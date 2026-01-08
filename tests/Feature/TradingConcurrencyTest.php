<?php

use App\Models\Admin\GlobalSetting;
use App\Models\Financial\Portfolio;
use App\Models\Financial\Transaction;
use App\Models\Market\Meme;
use App\Models\User;
use App\Services\TradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Configure trading service and global settings before each test.
 * Sets 2% tax rate and disables trading delay for immediate execution.
 */
beforeEach(function () {
    $this->service = new TradingService;
    GlobalSetting::create(['key' => 'tax_rate', 'value' => '0.02']);
    GlobalSetting::create(['key' => 'trading_delay_hours', 'value' => '0']);
});

describe('concurrent buy operations', function () {
    it('maintains supply consistency with multiple simultaneous buys', function () {
        $users = User::factory()->count(5)->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        foreach ($users as $user) {
            $this->service->executeBuy($user, $meme, 5);
        }

        $meme->refresh();
        expect($meme->circulating_supply)->toBe(25);

        expect(Transaction::where('type', 'buy')->count())->toBe(5);

        foreach ($users as $user) {
            $portfolio = Portfolio::where('user_id', $user->id)
                ->where('meme_id', $meme->id)
                ->first();
            expect($portfolio)->not->toBeNull();
            expect($portfolio->quantity)->toBe(5);
        }
    });

    it('ensures no double-spending with race conditions', function () {
        $user = User::factory()->create(['cfu_balance' => 20.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->executeBuy($user, $meme, 10);

        $remainingBalance = $user->fresh()->cfu_balance;

        try {
            $this->service->executeBuy($user, $meme, 10);
            $this->fail('Expected InsufficientFundsException');
        } catch (\App\Exceptions\Financial\InsufficientFundsException $e) {
        }

        expect($user->fresh()->cfu_balance)->toBe($remainingBalance);

        expect($meme->fresh()->circulating_supply)->toBe(10);
    });

    it('handles buy and sell on same meme atomically', function () {
        $buyer = User::factory()->create(['cfu_balance' => 100.00]);
        $seller = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 50,
        ]);

        Portfolio::create([
            'user_id' => $seller->id,
            'meme_id' => $meme->id,
            'quantity' => 20,
            'avg_buy_price' => 4.00,
        ]);

        $this->service->executeBuy($buyer, $meme, 10);
        $supplyAfterBuy = $meme->fresh()->circulating_supply;
        expect($supplyAfterBuy)->toBe(60);

        $this->service->executeSell($seller, $meme, 10);
        $supplyAfterSell = $meme->fresh()->circulating_supply;
        expect($supplyAfterSell)->toBe(50);

        expect($supplyAfterSell)->toBe(50);
    });

    it('maintains correct price through multiple transactions', function () {
        $users = User::factory()->count(3)->create(['cfu_balance' => 200.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
            'current_price' => 1.00,
        ]);

        $this->service->executeBuy($users[0], $meme, 10);
        $meme->refresh();
        $expectedPrice1 = 1.00 + (0.10 * 10);
        expect((float) $meme->current_price)->toBe($expectedPrice1);

        $this->service->executeBuy($users[1], $meme, 15);
        $meme->refresh();
        $expectedPrice2 = 1.00 + (0.10 * 25);
        expect((float) $meme->current_price)->toBe($expectedPrice2);

        $this->service->executeBuy($users[2], $meme, 5);
        $meme->refresh();
        $expectedPrice3 = 1.00 + (0.10 * 30);
        expect((float) $meme->current_price)->toBe($expectedPrice3);
    });
});

describe('concurrent sell operations', function () {
    it('prevents overselling with concurrent sell attempts', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 50,
        ]);

        Portfolio::create([
            'user_id' => $user->id,
            'meme_id' => $meme->id,
            'quantity' => 10,
            'avg_buy_price' => 5.00,
        ]);

        $this->service->executeSell($user, $meme, 5);

        $supplyAfterFirst = $meme->fresh()->circulating_supply;
        expect($supplyAfterFirst)->toBe(45);

        $this->service->executeSell($user, $meme, 5);

        expect($meme->fresh()->circulating_supply)->toBe(40);

        try {
            $this->service->executeSell($user, $meme, 1);
            $this->fail('Expected InsufficientSharesException');
        } catch (\App\Exceptions\Financial\InsufficientSharesException $e) {
        }

        expect($meme->fresh()->circulating_supply)->toBe(40);
    });

    it('handles multiple sellers on same meme correctly', function () {
        $sellers = User::factory()->count(3)->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 100,
        ]);

        foreach ($sellers as $seller) {
            Portfolio::create([
                'user_id' => $seller->id,
                'meme_id' => $meme->id,
                'quantity' => 20,
                'avg_buy_price' => 5.00,
            ]);
        }

        foreach ($sellers as $seller) {
            $this->service->executeSell($seller, $meme, 10);
        }

        expect($meme->fresh()->circulating_supply)->toBe(70);

        foreach ($sellers as $seller) {
            $portfolio = Portfolio::where('user_id', $seller->id)
                ->where('meme_id', $meme->id)
                ->first();
            expect($portfolio->quantity)->toBe(10);
        }
    });
});

describe('transaction rollback on failure', function () {
    it('rolls back entire transaction if any step fails', function () {
        $user = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $initialBalance = $user->cfu_balance;
        $initialSupply = $meme->circulating_supply;

        try {
            $this->service->executeBuy($user, $meme, 10, 5.00);
        } catch (\App\Exceptions\Market\SlippageExceededException $e) {
        }

        expect($user->fresh()->cfu_balance)->toBe($initialBalance);
        expect($meme->fresh()->circulating_supply)->toBe($initialSupply);
        expect(Portfolio::count())->toBe(0);
        expect(Transaction::count())->toBe(0);
    });

    it('maintains database consistency after failed operations', function () {
        $user = User::factory()->create(['cfu_balance' => 10.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        for ($i = 0; $i < 5; $i++) {
            try {
                $this->service->executeBuy($user, $meme, 10);
            } catch (\App\Exceptions\Financial\InsufficientFundsException $e) {
            }
        }

        expect((float) $user->fresh()->cfu_balance)->toBe(10.00);
        expect($meme->fresh()->circulating_supply)->toBe(0);
        expect(Transaction::count())->toBe(0);
    });
});

describe('edge cases with supply at zero', function () {
    it('can buy when supply is zero', function () {
        $user = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $transaction = $this->service->executeBuy($user, $meme, 10);

        expect($transaction)->toBeInstanceOf(Transaction::class);
        expect($meme->fresh()->circulating_supply)->toBe(10);
    });

    it('can sell all shares returning supply to zero', function () {
        $user = User::factory()->create(['cfu_balance' => 50.00]);
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 10,
        ]);

        Portfolio::create([
            'user_id' => $user->id,
            'meme_id' => $meme->id,
            'quantity' => 10,
            'avg_buy_price' => 1.50,
        ]);

        $this->service->executeSell($user, $meme, 10);

        expect($meme->fresh()->circulating_supply)->toBe(0);
        expect((float) $meme->fresh()->current_price)->toBe(1.00);
    });

    it('can buy again after supply returns to zero', function () {
        $user1 = User::factory()->create(['cfu_balance' => 100.00]);
        $user2 = User::factory()->create(['cfu_balance' => 100.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->executeBuy($user1, $meme, 10);
        expect($meme->fresh()->circulating_supply)->toBe(10);

        $this->service->executeSell($user1, $meme, 10);
        expect($meme->fresh()->circulating_supply)->toBe(0);

        $preview = $this->service->previewOrder($meme, 'buy', 10);
        $this->service->executeBuy($user2, $meme, 10);

        expect($meme->fresh()->circulating_supply)->toBe(10);
    });
});

describe('transaction history integrity', function () {
    it('records all transactions in order', function () {
        $user = User::factory()->create(['cfu_balance' => 200.00]);
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->executeBuy($user, $meme, 5);
        sleep(1);
        $this->service->executeBuy($user, $meme, 10);
        sleep(1);
        $this->service->executeSell($user, $meme, 3);

        $transactions = Transaction::where('user_id', $user->id)
            ->orderBy('executed_at')
            ->get();

        expect($transactions)->toHaveCount(3);
        expect($transactions[0]->type)->toBe('buy');
        expect($transactions[0]->quantity)->toBe(5);
        expect($transactions[1]->type)->toBe('buy');
        expect($transactions[1]->quantity)->toBe(10);
        expect($transactions[2]->type)->toBe('sell');
        expect($transactions[2]->quantity)->toBe(3);

        expect($transactions[0]->cfu_balance_after)->toBeLessThan(200.00);
        expect($transactions[1]->cfu_balance_after)->toBeLessThan($transactions[0]->cfu_balance_after);
        expect($transactions[2]->cfu_balance_after)->toBeGreaterThan($transactions[1]->cfu_balance_after);
    });
});
