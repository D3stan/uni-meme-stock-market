<?php

namespace Database\Seeders;

use App\Models\Market\Meme;
use App\Models\Financial\DividendHistory;
use Illuminate\Database\Seeder;

class DividendHistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $memes = Meme::where('status', 'approved')
            ->where('circulating_supply', '>', 100)
            ->get();

        foreach ($memes as $meme) {
            // Create 1-3 dividend distributions for each meme
            $numDividends = rand(1, 3);
            
            for ($i = 0; $i < $numDividends; $i++) {
                $amountPerShare = rand(5, 50) / 100; // 0.05 to 0.50 CFU per share
                $totalDistributed = $amountPerShare * $meme->circulating_supply;

                DividendHistory::create([
                    'meme_id' => $meme->id,
                    'amount_per_share' => round($amountPerShare, 4),
                    'total_distributed' => round($totalDistributed, 2),
                    'distributed_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
