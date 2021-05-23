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
        //
        Commands\IN_MH_Nagpur_Scrapper_JOB::class,
        Commands\scrapeStartCommand::class,
        Commands\TruncateAuditLogs::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('schedule-monitor:sync')->everyTenMinutes();
        $schedule->command('schedule-monitor:clean')->daily();
        $schedule->command('scraper:start')->everyTenMinutes()->withoutOverlapping()->appendOutputTo(storage_path('logs/schedule.log'));
        $schedule->command('scraper:covid')->everyTenMinutes()->withoutOverlapping()->appendOutputTo(storage_path('logs/schedule.log'));
        $schedule->command('truncate:audit')->daily()->appendOutputTo(storage_path('logs/schedule.log'));
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
}
