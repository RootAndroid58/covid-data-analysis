<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Traits\MonitorsSchedule;

class Kernel extends ConsoleKernel
{
    use MonitorsSchedule;
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
        $schedule->command('scraper:start')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('scraper:covid')->everyTenMinutes()->withoutOverlapping();
        $schedule->command('scraper:government')->everyTenMinutes()->withoutOverlapping();
        // $schedule->command('scraper:big-data')->hourly()->withoutOverlapping();
        $schedule->command('truncate:audit')->daily();

        $this->monitor($schedule);
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
