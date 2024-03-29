<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;



use App\Models\ActiveMinedCoin;
use App\Models\Order;
use App\Models\UserCoinBalance;
use Illuminate\Support\Facades\DB;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */

    protected $commands = [
        Commands\DemoCron::class,
        Commands\ServerTimingMonitor::class,
    ];


    protected function schedule(Schedule $schedule)
    {
        $schedule->command('demo:cron')
            ->everyMinute();
        
        $schedule->command('timingMonitor:cron')
            ->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}