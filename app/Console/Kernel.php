<?php


namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
       protected function schedule(Schedule $schedule)
   {
       // Schedule a command to run daily at 10:00 AM
       $schedule->command('emails:send-reminders')
                ->dailyAt('10:00');

       // Schedule a closure to run every minute
       $schedule->call(function () {
           // Your code to handle note reminders or other tasks
       })->everyMinute();
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