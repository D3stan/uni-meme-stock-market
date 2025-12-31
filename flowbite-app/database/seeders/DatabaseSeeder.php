<?php

namespace Database\Seeders;

use App\Models\GlobalSetting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Seed in ordine corretto per rispettare le foreign keys
        $this->call([
            CategorySeeder::class,    // Prima le categorie
            BadgeSeeder::class,       // Badge disponibili
            UserSeeder::class,        // Utenti (admin + traders)
            MemeSeeder::class,        // Meme creati dagli utenti
            PortfolioSeeder::class,   // Posizioni nei portafogli
            NotificationSeeder::class, // Notifiche di esempio
        ]);

        // Seed global settings
        $this->seedGlobalSettings();
    }

    /**
     * Seed impostazioni globali del sistema
     */
    private function seedGlobalSettings(): void
    {
        $settings = [
            // Trading fees
            'listing_fee' => '20',           // CFU per proporre un meme
            'tax_rate' => '0.02',            // 2% su ogni transazione
            
            // Bonus e incentivi
            'registration_bonus' => '100',   // CFU iniziali per nuovi utenti
            'dividend_rate' => '0.01',       // 1% dividendi giornalieri
            
            // Limiti di sistema
            'min_trade_quantity' => '1',     // Minimo 1 azione per trade
            'max_trade_quantity' => '1000',  // Massimo 1000 azioni per trade
            
            // Parametri default meme
            'default_base_price' => '1.00',  // Prezzo base default per nuovi meme
            'default_slope' => '0.10',       // Slope default per nuovi meme
            
            // Timing
            'trading_delay_hours' => '24',   // Ore prima che il trading sia abilitato dopo approvazione
            'price_update_interval' => '10', // Secondi tra aggiornamenti prezzi lato client
            
            // Slippage protection
            'slippage_threshold' => '0.02',  // 2% - soglia per avviso slippage
        ];

        foreach ($settings as $key => $value) {
            GlobalSetting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }
    }
}
