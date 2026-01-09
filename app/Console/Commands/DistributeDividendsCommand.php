<?php

namespace App\Console\Commands;

use App\Jobs\DistributeDividends;
use Illuminate\Console\Command;

class DistributeDividendsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'dividends:distribute';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Manually distribute dividends to shareholders of memes with positive 24h trends';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $this->info('Starting dividend distribution...');

        DistributeDividends::dispatchSync();

        $this->info('✓ Dividend distribution completed successfully!');

        return Command::SUCCESS;
    }
}
