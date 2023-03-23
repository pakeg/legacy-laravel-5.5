<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        \App\Console\Commands\getEnded::class,
        \App\Console\Commands\getInplay::class,
        \App\Console\Commands\getUpcoming::class,
        \App\Console\Commands\getEventView::class,
        \App\Console\Commands\getEventLineup::class,
        \App\Console\Commands\getEventTrend::class,
        \App\Console\Commands\getLeague::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    { 
        set_time_limit(0);
        $schedule->command('command:upcoming')->daily()->withoutOverlapping();       
        $schedule->command('command:league')->daily()->withoutOverlapping();
        $schedule->command('command:ended')->cron('*/1 * * * *')->withoutOverlapping();
        $schedule->command('command:inplay')->cron('*/1 * * * *')->withoutOverlapping();			
        $schedule->command('command:eventview')->cron('*/2 * * * *')->withoutOverlapping();
        $schedule->command('command:eventlineup')->cron('*/2 * * * *')->withoutOverlapping();
        $schedule->command('command:eventtrend')->cron('*/2 * * * *')->withoutOverlapping();
    }

    /**
     * Register the Closure based commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        require base_path('routes/console.php');
    }
}
