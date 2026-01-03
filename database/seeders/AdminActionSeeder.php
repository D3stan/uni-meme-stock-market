<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Market\Meme;
use App\Models\Admin\AdminAction;
use Illuminate\Database\Seeder;

class AdminActionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::where('role', 'admin')->first();
        $memes = Meme::where('status', 'approved')->get();

        // Approve all memes
        foreach ($memes as $meme) {
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'approve_meme',
                'target_id' => $meme->id,
                'target_type' => 'meme',
                'reason' => sprintf(
                    'IPO approved: base_price=%.2f, slope=%.4f',
                    $meme->base_price,
                    $meme->slope
                ),
                'created_at' => $meme->approved_at,
            ]);
        }

        // Update some global settings
        $settings = ['listing_fee', 'tax_rate', 'daily_bonus'];
        foreach ($settings as $setting) {
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'update_setting',
                'target_id' => null,
                'target_type' => 'setting',
                'reason' => "Initialized setting: {$setting}",
                'created_at' => now()->subDays(20),
            ]);
        }

        // Create market communications
        $numComms = 4;
        for ($i = 1; $i <= $numComms; $i++) {
            AdminAction::create([
                'admin_id' => $admin->id,
                'action_type' => 'create_communication',
                'target_id' => $i,
                'target_type' => 'communication',
                'reason' => 'Market communication posted',
                'created_at' => now()->subDays(rand(1, 15)),
            ]);
        }

        // Suspend and reactivate a meme (for testing)
        $testMeme = $memes->first();
        
        AdminAction::create([
            'admin_id' => $admin->id,
            'action_type' => 'suspend_meme',
            'target_id' => $testMeme->id,
            'target_type' => 'meme',
            'reason' => 'Suspected manipulation - temporary suspension for investigation',
            'created_at' => now()->subDays(10),
        ]);

        AdminAction::create([
            'admin_id' => $admin->id,
            'action_type' => 'reactivate_meme',
            'target_id' => $testMeme->id,
            'target_type' => 'meme',
            'reason' => 'Investigation completed - no irregularities found',
            'created_at' => now()->subDays(9),
        ]);
    }
}
