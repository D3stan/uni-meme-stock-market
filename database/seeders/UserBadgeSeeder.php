<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Gamification\Badge;
use App\Models\Gamification\UserBadge;
use Illuminate\Database\Seeder;

class UserBadgeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traders = User::where('role', 'trader')->get();
        $badges = Badge::all();

        foreach ($traders as $trader) {
            // Each trader gets 1-4 random badges
            $earnedBadges = $badges->random(rand(1, 4));
            
            foreach ($earnedBadges as $badge) {
                UserBadge::create([
                    'user_id' => $trader->id,
                    'badge_id' => $badge->id,
                    'awarded_at' => now()->subDays(rand(1, 60)),
                ]);
            }
        }
    }
}
