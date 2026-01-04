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
     * TODO: Make this configurable via Admin Panel (GlobalSettings).
     */
    private const TRADING_DELAY_HOURS = 8;

    /**
     * Maximum allowed slippage tolerance in CFU for price change protection.
     * If the price changes by more than this amount, the user must re-confirm.
     * TODO: Make this configurable via Admin Panel (GlobalSettings).
     */
    private const SLIPPAGE_TOLERANCE_CFU = 0.01;

    /**
     * Calculate the total cost for buying k shares using the integral formula.
     * Formula: CostTotal = P_base * k + (M/2) * ((S+k)² - S²)
     *
     * @return array ['subtotal' => float, 'fee' => float, 'total' => float]
     */
    public function calculateBuyCost(Meme $meme, int $quantity): array
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        $pBase = (float) $meme->base_price;
        $slope = (float) $meme->slope;
        $currentSupply = $meme->circulating_supply;

        // Integral formula for buying
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
     * Calculate the total income for selling k shares using the integral formula.
     * Formula: IncomeTotal = P_base * k + (M/2) * (S² - (S-k)²)
     *
     * @return array ['subtotal' => float, 'fee' => float, 'total' => float]
     */
    public function calculateSellIncome(Meme $meme, int $quantity): array
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException('Quantity must be greater than zero.');
        }

        $pBase = (float) $meme->base_price;
        $slope = (float) $meme->slope;
        $currentSupply = $meme->circulating_supply;

        // Integral formula for selling
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
     * Used for the "Anteprima Ordine" step.
     *
     * @param  string  $type  'buy' or 'sell'
     */
    public function previewOrder(Meme $meme, string $type, int $quantity): array
    {
        // Check if market is suspended
        if ($meme->status === 'suspended') {
            throw new MarketSuspendedException($meme->ticker);
        }

        // Check if trading is enabled (delay from approval)
        $tradingDelayHours = (int) GlobalSetting::get('trading_delay_hours', self::TRADING_DELAY_HOURS);
        $tradingEnabledAt = $meme->approved_at?->copy()->addHours($tradingDelayHours);
        
        if ($tradingEnabledAt && now()->isBefore($tradingEnabledAt)) {
            throw new MarketSuspendedException(
                $meme->ticker,
                "Trading for {$meme->ticker} will be available {$tradingEnabledAt->diffForHumans()}."
            );
        }

        if ($type === 'buy') {
            return $this->calculateBuyCost($meme, $quantity);
        }

        return $this->calculateSellIncome($meme, $quantity);
    }

    /**
     * Execute a buy order with full atomicity and concurrency control.
     *
     * @param  float|null  $expectedTotal  If provided, throws SlippageExceededException if price changed
     *
     * @throws InsufficientFundsException
     * @throws MarketSuspendedException
     * @throws SlippageExceededException
     */
    public function executeBuy(User $user, Meme $meme, int $quantity, ?float $expectedTotal = null): Transaction
    {
        return DB::transaction(function () use ($user, $meme, $quantity, $expectedTotal) {
            // Lock the meme row to prevent race conditions
            $meme = Meme::where('id', $meme->id)->lockForUpdate()->first();

            // Lock the user row
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            // Check market status
            if ($meme->status === 'suspended') {
                throw new MarketSuspendedException($meme->ticker);
            }

            // Check trading delay
            $tradingDelayHours = (int) GlobalSetting::get('trading_delay_hours', self::TRADING_DELAY_HOURS);
            if ($meme->approved_at && now()->diffInHours($meme->approved_at) < $tradingDelayHours) {
                throw new MarketSuspendedException($meme->ticker);
            }

            // Calculate actual cost with current supply
            $calculation = $this->calculateBuyCost($meme, $quantity);

            // Check slippage if expectedTotal was provided
            if ($expectedTotal !== null && abs($calculation['total'] - $expectedTotal) > self::SLIPPAGE_TOLERANCE_CFU) {
                throw SlippageExceededException::withTotal($expectedTotal, $calculation['total']);
            }

            // Check user balance
            if ($user->cfu_balance < $calculation['total']) {
                throw new InsufficientFundsException($calculation['total'], $user->cfu_balance);
            }

            // Deduct CFU from user
            $user->cfu_balance -= $calculation['total'];
            $user->save();

            // Mint new shares (increase supply)
            $meme->circulating_supply += $quantity;

            // Update cached price (eager update)
            $meme->current_price = $meme->base_price + ($meme->slope * $meme->circulating_supply);
            $meme->save();

            // Update or create portfolio entry
            $portfolio = Portfolio::firstOrNew([
                'user_id' => $user->id,
                'meme_id' => $meme->id,
            ]);

            // Calculate new average buy price
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

            // Record transaction
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

            // Record price history snapshot
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
     * Execute a sell order with full atomicity and concurrency control.
     *
     * @param  float|null  $expectedTotal  If provided, throws SlippageExceededException if price changed
     *
     * @throws InsufficientSharesException
     * @throws MarketSuspendedException
     * @throws SlippageExceededException
     */
    public function executeSell(User $user, Meme $meme, int $quantity, ?float $expectedTotal = null): Transaction
    {
        return DB::transaction(function () use ($user, $meme, $quantity, $expectedTotal) {
            // Lock the meme row
            $meme = Meme::where('id', $meme->id)->lockForUpdate()->first();

            // Lock the user row
            $user = User::where('id', $user->id)->lockForUpdate()->first();

            // Check market status
            if ($meme->status === 'suspended') {
                throw new MarketSuspendedException($meme->ticker);
            }

            // Find portfolio entry
            $portfolio = Portfolio::where('user_id', $user->id)
                ->where('meme_id', $meme->id)
                ->lockForUpdate()
                ->first();

            // Check if user has enough shares
            if (! $portfolio || $portfolio->quantity < $quantity) {
                $available = $portfolio ? $portfolio->quantity : 0;
                throw new InsufficientSharesException($quantity, $available, $meme->ticker);
            }

            // Calculate actual income with current supply
            $calculation = $this->calculateSellIncome($meme, $quantity);

            // Check slippage if expectedTotal was provided
            if ($expectedTotal !== null && abs($calculation['total'] - $expectedTotal) > self::SLIPPAGE_TOLERANCE_CFU) {
                throw SlippageExceededException::withTotal($expectedTotal, $calculation['total']);
            }

            // Credit CFU to user
            $user->cfu_balance += $calculation['total'];
            $user->save();

            // Burn shares (decrease supply)
            $meme->circulating_supply -= $quantity;

            // Update cached price (eager update)
            $meme->current_price = $meme->base_price + ($meme->slope * $meme->circulating_supply);
            $meme->save();

            // Update portfolio
            $portfolio->quantity -= $quantity;

            if ($portfolio->quantity == 0) {
                // Remove portfolio entry if no shares left
                $portfolio->delete();
            } else {
                $portfolio->save();
            }

            // Record transaction
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

            // Record price history snapshot
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
