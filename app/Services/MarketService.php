<?php

namespace App\Services;

use App\Models\Admin\AdminAction;
use App\Models\Admin\GlobalSetting;
use App\Models\Admin\MarketCommunication;
use App\Models\Financial\Transaction;
use App\Models\Market\Meme;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class MarketService
{
    /**
     * Create a market-wide communication message and log the admin action.
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
     * Deactivate a market communication and log the admin action.
     */
    public function deactivateMarketCommunication(
        User $admin,
        MarketCommunication $communication
    ): bool {
        return DB::transaction(function () use ($admin, $communication) {
            $communication->is_active = false;
            $communication->save();

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
     * Update a global setting and log the admin action.
     *
     * @param  mixed  $value
     */
    public function updateGlobalSetting(User $admin, string $key, $value): bool
    {
        GlobalSetting::set($key, $value);

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
     * Get memes for marketplace with filtering and 24h price change calculation.
     *
     * @param  string  $filter  'all', 'top_gainer', 'new_listing', 'high_risk'
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getMarketplaceMemes(string $filter = 'all', int $perPage = 20)
    {
        $query = Meme::with(['creator:id,name,email,avatar', 'category:id,name'])
            ->approved()
            ->whereNotNull('approved_at')
            ->with24hStats();

        switch ($filter) {
            case 'top_gainer':
                $query->orderByRaw('((current_price - COALESCE((SELECT price FROM price_histories WHERE meme_id = memes.id AND recorded_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY recorded_at ASC LIMIT 1), base_price)) / COALESCE((SELECT price FROM price_histories WHERE meme_id = memes.id AND recorded_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY recorded_at ASC LIMIT 1), base_price) * 100) DESC')
                      ->orderBy('id', 'desc');
                break;

            case 'new_listing':
                $query->where('approved_at', '>=', now()->subDays(7))
                    ->orderByDesc('approved_at')
                    ->orderBy('id', 'desc');
                break;

            case 'high_risk':
                $query->where(function ($q) {
                    $q->where('slope', '>=', 0.01)
                        ->orWhere('circulating_supply', '<', 100);
                    $q->where('slope', '>=', 0.01)
                        ->orWhere('circulating_supply', '<', 100);
                })
                ->orderByDesc('slope')
                ->orderBy('id', 'desc');
                break;

            case 'all':
            default:
                $query->orderByRaw('(current_price * circulating_supply) DESC')
                      ->orderBy('id', 'desc');
                break;
        }

        $memes = $query->paginate($perPage);

        $memes->getCollection()->transform(function ($meme) {
            return [
                'id' => $meme->id,
                'image' => $meme->image_path ? asset('storage/data/'.$meme->creator_id.'/'.$meme->image_path) : null,
                'text_alt' => $meme->text_alt,
                'name' => $meme->title,
                'ticker' => $meme->ticker,
                'price' => round($meme->current_price, 2),
                'change' => round($meme->pct_change_24h, 2),
                'creatorId' => $meme->creator_id,
                'creatorName' => $meme->creator->name ?? 'Unknown',
                'creatorAvatar' => $meme->creator->avatarUrl(),
                'status' => ($meme->approved_at && $meme->approved_at->diffInDays(now()) <= 7) ? 'new' : null,
                'marketCap' => round($meme->current_price * $meme->circulating_supply, 2),
                'volume24h' => round($meme->volume_24h, 2),
                'circulatingSupply' => $meme->circulating_supply,
                'isHighRisk' => $meme->slope >= 0.01 || $meme->circulating_supply < 100,
                'categoryName' => $meme->category->name ?? null,
                'approvedAt' => $meme->approved_at?->toIso8601String(),
            ];
        });

        return $memes;
    }

    /**
     * Check if a meme is considered high risk based on slope or supply.
     */
    public function isHighRiskMeme(Meme $meme): bool
    {
        return $meme->slope >= 0.01 || $meme->circulating_supply < 100;
    }

    /**
     * Get top gainers for the ticker tape.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getTickerMemes(int $limit = 10)
    {
        return Meme::approved()
            ->whereNotNull('approved_at')
            ->with24hStats()
            ->orderByRaw('ABS(((current_price - COALESCE((SELECT price FROM price_histories WHERE meme_id = memes.id AND recorded_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY recorded_at ASC LIMIT 1), base_price)) / COALESCE((SELECT price FROM price_histories WHERE meme_id = memes.id AND recorded_at >= DATE_SUB(NOW(), INTERVAL 24 HOUR) ORDER BY recorded_at ASC LIMIT 1), base_price) * 100)) DESC')
            ->limit($limit)
            ->get()
            ->map(function ($meme) {
                return [
                    'id' => $meme->id,
                    'ticker' => $meme->ticker,
                    'price' => round($meme->current_price, 2),
                    'change' => round($meme->pct_change_24h, 2),
                ];
            });
    }

    /**
     * Get market surveillance data including top gainers/losers, whale alerts, and fees.
     */
    public function getMarketSurveillanceData(): array
    {
        $topGainers = Meme::whereNotNull('approved_at')
            ->where('status', 'approved')
            ->orderByDesc('current_price')
            ->limit(10)
            ->get();

        $topLosers = Meme::whereNotNull('approved_at')
            ->where('status', 'approved')
            ->orderBy('current_price')
            ->limit(10)
            ->get();

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
     * Get top movers for landing page including volume data.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getLandingPageTopMovers(int $limit = 5)
    {
        $topMemes = $this->getMarketplaceMemes('top_gainer', $limit);

        return collect($topMemes->items())->map(function ($meme) {
            return $meme;
        });
    }
}
