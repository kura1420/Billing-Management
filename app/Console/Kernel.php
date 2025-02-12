<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();

        $this->
            _daily($schedule);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }

    protected function _daily(Schedule $schedule)
    {
        // $schedule->command('customer:invoice')->daily();        
        // $schedule->command('customer:suspend')->daily();
        // $schedule->command('customer:terminated')->daily();
        // $schedule->command('promo:disabled')->daily();
        $schedule->command('database:backup')->daily();
    }
}
