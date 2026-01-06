<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            // Core settings and categories
            GlobalSettingSeeder::class,
            CategorySeeder::class,
            BadgeSeeder::class,
            
            // Users
            UserSeeder::class,
            
            // Memes and market data
            MemeSeeder::class,
            PriceHistorySeeder::class,
            
            // User portfolios and transactions
            PortfolioSeeder::class,
            TransactionSeeder::class,
            
            // Dividends and rewards
            DividendHistorySeeder::class,
            UserBadgeSeeder::class,
            
            // User interactions
            WatchlistSeeder::class,
            NotificationSeeder::class,
            
            // Admin and communications
            MarketCommunicationSeeder::class,
            AdminActionSeeder::class,
        ]);

        $this->command->info('🎉 Database seeded successfully!');
        $this->command->info('');
        $this->command->info('👤 Admin Account:');
        $this->command->info('   Email: admin@studio.unibo.it');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('👥 Trader Accounts:');
        $this->command->info('   mario.rossi@studio.unibo.it / password');
        $this->command->info('   laura.bianchi@studio.unibo.it / password');
        $this->command->info('   giuseppe.verdi@studio.unibo.it / password');
}
