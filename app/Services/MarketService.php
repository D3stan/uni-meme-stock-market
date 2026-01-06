<?php

namespace App\Services;

use App\Models\User;
use App\Models\Market\Meme;
use App\Models\Market\Category;
use App\Models\Financial\Transaction;
use App\Models\Financial\PriceHistory;
use App\Models\Admin\GlobalSetting;
use App\Models\Admin\AdminAction;
use App\Models\Admin\MarketCommunication;
use App\Exceptions\Financial\InsufficientFundsException;
use App\Notifications\MemeProposedNotification;
use App\Notifications\MemeApprovedNotification;
use App\Notifications\MemeSuspendedNotification;
use App\Notifications\MemeDelistedNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MarketService
{
    /**
     * Create a market-wide communication message.
     * 
     * @param User $admin
     * @param string $message
     * @param Carbon|null $expiresAt
     * @return MarketCommunication
     */
    public function createMarketCommunication(
        User $admin,
        string $message,
        ?Carbon $expiresAt = null
    ): MarketCommunication {
        return DB::transaction(function () use ($admin, $message, $expiresAt) {
            $communication = MarketCommunication::create([
                'admin_id' => $admin->id,
                'message' => $message,
                'is_active' => true,
                'expires_at' => $expiresAt,
            ]);

            // Log admin action
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'create_communication',
                'target_id' => $communication->id,
                'target_type' => 'communication',
                'reason' => 'Market communication posted',
                'created_at' => now(),
            ]);

            return $communication;
        });
    }

    /**
     * Deactivate a market communication.
     * 
     * @param User $admin
     * @param MarketCommunication $communication
     * @return bool
     */
    public function deactivateMarketCommunication(
        User $admin,
        MarketCommunication $communication
    ): bool {
        return DB::transaction(function () use ($admin, $communication) {
            $communication->is_active = false;
            $communication->save();

            // Log admin action
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'deactivate_communication',
                'target_id' => $communication->id,
                'target_type' => 'communication',
                'reason' => 'Market communication deactivated',
                'created_at' => now(),
            ]);

            return true;
        });
    }

    /**
     * Update a global setting.
     * 
     * @param User $admin
     * @param string $key
     * @param mixed $value
     * @return bool
     */
    public function updateGlobalSetting(User $admin, string $key, $value): bool
    {
        GlobalSetting::set($key, $value);

        // Log admin action
        AdminAction::create([
            'admin_id' => $admin->id,
            'action_type' => 'update_setting',
            'target_id' => null,
            'target_type' => 'setting',
            'reason' => sprintf('Updated setting: %s = %s', $key, $value),
            'created_at' => now(),
        ]);

        return true;
    }

    /**
     * Get memes for marketplace with filtering support.
     * 
     * @param string $filter 'all', 'top_gainer', 'new_listing', 'high_risk'
     * @param int $perPage
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMarketplaceMemes(string $filter = 'all', int $perPage = 20)
    {
        $query = Meme::with(['creator:id,name,email,avatar', 'category:id,name'])
            ->where('status', 'approved')
            ->whereNotNull('approved_at');

        // Add price change 24h calculation
        $query->leftJoin('price_histories as ph_24h', function ($join) {
            $join->on('memes.id', '=', 'ph_24h.meme_id')
                ->where('ph_24h.recorded_at', '>=', now()->subHours(24))
                ->where('ph_24h.recorded_at', '=', function ($subQuery) {
                    $subQuery->select(DB::raw('MIN(recorded_at)'))
                        ->from('price_histories as ph_inner')
                        ->whereColumn('ph_inner.meme_id', 'memes.id')
                        ->where('ph_inner.recorded_at', '>=', now()->subHours(24));
                });
        });

        $query->select(
            'memes.*',
            DB::raw('COALESCE(ph_24h.price, memes.base_price) as price_24h_ago'),
            DB::raw('CASE 
                WHEN COALESCE(ph_24h.price, memes.base_price) > 0 
                THEN ((memes.current_price - COALESCE(ph_24h.price, memes.base_price)) / COALESCE(ph_24h.price, memes.base_price) * 100)
                ELSE 0 
            END as pct_change_24h')
        );

        // Apply filters
        switch ($filter) {
            case 'top_gainer':
                // Top gainers: highest positive percentage change in 24h
                $query->orderByRaw('pct_change_24h DESC');
                break;

            case 'new_listing':
                // New listings: approved in the last 7 days
                $query->where('approved_at', '>=', now()->subDays(7))
                    ->orderByDesc('approved_at');
                break;

            case 'high_risk':
                // High risk: high slope (volatile) OR low circulating supply
                $query->where(function ($q) {
                    $q->where('slope', '>=', 0.01) // High slope = more volatile
                        ->orWhere('circulating_supply', '<', 100); // Low supply = risky
                })
                ->orderByDesc('slope');
                break;

            case 'all':
            default:
                // All: ordered by market cap (current_price * circulating_supply)
                $query->orderByRaw('(memes.current_price * memes.circulating_supply) DESC');
                break;
        }

        // Paginate results
        $memes = $query->paginate($perPage);

        // Add computed attributes to each meme
        $memes->getCollection()->transform(function ($meme) {
            // Calculate 24h volume (sum of all transaction amounts in last 24h)
            $volume24h = Transaction::where('meme_id', $meme->id)
                ->whereIn('type', ['buy', 'sell'])
                ->where('executed_at', '>=', now()->subHours(24))
                ->sum('total_amount');

            // Determine status badge
            $statusBadge = null;
            if ($meme->approved_at && $meme->approved_at->diffInDays(now()) <= 7) {
                $statusBadge = 'new';
            }
            
            // Return formatted data for frontend
            return [
                'id' => $meme->id,
                'image' => $meme->image_path ? asset('storage/data/' . $meme->creator_id . '/' . $meme->image_path) : null,
                'text_alt'=> $meme->text_alt,
                'name' => $meme->title,
                'ticker' => $meme->ticker,
                'price' => round($meme->current_price, 2),
                'change' => round($meme->pct_change_24h ?? 0, 2),
                'creatorId' => $meme->creator_id,
                'creatorName' => $meme->creator->name ?? 'Unknown',
                'creatorAvatar' => $meme->creator->avatarUrl(),
                'status' => $statusBadge,
                'marketCap' => round($meme->current_price * $meme->circulating_supply, 2),
                'volume24h' => round($volume24h, 2),
                'circulatingSupply' => $meme->circulating_supply,
                'isHighRisk' => $meme->slope >= 0.01 || $meme->circulating_supply < 100,
                'categoryName' => $meme->category->name ?? null,
                'approvedAt' => $meme->approved_at?->toIso8601String(),
            ];
        });

        return $memes;
    }

    /**
     * Get top gainers for ticker tape.
     * 
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getTickerMemes(int $limit = 10)
    {
        return Meme::where('status', 'approved')
            ->whereNotNull('approved_at')
            ->leftJoin('price_histories as ph_24h', function ($join) {
                $join->on('memes.id', '=', 'ph_24h.meme_id')
                    ->where('ph_24h.recorded_at', '>=', now()->subHours(24))
                    ->where('ph_24h.recorded_at', '=', function ($subQuery) {
                        $subQuery->select(DB::raw('MIN(recorded_at)'))
                            ->from('price_histories as ph_inner')
                            ->whereColumn('ph_inner.meme_id', 'memes.id')
                            ->where('ph_inner.recorded_at', '>=', now()->subHours(24));
                    });
            })
            ->select(
                'memes.ticker',
                'memes.current_price',
                DB::raw('COALESCE(ph_24h.price, memes.base_price) as price_24h_ago'),
                DB::raw('CASE 
                    WHEN COALESCE(ph_24h.price, memes.base_price) > 0 
                    THEN ((memes.current_price - COALESCE(ph_24h.price, memes.base_price)) / COALESCE(ph_24h.price, memes.base_price) * 100)
                    ELSE 0 
                END as pct_change_24h')
            )
            ->orderByRaw('ABS(pct_change_24h) DESC')
            ->limit($limit)
            ->get()
            ->map(function ($meme) {
                return [
                    'ticker' => $meme->ticker,
                    'price' => round($meme->current_price, 2),
                    'change' => round($meme->pct_change_24h ?? 0, 2),
                ];
            });
    }

    /**
     * Get market surveillance data for admins.
     * 
     * @return array
     */
    public function getMarketSurveillanceData(): array
    {
        // Top gainers (24h)
        $topGainers = Meme::whereNotNull('approved_at')
            ->where('status', 'approved')
            ->orderByDesc('current_price')
            ->limit(10)
            ->get();

        // Top losers (24h) - would need price history comparison
        // For now, we'll get memes with lowest current prices
        $topLosers = Meme::whereNotNull('approved_at')
            ->where('status', 'approved')
            ->orderBy('current_price')
            ->limit(10)
            ->get();

        // Whale alerts (users holding >10% of any meme)
        $whaleAlerts = DB::table('portfolios')
            ->join('memes', 'portfolios.meme_id', '=', 'memes.id')
            ->join('users', 'portfolios.user_id', '=', 'users.id')
            ->select(
                'users.name',
                'users.email',
                'memes.ticker',
                'portfolios.quantity',
                'memes.circulating_supply',
                DB::raw('(portfolios.quantity / memes.circulating_supply * 100) as ownership_percentage')
            )
            ->whereRaw('portfolios.quantity / memes.circulating_supply > 0.10')
            ->orderByDesc('ownership_percentage')
            ->get();

        // Total fees collected
        $totalFeesCollected = Transaction::whereIn('type', ['buy', 'sell', 'listing_fee'])
            ->sum('fee_amount');

        return [
            'top_gainers' => $topGainers,
            'top_losers' => $topLosers,
            'whale_alerts' => $whaleAlerts,
            'total_fees_collected' => $totalFeesCollected,
        ];
    }

    /**
     * Get top movers for landing page with volume data.
     * 
     * @param int $limit
     * @return \Illuminate\Support\Collection
     */
    public function getLandingPageTopMovers(int $limit = 5)
    {
        $topMemes = $this->getMarketplaceMemes('top_gainer', $limit);
        
        // Calculate volume for each meme
        return $topMemes->map(function ($meme) {
            $volume24h = Transaction::where('meme_id', $meme['id'])
                ->whereIn('type', ['buy', 'sell'])
                ->where('executed_at', '>=', now()->subHours(24))
                ->sum('total_amount');
            
            $meme['volume24h'] = $volume24h;
            return $meme;
        });
    }
}
