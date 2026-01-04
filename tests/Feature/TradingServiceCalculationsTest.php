<?php

use App\Exceptions\Market\MarketSuspendedException;
use App\Models\Admin\GlobalSetting;
use App\Models\Market\Meme;
use App\Services\TradingService;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->service = new TradingService;

    // Set up global settings
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

        // Formula: CostTotal = P_base * k + (M/2) * ((S+k)² - S²)
        // = 1.00 * 10 + (0.10/2) * ((0+10)² - 0²)
        // = 10 + 0.05 * 100 = 10 + 5 = 15
        // Fee = 15 * 0.02 = 0.30
        // Total = 15 + 0.30 = 15.30

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

        // Formula: CostTotal = 1.00 * 10 + (0.10/2) * ((50+10)² - 50²)
        // = 10 + 0.05 * (3600 - 2500) = 10 + 0.05 * 1100
        // = 10 + 55 = 65
        // Fee = 65 * 0.02 = 1.30
        // Total = 65 + 1.30 = 66.30

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

        // Formula: IncomeTotal = P_base * k + (M/2) * (S² - (S-k)²)
        // = 1.00 * 10 + (0.10/2) * (60² - (60-10)²)
        // = 10 + 0.05 * (3600 - 2500) = 10 + 0.05 * 1100
        // = 10 + 55 = 65
        // Fee = 65 * 0.02 = 1.30
        // Total = 65 - 1.30 = 63.70

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

        // Formula: IncomeTotal = 1.00 * 10 + (0.10/2) * (10² - 0²)
        // = 10 + 0.05 * 100 = 10 + 5 = 15
        // Fee = 15 * 0.02 = 0.30
        // Total = 15 - 0.30 = 14.70

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

        // Buy 10 shares
        $buyResult = $this->service->calculateBuyCost($meme, 10);

        // Simulate supply increase
        $meme->circulating_supply = 60;

        // Sell those 10 shares
        $sellResult = $this->service->calculateSellIncome($meme, 10);

        // The subtotals should be equal (before fees)
        expect($buyResult['subtotal'])->toBe($sellResult['subtotal']);

        // But total will differ due to fee direction (added vs subtracted)
        expect($buyResult['total'])->toBeGreaterThan($sellResult['total']);

        // Loss should be exactly 2 * fee (buy fee + sell fee)
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
        // Update tax rate
        GlobalSetting::where('key', 'tax_rate')->update(['value' => '0.05']);

        $meme = Meme::factory()->create([
            'base_price' => 1.00,
            'slope' => 0.10,
            'circulating_supply' => 0,
        ]);

        $result = $this->service->calculateBuyCost($meme, 10);

        // Subtotal = 15.00
        // Fee = 15.00 * 0.05 = 0.75
        // Total = 15.75
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

        // Should use default 0.02
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

        // Price at S=50: 1.00 + 0.10*50 = 6.00
        // Price at S=60: 1.00 + 0.10*60 = 7.00
        // Average should be around 6.50
        expect($result['estimated_price'])->toBeGreaterThan(6.0);
        expect($result['estimated_price'])->toBeLessThan(7.0);
    });
});
