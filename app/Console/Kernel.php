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
        $schedule->command('cron:sync')->everyTenMinutes();
        $schedule->command('cron:clean')->daily();
        $schedule->command('scraper:start')->everyTenMinutes()->withoutOverlapping()->storeOutputInDb();
        $schedule->command('scraper:covid')->everyTenMinutes()->withoutOverlapping()->storeOutputInDb();
        $schedule->command('truncate:audit')->daily()->storeOutputInDb();
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
