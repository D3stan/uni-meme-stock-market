<?php

namespace Database\Seeders;

use App\Models\Market\Meme;
use App\Models\Financial\PriceHistory;
use Illuminate\Database\Seeder;

class PriceHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $memes = Meme::whereIn('id', range(1, 17))->get();

        foreach ($memes as $meme) {
            // Create IPO record
            PriceHistory::create([
                'meme_id' => $meme->id,
                'price' => $meme->base_price,
                'circulating_supply_snapshot' => 0,
                'trigger_type' => 'ipo',
                'recorded_at' => $meme->approved_at,
                'volume_24h' => 0,
                'pct_change_24h' => 0,
            ]);

            // Create historical records for last 7 days
            for ($day = 6; $day >= 1; $day--) {
                // More granular data for recent days (hourly for last 2 days, every 4 hours for older)
                $interval = $day <= 2 ? 1 : 4;
                
                for ($hour = 0; $hour < 24; $hour += $interval) {
                    $recordedAt = now()->subDays($day)->startOfDay()->addHours($hour);
                    
                    // Calculate price variation (random walk between base_price and current_price)
                    $progress = (7 - $day + ($hour / 24)) / 7; // 0 to 1
                    $priceDiff = $meme->current_price - $meme->base_price;
                    $historicalPrice = $meme->base_price + ($priceDiff * $progress) + (rand(-20, 20) / 100);
                    
                    // Ensure price is positive
                    $historicalPrice = max(0.10, $historicalPrice);
                    
                    // Calculate supply growth
                    $supplyGrowth = floor($meme->circulating_supply * $progress * rand(70, 100) / 100);

                    PriceHistory::create([
                        'meme_id' => $meme->id,
                        'price' => round($historicalPrice, 2),
                        'circulating_supply_snapshot' => $supplyGrowth,
                        'trigger_type' => rand(0, 1) ? 'buy' : 'sell',
                        'recorded_at' => $recordedAt,
                        'volume_24h' => rand(50, 500),
                        'pct_change_24h' => rand(-15, 25) / 10,
                    ]);
                }
            }

            // Create today's record at various times (hourly, but only for past hours)
            $currentHour = now()->hour;
            for ($hour = 0; $hour <= $currentHour; $hour++) {
                $recordedAt = now()->startOfDay()->addHours($hour);
                
                // Skip if this would be in the future
                if ($recordedAt->isFuture()) {
                    continue;
                }
                
                // Price should be close to current_price
                $variance = rand(-50, 50) / 1000; // ±5%
                $todayPrice = $meme->current_price * (1 + $variance);
                $todayPrice = max(0.10, $todayPrice);

                PriceHistory::create([
                    'meme_id' => $meme->id,
                    'price' => round($todayPrice, 2),
                    'circulating_supply_snapshot' => $meme->circulating_supply,
                    'trigger_type' => rand(0, 1) ? 'buy' : 'sell',
                    'recorded_at' => $recordedAt,
                    'volume_24h' => rand(100, 800),
                    'pct_change_24h' => rand(-10, 20) / 10,
                ]);
            }
        }
    }
}
