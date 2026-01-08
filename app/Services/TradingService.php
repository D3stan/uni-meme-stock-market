<?php

namespace App\Services;

use App\Exceptions\Financial\InsufficientFundsException;
use App\Exceptions\Financial\InsufficientSharesException;
use App\Exceptions\Market\MarketSuspendedException;
use App\Exceptions\Market\SlippageExceededException;
use App\Models\Admin\GlobalSetting;
use App\Models\Financial\Portfolio;
use App\Models\Financial\PriceHistory;
use App\Models\Financial\Transaction;
use App\Models\Market\Meme;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class TradingService
{
    /**
     * Trading delay in hours after meme approval before trading can begin.
     */
    private const TRADING_DELAY_HOURS = 8;

    /**
     * Maximum allowed slippage tolerance in CFU for price change protection.
     * If the price changes by more than this amount, the user must re-confirm.
     */
    private const SLIPPAGE_TOLERANCE_CFU = 0.01;

    /**
     * Check if trading is allowed for a meme (status and delay checks).
     *
     * @throws MarketSuspendedException
     */
    private function validateTradingAllowed(Meme $meme): void
    {
        if ($meme->status === 'suspended') {
            throw new MarketSuspendedException($meme->ticker);
        }

        $tradingDelayHours = (int) GlobalSetting::get('trading_delay_hours', self::TRADING_DELAY_HOURS);
        $tradingEnabledAt = $meme->approved_at?->copy()->addHours($tradingDelayHours);

        if ($tradingEnabledAt && now()->isBefore($tradingEnabledAt)) {
            throw new MarketSuspendedException(
                $meme->ticker,
                "Trading for {$meme->ticker} will be available {$tradingEnabledAt->diffForHumans()}."
            );
        }
    }

    /**
     * Calculate the total cost for buying k shares using the integral formula:
     * CostTotal = P_base * k + (M/2) * ((S+k)² - S²)
     */
    public function calculateBuyCost(Meme $meme, int $quantity): array
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        $pBase = (float) $meme->base_price;
        $slope = (float) $meme->slope;
        $currentSupply = $meme->circulating_supply;

        $subtotal = ($pBase * $quantity) +
                    (($slope / 2) * ((pow($currentSupply + $quantity, 2)) - pow($currentSupply, 2)));

        $taxRate = (float) GlobalSetting::get('tax_rate', 0.02);
        $fee = $subtotal * $taxRate;
        $total = $subtotal + $fee;

        return [
            'subtotal' => round($subtotal, 2),
            'fee' => round($fee, 2),
            'total' => round($total, 2),
            'current_supply' => $currentSupply,
            'estimated_price' => round($pBase + ($slope * ($currentSupply + ($quantity / 2))), 4),
        ];
    }

    /**
     * Calculate the total income for selling k shares using the integral formula:
     * IncomeTotal = P_base * k + (M/2) * (S² - (S-k)²)
     */
    public function calculateSellIncome(Meme $meme, int $quantity): array
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        $pBase = (float) $meme->base_price;
        $slope = (float) $meme->slope;
        $currentSupply = $meme->circulating_supply;

        $subtotal = ($pBase * $quantity) +
                    (($slope / 2) * (pow($currentSupply, 2) - pow($currentSupply - $quantity, 2)));

        $taxRate = (float) GlobalSetting::get('tax_rate', 0.02);
        $fee = $subtotal * $taxRate;
        $total = $subtotal - $fee;

        return [
            'subtotal' => round($subtotal, 2),
            'fee' => round($fee, 2),
            'total' => round($total, 2),
            'current_supply' => $currentSupply,
            'estimated_price' => round($pBase + ($slope * ($currentSupply - ($quantity / 2))), 4),
        ];
    }

    /**
     * Preview an order without executing it.
     *
     * @param  string  $type  'buy' or 'sell'
     */
    public function previewOrder(Meme $meme, string $type, int $quantity): array
    {
        $this->validateTradingAllowed($meme);

        if ($type === 'buy') {
            return $this->calculateBuyCost($meme, $quantity);
        }

        return $this->calculateSellIncome($meme, $quantity);
    }

    /**
     * Execute a buy order with full atomicity, concurrency control, and slippage protection.
     * Locks relevant database rows to prevent race conditions.
     *
     * @throws InsufficientFundsException
     * @throws MarketSuspendedException
     * @throws SlippageExceededException
     */
    public function executeBuy(User $user, Meme $meme, int $quantity, ?float $expectedTotal = null): Transaction
    {
        return DB::transaction(function () use ($user, $meme, $quantity, $expectedTotal) {
            $meme = Meme::where('id', $meme->id)->lockForUpdate()->first();

            $user = User::where('id', $user->id)->lockForUpdate()->first();

            $this->validateTradingAllowed($meme);

            $calculation = $this->calculateBuyCost($meme, $quantity);

            if ($expectedTotal !== null && abs($calculation['total'] - $expectedTotal) > self::SLIPPAGE_TOLERANCE_CFU) {
                throw SlippageExceededException::withTotal($expectedTotal, $calculation['total']);
            }

            if ($user->cfu_balance < $calculation['total']) {
                throw new InsufficientFundsException($calculation['total'], $user->cfu_balance);
            }

            $user->cfu_balance -= $calculation['total'];
            $user->save();

            $meme->circulating_supply += $quantity;

            $meme->current_price = $meme->base_price + ($meme->slope * $meme->circulating_supply);
            $meme->save();

            $portfolio = Portfolio::firstOrNew([
                'user_id' => $user->id,
                'meme_id' => $meme->id,
            ]);

            if ($portfolio->exists) {
                $oldValue = $portfolio->quantity * $portfolio->avg_buy_price;
                $newValue = $quantity * $calculation['estimated_price'];
                $portfolio->avg_buy_price = ($oldValue + $newValue) / ($portfolio->quantity + $quantity);
                $portfolio->quantity += $quantity;
            } else {
                $portfolio->quantity = $quantity;
                $portfolio->avg_buy_price = $calculation['estimated_price'];
            }
            $portfolio->save();

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'meme_id' => $meme->id,
                'type' => 'buy',
                'quantity' => $quantity,
                'price_per_share' => $calculation['estimated_price'],
                'fee_amount' => $calculation['fee'],
                'total_amount' => $calculation['total'],
                'cfu_balance_after' => $user->cfu_balance,
                'executed_at' => now(),
            ]);

            PriceHistory::create([
                'meme_id' => $meme->id,
                'price' => $meme->current_price,
                'circulating_supply_snapshot' => $meme->circulating_supply,
                'trigger_type' => 'buy',
                'recorded_at' => now(),
            ]);

            return $transaction;
        });
    }

    /**
     * Execute a sell order with full atomicity, concurrency control, and slippage protection.
     * Locks relevant database rows to prevent race conditions.
     *
     * @throws InsufficientSharesException
     * @throws MarketSuspendedException
     * @throws SlippageExceededException
     */
    public function executeSell(User $user, Meme $meme, int $quantity, ?float $expectedTotal = null): Transaction
    {
        return DB::transaction(function () use ($user, $meme, $quantity, $expectedTotal) {
            $meme = Meme::where('id', $meme->id)->lockForUpdate()->first();

            $user = User::where('id', $user->id)->lockForUpdate()->first();

            if ($meme->status === 'suspended') {
                throw new MarketSuspendedException($meme->ticker);
            }

            $portfolio = Portfolio::where('user_id', $user->id)
                ->where('meme_id', $meme->id)
                ->lockForUpdate()
                ->first();

            if (! $portfolio || $portfolio->quantity < $quantity) {
                $available = $portfolio ? $portfolio->quantity : 0;
                throw new InsufficientSharesException($quantity, $available, $meme->ticker);
            }

            $calculation = $this->calculateSellIncome($meme, $quantity);

            if ($expectedTotal !== null && abs($calculation['total'] - $expectedTotal) > self::SLIPPAGE_TOLERANCE_CFU) {
                throw SlippageExceededException::withTotal($expectedTotal, $calculation['total']);
            }

            $user->cfu_balance += $calculation['total'];
            $user->save();

            $meme->circulating_supply -= $quantity;

            $meme->current_price = $meme->base_price + ($meme->slope * $meme->circulating_supply);
            $meme->save();

            $portfolio->quantity -= $quantity;

            if ($portfolio->quantity == 0) {
                $portfolio->delete();
            } else {
                $portfolio->save();
            }

            $transaction = Transaction::create([
                'user_id' => $user->id,
                'meme_id' => $meme->id,
                'type' => 'sell',
                'quantity' => $quantity,
                'price_per_share' => $calculation['estimated_price'],
                'fee_amount' => $calculation['fee'],
                'total_amount' => $calculation['total'],
                'cfu_balance_after' => $user->cfu_balance,
                'executed_at' => now(),
            ]);

            PriceHistory::create([
                'meme_id' => $meme->id,
                'price' => $meme->current_price,
                'circulating_supply_snapshot' => $meme->circulating_supply,
                'trigger_type' => 'sell',
                'recorded_at' => now(),
            ]);

            return $transaction;
        });
    }
}
