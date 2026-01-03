<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Utility\Notification;
use App\Models\Market\Meme;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traders = User::where('role', 'trader')->get();
        $memes = Meme::where('status', 'approved')->get();

        foreach ($traders as $trader) {
            // Welcome notification
            Notification::create([
                'user_id' => $trader->id,
                'title' => 'Benvenuto su AlmaStreet! 🎉',
                'message' => 'Hai ricevuto 100 CFU come bonus di benvenuto. Inizia a fare trading!',
                'is_read' => rand(0, 1) === 1,
                'created_at' => $trader->created_at,
            ]);

            // Random notifications (2-5 per user)
            $numNotifications = rand(2, 5);
            
            for ($i = 0; $i < $numNotifications; $i++) {
                $type = rand(1, 4);
                
                switch ($type) {
                    case 1: // Dividend received
                        $meme = $memes->random();
                        $amount = rand(50, 500) / 100;
                        Notification::create([
                            'user_id' => $trader->id,
                            'title' => '💰 Dividendo Ricevuto',
                            'message' => sprintf('Hai ricevuto %.2f CFU come dividendo da $%s', $amount, $meme->ticker),
                            'is_read' => rand(0, 1) === 1,
                            'created_at' => now()->subDays(rand(1, 15)),
                        ]);
                        break;
                        
                    case 2: // Transaction completed
                        $meme = $memes->random();
                        Notification::create([
                            'user_id' => $trader->id,
                            'title' => '✅ Transazione Completata',
                            'message' => sprintf('Hai acquistato %d azioni di $%s', rand(5, 50), $meme->ticker),
                            'is_read' => rand(0, 1) === 1,
                            'created_at' => now()->subDays(rand(1, 20)),
                        ]);
                        break;
                        
                    case 3: // Price alert
                        $meme = $memes->random();
                        $change = rand(10, 50);
                        Notification::create([
                            'user_id' => $trader->id,
                            'title' => '📈 Alerta Prezzo',
                            'message' => sprintf('$%s è salito del %d%% nelle ultime 24h!', $meme->ticker, $change),
                            'is_read' => rand(0, 1) === 1,
                            'created_at' => now()->subDays(rand(1, 10)),
                        ]);
                        break;
                        
                    case 4: // New meme listing
                        $meme = $memes->where('approved_at', '>=', now()->subDays(7))->random();
                        Notification::create([
                            'user_id' => $trader->id,
                            'title' => '🆕 Nuovo Meme Quotato',
                            'message' => sprintf('$%s è stato appena quotato sul mercato!', $meme->ticker),
                            'is_read' => rand(0, 1) === 1,
                            'created_at' => $meme->approved_at,
                        ]);
                        break;
                }
            }
        }

        // Global notification for all users
        $allUsers = User::all();
        foreach ($allUsers as $user) {
            Notification::create([
                'user_id' => $user->id,
                'title' => '📢 Comunicazione del Rettorato',
                'message' => 'Il mercato sarà aperto durante le vacanze natalizie. Buon trading!',
                'is_read' => rand(0, 1) === 1,
                'created_at' => now()->subDays(5),
            ]);
        }
    }
}
