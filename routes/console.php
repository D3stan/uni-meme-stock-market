<?php

use App\Jobs\DistributeDividends;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

// Schedule dividend distribution to run daily at 2:00 AM
Schedule::job(new DistributeDividends)
    ->dailyAt('02:00')
    ->onOneServer()
    ->withoutOverlapping();
