<?php

namespace App\Services;

use App\Models\User;
use App\Models\Financial\Transaction;
use App\Models\Financial\Portfolio;
use App\Models\Financial\PriceHistory;
use App\Models\Gamification\Badge;
use App\Models\Gamification\UserBadge;
use App\Notifications\BadgeAwardedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Collection;
use Carbon\Carbon;

class UserService
{
    /**
     * Award the registration bonus to a new user (100 CFU).
     * 
     * @param User $user
     * @return Transaction
     */
    public function awardRegistrationBonus(User $user): Transaction
    {
        return DB::transaction(function () use ($user) {
            // Lock user row
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            $bonusAmount = 100.00;

            // Credit bonus to user
            $user->cfu_balance += $bonusAmount;
            $user->last_daily_bonus_at = now();
            $user->save();

            // Record transaction
            $transaction = Transaction::create([
                'user_id' => $user->id,
                'meme_id' => null,
                'type' => 'bonus',
                'quantity' => 0,
                'price_per_share' => 0,
                'fee_amount' => 0,
                'total_amount' => $bonusAmount,
                'cfu_balance_after' => $user->cfu_balance,
                'executed_at' => now(),
            ]);

            return $transaction;
        });
    }

    /**
     * Calculate the user's net worth (lazy calculation with cache update).
     * Net Worth = Liquid Balance + Sum(Portfolio Value)
     * 
     * Optimized with single aggregation query instead of N+1.
     * 
     * @param User $user
     * @param bool $updateCache Whether to update the cached_net_worth field
     * @return float
     */
    public function calculateNetWorth(User $user, bool $updateCache = true): float
    {
        // Get liquid balance
        $liquidBalance = (float) $user->cfu_balance;

        // Calculate invested value with optimized single query (join + aggregation)
        $investedValue = (float) Portfolio::where('portfolios.user_id', $user->id)
            ->join('memes', 'portfolios.meme_id', '=', 'memes.id')
            ->selectRaw('SUM(portfolios.quantity * memes.current_price) as total_value')
            ->value('total_value') ?? 0;

        $netWorth = $liquidBalance + $investedValue;

        // Update cache if requested
        if ($updateCache) {
            $user->cached_net_worth = $netWorth;
            $user->save();
        }

        return round($netWorth, 2);
    }

    /**
     * Get portfolio breakdown with PNL (Profit & Loss) for each position.
     * 
     * Optimized with eager loading and efficient calculations.
     * 
     * @param User $user
     * @return Collection
     */
    public function getPortfolioBreakdown(User $user): Collection
    {
        return Portfolio::where('user_id', $user->id)
            ->with('meme:id,ticker,title,current_price,image_path')
            ->orderBy('quantity', 'desc')
            ->get()
            ->map(function ($portfolio) {
                $currentPrice = (float) $portfolio->meme->current_price;
                $avgBuyPrice = (float) $portfolio->avg_buy_price;
                $quantity = $portfolio->quantity;

                $currentValue = $quantity * $currentPrice;
                $costBasis = $quantity * $avgBuyPrice;
                $unrealizedPnl = $currentValue - $costBasis;
                $unrealizedPnlPercent = $costBasis > 0 
                    ? (($currentValue - $costBasis) / $costBasis) * 100 
                    : 0;

                return [
                    'meme_id' => $portfolio->meme->id,
                    'ticker' => $portfolio->meme->ticker,
                    'title' => $portfolio->meme->title,
                    'image_path' => $portfolio->meme->image_path,
                    'quantity' => $quantity,
                    'avg_buy_price' => round($avgBuyPrice, 4),
                    'current_price' => round($currentPrice, 4),
                    'cost_basis' => round($costBasis, 2),
                    'current_value' => round($currentValue, 2),
                    'unrealized_pnl' => round($unrealizedPnl, 2),
                    'unrealized_pnl_percent' => round($unrealizedPnlPercent, 2),
                ];
            });
    }

    /**
     * Get asset allocation (liquid vs invested).
     * 
     * Optimized to reuse net worth calculation instead of recalculating.
     * 
     * @param User $user
     * @return array
     */
    public function getAssetAllocation(User $user): array
    {
        $liquidBalance = (float) $user->cfu_balance;

        // Optimized: single query with join
        $investedValue = (float) Portfolio::where('portfolios.user_id', $user->id)
            ->join('memes', 'portfolios.meme_id', '=', 'memes.id')
            ->selectRaw('SUM(portfolios.quantity * memes.current_price) as total_value')
            ->value('total_value') ?? 0;

        $total = $liquidBalance + $investedValue;

        return [
            'liquid' => round($liquidBalance, 2),
            'invested' => round($investedValue, 2),
            'total' => round($total, 2),
            'liquid_percent' => $total > 0 ? round(($liquidBalance / $total) * 100, 2) : 0,
            'invested_percent' => $total > 0 ? round(($investedValue / $total) * 100, 2) : 0,
        ];
    }

    /**
     * Calculate daily PNL (Profit & Loss since yesterday).
     * 
     * Uses transaction history + price histories to reconstruct yesterday's net worth.
     * Yesterday Net Worth = Yesterday Liquid Balance + Yesterday Portfolio Value
     * 
     * @param User $user
     * @return array
     */
    public function getDailyPnl(User $user): array
    {
        $todayStart = now()->startOfDay();
        $yesterdayEnd = now()->subDay()->endOfDay();
        
        // Get yesterday's liquid balance from last transaction before today
        $lastTransactionYesterday = Transaction::where('user_id', $user->id)
            ->where('executed_at', '<', $todayStart)
            ->orderByDesc('executed_at')
            ->first();

        $yesterdayLiquid = $lastTransactionYesterday 
            ? (float) $lastTransactionYesterday->cfu_balance_after 
            : 100.00; // Default registration bonus if no transactions yet

        // Calculate yesterday's portfolio value using price histories
        $yesterdayInvested = 0;
        $portfolios = Portfolio::where('user_id', $user->id)->get();
        
        foreach ($portfolios as $portfolio) {
            // Get the last price recorded before today for this meme
            $yesterdayPrice = PriceHistory::where('meme_id', $portfolio->meme_id)
                ->where('recorded_at', '<', $todayStart)
                ->orderByDesc('recorded_at')
                ->value('price');
            
            // If no price history exists, use current price (meme was just listed)
            if ($yesterdayPrice === null) {
                $yesterdayPrice = (float) $portfolio->meme->current_price;
            }
            
            $yesterdayInvested += $portfolio->quantity * (float) $yesterdayPrice;
        }

        $yesterdayNetWorth = $yesterdayLiquid + $yesterdayInvested;
        
        // Calculate current net worth
        $currentNetWorth = $this->calculateNetWorth($user, false);
        
        // Calculate PNL
        $dailyPnl = $currentNetWorth - $yesterdayNetWorth;
        $dailyPnlPercent = $yesterdayNetWorth > 0 
            ? (($dailyPnl / $yesterdayNetWorth) * 100) 
            : 0;

        return [
            'daily_pnl' => round($dailyPnl, 2),
            'daily_pnl_percent' => round($dailyPnlPercent, 2),
            'current_net_worth' => round($currentNetWorth, 2),
            'yesterday_net_worth' => round($yesterdayNetWorth, 2),
        ];
    }

    /**
     * Check user's achievements and award badges if eligible.
     * 
     * @param User $user
     * @return Collection Newly awarded badges
     */
    public function checkAndAwardBadges(User $user): Collection
    {
        $newBadges = collect();

        // Get all badges user doesn't have yet
        $existingBadgeIds = UserBadge::where('user_id', $user->id)
            ->pluck('badge_id')
            ->toArray();

        $availableBadges = Badge::whereNotIn('id', $existingBadgeIds)->get();

        foreach ($availableBadges as $badge) {
            $eligible = false;

            // Check badge criteria based on name
            // This is simplified - in production you'd have a more robust system
            switch ($badge->name) {
                case 'Diamond Hands':
                    $eligible = $this->checkDiamondHandsEligibility($user);
                    break;

                case 'IPO Hunter':
                    $eligible = $this->checkIpoHunterEligibility($user);
                    break;

                case 'Liquidator':
                    $eligible = $this->checkLiquidatorEligibility($user);
                    break;
            }

            if ($eligible) {
                UserBadge::create([
                    'user_id' => $user->id,
                    'badge_id' => $badge->id,
                    'awarded_at' => now(),
                ]);
                $newBadges->push($badge);
                
                // Notify user about new badge
                $user->notify(new BadgeAwardedNotification($badge));
            }
        }

        return $newBadges;
    }

    /**
     * Check if user held a position for >1 week without selling.
     * 
     * @param User $user
     * @return bool
     */
    private function checkDiamondHandsEligibility(User $user): bool
    {
        // Get first buy transaction for each meme
        $portfolios = Portfolio::where('user_id', $user->id)->get();

        foreach ($portfolios as $portfolio) {
            $firstBuy = Transaction::where('user_id', $user->id)
                ->where('meme_id', $portfolio->meme_id)
                ->where('type', 'buy')
                ->orderBy('executed_at')
                ->first();

            if ($firstBuy && now()->diffInWeeks($firstBuy->executed_at) >= 1) {
                // Check if they never sold completely
                $totalBuys = Transaction::where('user_id', $user->id)
                    ->where('meme_id', $portfolio->meme_id)
                    ->where('type', 'buy')
                    ->where('executed_at', '>=', $firstBuy->executed_at)
                    ->sum('quantity');

                $totalSells = Transaction::where('user_id', $user->id)
                    ->where('meme_id', $portfolio->meme_id)
                    ->where('type', 'sell')
                    ->where('executed_at', '>=', $firstBuy->executed_at)
                    ->sum('quantity');

                if ($totalBuys > $totalSells) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Check if user participated in 5+ IPOs.
     * 
     * @param User $user
     * @return bool
     */
    private function checkIpoHunterEligibility(User $user): bool
    {
        // Count distinct memes where user bought within 8 hours of approval
        $ipoParticipations = Transaction::where('user_id', $user->id)
            ->where('type', 'buy')
            ->with('meme')
            ->get()
            ->filter(function ($transaction) {
                if (!$transaction->meme || !$transaction->meme->approved_at) {
                    return false;
                }
                
                $hoursSinceApproval = $transaction->meme->approved_at
                    ->diffInHours($transaction->executed_at);
                
                return $hoursSinceApproval >= 8 && $hoursSinceApproval <= 16;
            })
            ->pluck('meme_id')
            ->unique()
            ->count();

        return $ipoParticipations >= 5;
    }

    /**
     * Check if user reached 0 CFU balance.
     * 
     * @param User $user
     * @return bool
     */
    private function checkLiquidatorEligibility(User $user): bool
    {
        return $user->cfu_balance == 0;
    }

    /**
     * Deactivate a user account (freeze, don't liquidate positions).
     * 
     * @param User $user
     * @return bool
     */
    public function deactivateAccount(User $user): bool
    {
        $user->is_suspended = true;
        $user->save();

        return true;
    }

    /**
     * Reactivate a suspended user account.
     * 
     * @param User $user
     * @return bool
     */
    public function reactivateAccount(User $user): bool
    {
        $user->is_suspended = false;
        $user->save();

        return true;
    }
}
