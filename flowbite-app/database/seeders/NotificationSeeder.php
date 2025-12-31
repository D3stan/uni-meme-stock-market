<?php

namespace Database\Seeders;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traders = User::where('role', 'trader')->get();

        // Notifiche per Marco (id: 2)
        Notification::create([
            'user_id' => 2,
            'title' => 'Ordine Eseguito',
            'message' => 'Hai acquistato 3 azioni di $PRESSF per 13.50 CFU.',
            'is_read' => true,
        ]);

        Notification::create([
            'user_id' => 2,
            'title' => 'Dividendo Ricevuto',
            'message' => 'Hai ricevuto 2.50 CFU di dividendi da $STONK!',
            'is_read' => false,
        ]);

        // Notifiche per Giulia (id: 3)
        Notification::create([
            'user_id' => 3,
            'title' => 'Benvenuta su AlmaStreet!',
            'message' => 'Hai ricevuto 100 CFU come bonus di benvenuto. Inizia a fare trading!',
            'is_read' => true,
        ]);

        Notification::create([
            'user_id' => 3,
            'title' => 'Prezzo in salita 📈',
            'message' => '$STONK ha guadagnato il 15% nelle ultime 24 ore!',
            'is_read' => false,
        ]);

        // Notifiche per Alessandro (id: 4)
        Notification::create([
            'user_id' => 4,
            'title' => 'Badge Ottenuto! 🏆',
            'message' => 'Hai ottenuto il badge "Whale" - Possiedi più di 1000 CFU in un singolo titolo.',
            'is_read' => false,
        ]);

        // Notifiche per Sofia (id: 5)
        Notification::create([
            'user_id' => 5,
            'title' => 'Meme in attesa',
            'message' => 'Il tuo meme "$ULTIMA" è in attesa di approvazione dal Rettorato.',
            'is_read' => false,
        ]);

        // Notifica globale (visibile a tutti)
        Notification::create([
            'user_id' => null, // Globale
            'title' => 'Manutenzione Programmata',
            'message' => 'Il sistema sarà in manutenzione domani dalle 02:00 alle 04:00.',
            'is_read' => null,
        ]);
    }
}
