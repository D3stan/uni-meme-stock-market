<?php

use App\Exceptions\Market\MarketSuspendedException;
use App\Models\Admin\GlobalSetting;
use App\Models\Market\Meme;
use App\Services\TradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

/**
 * Initialize trading service and configure global settings before each test.
 * Sets 2% transaction tax rate and disables trading delay for calculation tests.
 */
beforeEach(function () {
    $this->service = new TradingService;

    GlobalSetting::create(['key' => 'tax_rate', 'value' => '0.02']);
    GlobalSetting::create(['key' => 'trading_delay_hours', 'value' => '0']);
});

describe('calculateBuyCost', function () {
    it('calculates cost correctly with zero supply', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $result = $this->service->calculateBuyCost($meme, 10);

        expect($result['subtotal'])->toBe(15.00);
        expect($result['fee'])->toBe(0.30);
        expect($result['total'])->toBe(15.30);
        expect($result['current_supply'])->toBe(0);
    });

    it('calculates cost correctly with existing supply', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 50,
        ]);

        $result = $this->service->calculateBuyCost($meme, 10);

        expect($result['subtotal'])->toBe(65.00);
        expect($result['fee'])->toBe(1.30);
        expect($result['total'])->toBe(66.30);
    });

    it('rejects zero quantity', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->calculateBuyCost($meme, 0);
    })->throws(InvalidArgumentException::class, 'Quantity must be greater than zero.');

    it('rejects negative quantity', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $this->service->calculateBuyCost($meme, -5);
    })->throws(InvalidArgumentException::class, 'Quantity must be greater than zero.');

    it('handles large quantities without overflow', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.01,
            'circulating_supply' => 1000,
        ]);

        $result = $this->service->calculateBuyCost($meme, 1000);

        expect($result['total'])->toBeGreaterThan(0);
        expect($result['subtotal'])->toBeGreaterThan(0);
    });
});

describe('calculateSellIncome', function () {
    it('calculates income correctly with existing supply', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 60,
        ]);

        $result = $this->service->calculateSellIncome($meme, 10);

        expect($result['subtotal'])->toBe(65.00);
        expect($result['fee'])->toBe(1.30);
        expect($result['total'])->toBe(63.70);
    });

    it('calculates income correctly selling all supply', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 10,
        ]);

        $result = $this->service->calculateSellIncome($meme, 10);

        expect($result['subtotal'])->toBe(15.00);
        expect($result['fee'])->toBe(0.30);
        expect($result['total'])->toBe(14.70);
    });

    it('rejects zero quantity', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 50,
        ]);

        $this->service->calculateSellIncome($meme, 0);
    })->throws(InvalidArgumentException::class, 'Quantity must be greater than zero.');

    it('rejects negative quantity', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 50,
        ]);

        $this->service->calculateSellIncome($meme, -10);
    })->throws(InvalidArgumentException::class, 'Quantity must be greater than zero.');
});

describe('buy and sell symmetry', function () {
    it('ensures buying then selling returns nearly same amount minus fees', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 50,
        ]);

        $buyResult = $this->service->calculateBuyCost($meme, 10);

        $meme->circulating_supply = 60;

        $sellResult = $this->service->calculateSellIncome($meme, 10);

        expect($buyResult['subtotal'])->toBe($sellResult['subtotal']);

        expect($buyResult['total'])->toBeGreaterThan($sellResult['total']);

        $expectedLoss = $buyResult['fee'] + $sellResult['fee'];
        $actualLoss = $buyResult['total'] - $sellResult['total'];
        expect(round($actualLoss, 2))->toBe(round($expectedLoss, 2));
    });
});

describe('previewOrder', function () {
    it('returns buy calculation for buy type', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $result = $this->service->previewOrder($meme, 'buy', 10);

        expect($result['total'])->toBe(15.30);
    });

    it('returns sell calculation for sell type', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 60,
        ]);

        $result = $this->service->previewOrder($meme, 'sell', 10);

        expect($result['total'])->toBe(63.70);
    });

    it('throws exception for suspended meme', function () {
        $meme = Meme::factory()->suspended()->create();

        $this->service->previewOrder($meme, 'buy', 10);
    })->throws(MarketSuspendedException::class);

    it('throws exception for meme within trading delay window', function () {
        GlobalSetting::where('key', 'trading_delay_hours')->update(['value' => '8']);

        $meme = Meme::factory()->create([
            'status' => 'approved',
            'approved_at' => now()->subHours(5), // Less than 8 hours
        ]);

        $this->service->previewOrder($meme, 'buy', 10);
    })->throws(MarketSuspendedException::class);

    it('allows trading after delay window has passed', function () {
        $meme = Meme::factory()->approved()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $result = $this->service->previewOrder($meme, 'buy', 10);

        expect($result)->toHaveKey('total');
    });
});

describe('fee calculation', function () {
    it('uses tax rate from global settings', function () {
        GlobalSetting::where('key', 'tax_rate')->update(['value' => '0.05']);

        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $result = $this->service->calculateBuyCost($meme, 10);

        expect($result['fee'])->toBe(0.75);
        expect($result['total'])->toBe(15.75);
    });

    it('defaults to 2% when tax rate not set', function () {
        GlobalSetting::where('key', 'tax_rate')->delete();

        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $result = $this->service->calculateBuyCost($meme, 10);

        expect($result['fee'])->toBe(0.30);
    });
});

describe('price estimation', function () {
    it('provides reasonable average price estimate for buy', function () {
        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 50,
        ]);

        $result = $this->service->calculateBuyCost($meme, 10);

        expect($result['estimated_price'])->toBeGreaterThan(6.0);
        expect($result['estimated_price'])->toBeLessThan(7.0);
    });
});
