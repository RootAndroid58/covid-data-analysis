<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Spatie\ScheduleMonitor\Models\MonitoredScheduledTaskLogItem;

class cron_clean extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cron:clean';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up old records from the schedule monitor log.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cutOffInDays = config('schedule-monitor.delete_log_items_older_than_days');

        $this->comment('Deleting all log items older than ' . $cutOffInDays .' '. Str::plural('day', $cutOffInDays) . '...');

        $cutOff = now()->subDays(config('schedule-monitor.delete_log_items_older_than_days'));

        $numberOfRecordsDeleted = MonitoredScheduledTaskLogItem::query()
            ->where('created_at', '<', $cutOff->toDateTimeString())
            ->delete();

        $this->info('Deleted ' . $numberOfRecordsDeleted . ' '. Str::plural('log item', $numberOfRecordsDeleted) . '!');
    }
}
