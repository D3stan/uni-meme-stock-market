<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Market\Meme;
use App\Models\Market\Watchlist;
use Illuminate\Database\Seeder;

class WatchlistSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traders = User::where('role', 'trader')->get();
        $memes = Meme::where('status', 'approved')->get();

        foreach ($traders as $trader) {
            // Each trader watches 3-6 memes
            $watchedMemes = $memes->random(rand(3, 6));
            
            foreach ($watchedMemes as $meme) {
                Watchlist::create([
                    'user_id' => $trader->id,
                    'meme_id' => $meme->id,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
