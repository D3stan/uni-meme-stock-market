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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class MarketService
{
    /**
     * Propose a new meme (charges listing fee, creates pending meme).
     * 
     * @param User $creator
     * @param array $data ['title', 'ticker', 'category_id', 'image']
     * @return Meme
     * @throws InsufficientFundsException
     */
    public function proposeMeme(User $creator, array $data): Meme
    {
        return DB::transaction(function () use ($creator, $data) {
            // Lock user row
            $creator = User::where('id', $creator->id)->lockForUpdate()->first();

            // Get listing fee from global settings
            $listingFee = (float) GlobalSetting::get('listing_fee', 20.00);

            // Check if user has enough CFU
            if ($creator->cfu_balance < $listingFee) {
                throw new InsufficientFundsException($listingFee, $creator->cfu_balance);
            }

            // Deduct listing fee
            $creator->cfu_balance -= $listingFee;
            $creator->save();

            // Store image if provided
            $imagePath = null;
            if (isset($data['image'])) {
                $imagePath = $data['image']->store('memes', 'public');
            }

            // Create meme with pending status
            $meme = Meme::create([
                'creator_id' => $creator->id,
                'category_id' => $data['category_id'],
                'title' => $data['title'],
                'ticker' => strtoupper($data['ticker']),
                'image_path' => $imagePath,
                'status' => 'pending',
                'base_price' => 0, // Will be set by admin on approval
                'slope' => 0, // Will be set by admin on approval
                'current_price' => 0,
                'circulating_supply' => 0,
            ]);

            // Record listing fee transaction
            Transaction::create([
                'user_id' => $creator->id,
                'meme_id' => $meme->id,
                'type' => 'listing_fee',
                'quantity' => 0,
                'price_per_share' => 0,
                'fee_amount' => $listingFee,
                'total_amount' => $listingFee,
                'cfu_balance_after' => $creator->cfu_balance,
                'executed_at' => now(),
            ]);

            // TODO: Send notification to admins about new pending meme

            return $meme;
        });
    }

    /**
     * Approve a pending meme (sets price parameters and launches IPO).
     * 
     * @param User $admin
     * @param Meme $meme
     * @param float $basePrice
     * @param float $slope
     * @return bool
     */
    public function approveMeme(User $admin, Meme $meme, float $basePrice, float $slope): bool
    {
        return DB::transaction(function () use ($admin, $meme, $basePrice, $slope) {
            // Update meme with IPO parameters
            $meme->base_price = $basePrice;
            $meme->slope = $slope;
            $meme->current_price = $basePrice; // Initial price
            $meme->status = 'approved';
            $meme->approved_at = now();
            $meme->approved_by = $admin->id;
            $meme->save();

            // Create first price history record (IPO)
            PriceHistory::create([
                'meme_id' => $meme->id,
                'price' => $basePrice,
                'circulating_supply_snapshot' => 0,
                'trigger_type' => 'ipo',
                'recorded_at' => now(),
            ]);

            // Log admin action
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'approve_meme',
                'target_id' => $meme->id,
                'target_type' => 'meme',
                'reason' => sprintf(
                    'IPO approved: base_price=%.2f, slope=%.2f',
                    $basePrice,
                    $slope
                ),
                'created_at' => now(),
            ]);

            // TODO: Notify creator that meme was approved

            return true;
        });
    }

    /**
     * Suspend trading for a specific meme.
     * 
     * @param User $admin
     * @param Meme $meme
     * @param string $reason
     * @return bool
     */
    public function suspendMeme(User $admin, Meme $meme, string $reason): bool
    {
        return DB::transaction(function () use ($admin, $meme, $reason) {
            $meme->status = 'suspended';
            $meme->save();

            // Log admin action
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'suspend_meme',
                'target_id' => $meme->id,
                'target_type' => 'meme',
                'reason' => $reason,
                'created_at' => now(),
            ]);

            // TODO: Notify all holders of the meme

            return true;
        });
    }

    /**
     * Reactivate a suspended meme.
     * 
     * @param User $admin
     * @param Meme $meme
     * @param string $reason
     * @return bool
     */
    public function reactivateMeme(User $admin, Meme $meme, string $reason): bool
    {
        return DB::transaction(function () use ($admin, $meme, $reason) {
            $meme->status = 'approved';
            $meme->save();

            // Log admin action
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'reactivate_meme',
                'target_id' => $meme->id,
                'target_type' => 'meme',
                'reason' => $reason,
                'created_at' => now(),
            ]);

            return true;
        });
    }

    /**
     * Delist a meme (soft delete).
     * 
     * @param User $admin
     * @param Meme $meme
     * @param string $reason
     * @return bool
     */
    public function delistMeme(User $admin, Meme $meme, string $reason): bool
    {
        return DB::transaction(function () use ($admin, $meme, $reason) {
            // Soft delete the meme
            $meme->delete();

            // Log admin action
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'delist_meme',
                'target_id' => $meme->id,
                'target_type' => 'meme',
                'reason' => $reason,
                'created_at' => now(),
            ]);

            // TODO: Notify holders that meme was delisted

            return true;
        });
    }

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
}
