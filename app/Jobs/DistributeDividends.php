<?php

namespace App\Jobs;

use App\Models\Financial\DividendHistory;
use App\Models\Financial\Portfolio;
use App\Models\Financial\PriceHistory;
use App\Models\Financial\Transaction;
use App\Models\Market\Meme;
use App\Models\User;
use App\Services\NotificationDispatcher;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DistributeDividends implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(NotificationDispatcher $notificationDispatcher): void
    {
        Log::info('Starting dividend distribution process');

        $memes = Meme::approved()
            ->where('circulating_supply', '>', 0)
            ->get();

        $totalMemesProcessed = 0;
        $totalDividendsDistributed = 0;

        foreach ($memes as $meme) {
            try {
                $distributed = $this->processMeme($meme, $notificationDispatcher);
                if ($distributed) {
                    $totalMemesProcessed++;
                    $totalDividendsDistributed += $distributed;
                }
            } catch (\Exception $e) {
                Log::error('Error processing dividend for meme', [
                    'meme_id' => $meme->id,
                    'ticker' => $meme->ticker,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        Log::info('Dividend distribution completed', [
            'memes_processed' => $totalMemesProcessed,
            'total_distributed' => $totalDividendsDistributed,
        ]);
    }

    /**
     * Process dividend distribution for a single meme.
     *
     * @return float|null Total amount distributed, or null if no distribution
     */
    private function processMeme(Meme $meme, NotificationDispatcher $notificationDispatcher): ?float
    {
        if (! $this->hasPositiveTrend($meme)) {
            return null;
        }

        $marketValue = $meme->current_price * $meme->circulating_supply;
        $dividendPercentage = 0.01; // 1% of market value
        $totalDividend = $marketValue * $dividendPercentage;
        $amountPerShare = $totalDividend / $meme->circulating_supply;

        $shareholders = Portfolio::where('meme_id', $meme->id)
            ->where('quantity', '>', 0)
            ->with('user')
            ->get();

        if ($shareholders->isEmpty()) {
            return null;
        }

        DB::beginTransaction();

        try {
            $distributedAmount = 0;

            foreach ($shareholders as $portfolio) {
                $userDividend = $amountPerShare * $portfolio->quantity;

                $user = $portfolio->user;
                $user->cfu_balance += $userDividend;
                $user->save();

                Transaction::create([
                    'user_id' => $user->id,
                    'meme_id' => $meme->id,
                    'type' => 'dividend',
                    'quantity' => $portfolio->quantity,
                    'price_per_share' => $amountPerShare,
                    'fee_amount' => 0,
                    'total_amount' => $userDividend,
                    'cfu_balance_after' => $user->cfu_balance,
                    'executed_at' => now(),
                ]);

                if ($user->notify_dividends ?? true) {
                    $notificationDispatcher->dividendReceived($user, $userDividend, $meme->ticker);
                }

                $distributedAmount += $userDividend;
            }

            DividendHistory::create([
                'meme_id' => $meme->id,
                'amount_per_share' => $amountPerShare,
                'total_distributed' => $distributedAmount,
                'distributed_at' => now(),
            ]);

            DB::commit();

            Log::info('Dividend distributed successfully', [
                'meme_id' => $meme->id,
                'ticker' => $meme->ticker,
                'total_distributed' => $distributedAmount,
                'shareholders_count' => $shareholders->count(),
            ]);

            return $distributedAmount;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Check if the meme has a positive price trend in the last 24 hours.
     */
    private function hasPositiveTrend(Meme $meme): bool
    {
        $priceHistory = PriceHistory::where('meme_id', $meme->id)
            ->where('recorded_at', '>=', now()->subHours(24))
            ->orderBy('recorded_at', 'asc')
            ->first();

        if (! $priceHistory) {
            return false;
        }

        return $meme->current_price > $priceHistory->price;
    }
}
