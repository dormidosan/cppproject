<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        $schedule->command('app:send-emails')->everyTwoMinutes();
        //$schedule->command('app:send-emails')->everyThirtyMinutes();
        //->dailyAt('13:00');	Run the task every day at 13:00
        //->weeklyOn(1, '8:00');	Run the task every week on Monday at 8:00
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
