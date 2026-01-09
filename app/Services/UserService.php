<?php

namespace App\Services;

use App\Models\Financial\Portfolio;
use App\Models\Financial\PriceHistory;
use App\Models\Financial\Transaction;
use App\Models\Gamification\Badge;
use App\Models\Gamification\UserBadge;
use App\Models\User;
use App\Notifications\BadgeAwardedNotification;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Award the registration bonus to a new user (100 CFU).
     */
    public function awardRegistrationBonus(User $user): Transaction
    {
        return DB::transaction(function () use ($user) {
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            $bonusAmount = 100.00;

            $user->cfu_balance += $bonusAmount;
            $user->last_daily_bonus_at = now();
            $user->save();

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
     * Calculate the user's net worth (Liquid Balance + Sum of Portfolio Value).
     * Optimized with a single aggregation query.
     *
     * @param  bool  $updateCache  Whether to update the cached_net_worth field
     */
    public function calculateNetWorth(User $user, bool $updateCache = true): float
    {
        $liquidBalance = (float) $user->cfu_balance;

        $investedValue = (float) Portfolio::where('portfolios.user_id', $user->id)
            ->join('memes', 'portfolios.meme_id', '=', 'memes.id')
            ->selectRaw('SUM(portfolios.quantity * memes.current_price) as total_value')
            ->value('total_value') ?? 0;

        $netWorth = $liquidBalance + $investedValue;

        if ($updateCache) {
            $user->cached_net_worth = $netWorth;
            $user->save();
        }

        return round($netWorth, 2);
    }

    /**
     * Get portfolio breakdown with PNL (Profit & Loss) for each position.
     * Optimized with eager loading.
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
     */
    public function getAssetAllocation(User $user): array
    {
        $liquidBalance = (float) $user->cfu_balance;

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
     * Reconstructs yesterday's net worth using transaction and price history.
     */
    public function getDailyPnl(User $user): array
    {
        $todayStart = now()->startOfDay();

        $lastTransactionYesterday = Transaction::where('user_id', $user->id)
            ->where('executed_at', '<', $todayStart)
            ->orderByDesc('executed_at')
            ->first();

        $yesterdayLiquid = $lastTransactionYesterday
            ? (float) $lastTransactionYesterday->cfu_balance_after
            : 100.00;

        $yesterdayInvested = 0;
        $portfolios = Portfolio::where('user_id', $user->id)->get();

        foreach ($portfolios as $portfolio) {
            $yesterdayPrice = PriceHistory::where('meme_id', $portfolio->meme_id)
                ->where('recorded_at', '<', $todayStart)
                ->orderByDesc('recorded_at')
                ->value('price');

            if ($yesterdayPrice === null) {
                $yesterdayPrice = (float) $portfolio->meme->current_price;
            }

            $yesterdayInvested += $portfolio->quantity * (float) $yesterdayPrice;
        }

        $yesterdayNetWorth = $yesterdayLiquid + $yesterdayInvested;

        $currentNetWorth = $this->calculateNetWorth($user, false);

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
     * Check user's achievements and award eligible badges.
     */
    public function checkAndAwardBadges(User $user): Collection
    {
        $newBadges = collect();

        $existingBadgeIds = UserBadge::where('user_id', $user->id)
            ->pluck('badge_id')
            ->toArray();

        $availableBadges = Badge::whereNotIn('id', $existingBadgeIds)->get();

        foreach ($availableBadges as $badge) {
            $eligible = false;

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

                $user->notify(new BadgeAwardedNotification($badge));
            }
        }

        return $newBadges;
    }

    /**
     * Check if user held a position for >1 week without selling completely.
     */
    private function checkDiamondHandsEligibility(User $user): bool
    {
        $portfolios = Portfolio::where('user_id', $user->id)->get();

        foreach ($portfolios as $portfolio) {
            $firstBuy = Transaction::where('user_id', $user->id)
                ->where('meme_id', $portfolio->meme_id)
                ->where('type', 'buy')
                ->orderBy('executed_at')
                ->first();

            if ($firstBuy && now()->diffInWeeks($firstBuy->executed_at) >= 1) {
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
     * Check if user participated in 5+ IPOs (bought within 8-16h of approval).
     */
    private function checkIpoHunterEligibility(User $user): bool
    {
        $ipoParticipations = Transaction::where('user_id', $user->id)
            ->where('type', 'buy')
            ->with('meme')
            ->get()
            ->filter(function ($transaction) {
                if (! $transaction->meme || ! $transaction->meme->approved_at) {
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
     */
    private function checkLiquidatorEligibility(User $user): bool
    {
        return $user->cfu_balance == 0;
    }

    /**
     * Deactivate a user account (freeze, don't liquidate positions).
     */
    public function deactivateAccount(User $user): bool
    {
        $user->is_suspended = true;
        $user->save();

        return true;
    }

    /**
     * Reactivate a suspended user account.
     */
    public function reactivateAccount(User $user): bool
    {
        $user->is_suspended = false;
        $user->save();

        return true;
    }
}
