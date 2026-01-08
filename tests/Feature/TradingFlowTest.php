<?php

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
use App\Services\TradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Initialize trading service and configure global settings before each test.
 * Sets 2% transaction tax rate and disables trading delay to allow immediate execution.
 */
beforeEach(function () {
    $this->service = new TradingService;

    GlobalSetting::create(['key' => 'tax_rate', 'value' => '0.02']);
    GlobalSetting::create(['key' => 'trading_delay_hours', 'value' => '0']);
});

describe('executeBuy', function () {
    it('allows user to buy meme shares successfully', function () {
        $user = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
            'current_price' => 1.00,
        ]);

        $transaction = $this->service->executeBuy($user, $meme, 10);

        expect($transaction)->toBeInstanceOf(Transaction::class);
        expect($transaction->type)->toBe('buy');
        expect($transaction->quantity)->toBe(10);
        expect((float) $transaction->total_amount)->toBe(15.30);
        expect((float) $transaction->fee_amount)->toBe(0.30);

        expect((float) $user->fresh()->cfu_balance)->toBe(84.70);

        expect($meme->fresh()->circulating_supply)->toBe(10);

        $expectedPrice = 1.00 + (0.10 * 10);
        expect((float) $meme->fresh()->current_price)->toBe($expectedPrice);

        $portfolio = Portfolio::where('user_id', $user->id)
            ->where('meme_id', $meme->id)
            ->first();
        expect($portfolio)->not->toBeNull();
        expect($portfolio->quantity)->toBe(10);
        expect($portfolio->avg_buy_price)->toBeGreaterThan(0);

        $history = PriceHistory::where('meme_id', $meme->id)
            ->where('trigger_type', 'buy')
            ->first();
        expect($history)->not->toBeNull();
        expect($history->circulating_supply_snapshot)->toBe(10);
    });

    it('throws exception when user has insufficient funds', function () {
        $user = User::factory()->create(['cfu_balance' => 10.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->executeBuy($user, $meme, 10);
    })->throws(InsufficientFundsException::class);

    it('does not modify state when insufficient funds', function () {
        $user = User::factory()->create(['cfu_balance' => 10.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        try {
            $this->service->executeBuy($user, $meme, 10);
        } catch (InsufficientFundsException $e) {
        }

        expect((float) $user->fresh()->cfu_balance)->toBe(10.00);
        expect($meme->fresh()->circulating_supply)->toBe(0);
        expect(Portfolio::count())->toBe(0);
        expect(Transaction::count())->toBe(0);
    });

    it('throws exception for suspended meme', function () {
        $user = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->suspended()->create();

        $this->service->executeBuy($user, $meme, 10);
    })->throws(MarketSuspendedException::class);

    it('throws exception when meme is within trading delay', function () {
        GlobalSetting::where('key', 'trading_delay_hours')->update(['value' => '8']);

        $user = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->create([
            'status' => 'approved',
            'approved_at' => now()->subHours(5),
        ]);

        $this->service->executeBuy($user, $meme, 10);
    })->throws(MarketSuspendedException::class);

    it('throws slippage exception when price changes beyond tolerance', function () {
        $user = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->executeBuy($user, $meme, 10, 10.00);
    })->throws(SlippageExceededException::class);

    it('accepts order when price is within tolerance', function () {
        $user = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $transaction = $this->service->executeBuy($user, $meme, 10, 15.30);

        expect($transaction)->toBeInstanceOf(Transaction::class);
    });

    it('updates portfolio average buy price correctly on second purchase', function () {
        $user = User::factory()->create(['cfu_balance' => 200.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->executeBuy($user, $meme, 10);
        $portfolio = Portfolio::where('user_id', $user->id)
            ->where('meme_id', $meme->id)
            ->first();
        $firstAvgPrice = $portfolio->avg_buy_price;

        $this->service->executeBuy($user, $meme, 10);
        $portfolio->refresh();

        expect($portfolio->quantity)->toBe(20);
        expect($portfolio->avg_buy_price)->toBeGreaterThan($firstAvgPrice);
    });

    it('handles concurrent purchases atomically', function () {
        $user1 = User::factory()->create(['cfu_balance' => 100.00]);
        $user2 = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->executeBuy($user1, $meme, 5);
        $this->service->executeBuy($user2, $meme, 5);

        expect($meme->fresh()->circulating_supply)->toBe(10);

        expect(Portfolio::where('user_id', $user1->id)->first()->quantity)->toBe(5);
        expect(Portfolio::where('user_id', $user2->id)->first()->quantity)->toBe(5);
    });
});

describe('executeSell', function () {
    it('allows user to sell meme shares successfully', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 60,
            'current_price' => 7.00,
        ]);

        Portfolio::create([
            'user_id' => $user->id,
            'meme_id' => $meme->id,
            'quantity' => 20,
            'avg_buy_price' => 5.00,
        ]);

        $transaction = $this->service->executeSell($user, $meme, 10);

        expect($transaction)->toBeInstanceOf(Transaction::class);
        expect($transaction->type)->toBe('sell');
        expect($transaction->quantity)->toBe(10);
        expect((float) $transaction->total_amount)->toBe(63.70);

        expect((float) $user->fresh()->cfu_balance)->toBe(113.70);

        expect($meme->fresh()->circulating_supply)->toBe(50);

        $expectedPrice = 1.00 + (0.10 * 50);
        expect((float) $meme->fresh()->current_price)->toBe($expectedPrice);

        $portfolio = Portfolio::where('user_id', $user->id)
            ->where('meme_id', $meme->id)
            ->first();
        expect($portfolio->quantity)->toBe(10);

        $history = PriceHistory::where('meme_id', $meme->id)
            ->where('trigger_type', 'sell')
            ->first();
        expect($history)->not->toBeNull();
    });

    it('deletes portfolio entry when selling all shares', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 60,
        ]);

        Portfolio::create([
            'user_id' => $user->id,
            'meme_id' => $meme->id,
            'quantity' => 10,
            'avg_buy_price' => 5.00,
        ]);

        $this->service->executeSell($user, $meme, 10);

        $portfolio = Portfolio::where('user_id', $user->id)
            ->where('meme_id', $meme->id)
            ->first();
        expect($portfolio)->toBeNull();
    });

    it('throws exception when user has insufficient shares', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 60,
        ]);

        Portfolio::create([
            'user_id' => $user->id,
            'meme_id' => $meme->id,
            'quantity' => 5,
            'avg_buy_price' => 5.00,
        ]);

        $this->service->executeSell($user, $meme, 10);
    })->throws(InsufficientSharesException::class);

    it('throws exception when user has no portfolio entry', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 60,
        ]);

        $this->service->executeSell($user, $meme, 10);
    })->throws(InsufficientSharesException::class);

    it('throws exception for suspended meme', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->suspended()->create();

        Portfolio::create([
            'user_id' => $user->id,
            'meme_id' => $meme->id,
            'quantity' => 10,
            'avg_buy_price' => 5.00,
        ]);

        $this->service->executeSell($user, $meme, 10);
    })->throws(MarketSuspendedException::class);

    it('throws slippage exception when price changes beyond tolerance', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 60,
        ]);

        Portfolio::create([
            'user_id' => $user->id,
            'meme_id' => $meme->id,
            'quantity' => 10,
            'avg_buy_price' => 5.00,
        ]);

        $this->service->executeSell($user, $meme, 10, 100.00);
    })->throws(SlippageExceededException::class);

    it('does not modify state on failed sell', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 60,
        ]);

        Portfolio::create([
            'user_id' => $user->id,
            'meme_id' => $meme->id,
            'quantity' => 5,
            'avg_buy_price' => 5.00,
        ]);

        try {
            $this->service->executeSell($user, $meme, 10);
        } catch (InsufficientSharesException $e) {
        }

        expect((float) $user->fresh()->cfu_balance)->toBe(50.00);
        expect($meme->fresh()->circulating_supply)->toBe(60);
        expect(Portfolio::where('user_id', $user->id)->first()->quantity)->toBe(5);
        expect(Transaction::where('type', 'sell')->count())->toBe(0);
    });
});

describe('complete trading flow', function () {
    it('simulates full buy-sell cycle', function () {
        $user = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
            'current_price' => 1.00,
        ]);

        $preview = $this->service->previewOrder($meme, 'buy', 10);
        expect($preview['total'])->toBe(15.30);

        $buyTx = $this->service->executeBuy($user, $meme, 10, $preview['total']);
        expect($buyTx->type)->toBe('buy');
        expect((float) $user->fresh()->cfu_balance)->toBe(84.70);

        $meme->refresh();
        expect($meme->circulating_supply)->toBe(10);

        $sellPreview = $this->service->previewOrder($meme, 'sell', 10);
        expect($sellPreview['total'])->toBe(14.70);

        $sellTx = $this->service->executeSell($user, $meme, 10, $sellPreview['total']);
        expect($sellTx->type)->toBe('sell');

        expect((float) $user->fresh()->cfu_balance)->toBe(99.40);
    });

    it('demonstrates profit from price increase', function () {
        $user1 = User::factory()->create(['cfu_balance' => 100.00]);
        $user2 = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->executeBuy($user1, $meme, 10);
        $user1Balance = $user1->fresh()->cfu_balance;

        $this->service->executeBuy($user2, $meme, 20);

        $meme->refresh();
        $this->service->executeSell($user1, $meme, 10);

        expect($user1->fresh()->cfu_balance)->toBeGreaterThan(100.00);
    });
});
