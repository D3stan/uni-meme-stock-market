<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Market\Meme;
use App\Models\Financial\Portfolio;
use Illuminate\Database\Seeder;

class PortfolioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traders = User::where('role', 'trader')->get();
        $memes = Meme::where('status', 'approved')->get();

        foreach ($traders as $trader) {
            // Each trader owns 2-5 different memes
            $ownedMemes = $memes->random(rand(2, 5));
            
            foreach ($ownedMemes as $meme) {
                $quantity = rand(5, 50);
                $avgBuyPrice = $meme->current_price * rand(80, 120) / 100; // Bought at ±20% of current price

                Portfolio::create([
                    'user_id' => $trader->id,
                    'meme_id' => $meme->id,
                    'quantity' => $quantity,
                    'avg_buy_price' => round($avgBuyPrice, 2),
                ]);
            }
        }
    }
}
