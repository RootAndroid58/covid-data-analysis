<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
use App\Models\ScheduleEvents;
use Carbon\Carbon;
use Illuminate\Console\Command;

class TruncateAuditLogs extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'truncate:audit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes Audit logs > 30 days';

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
        $start = microtime(true);
        $AuditLog = AuditLog::where('created_at',"<",Carbon::now()->subDays(30))->count();
        $ScheduleEvents = ScheduleEvents::where('created_at',"<",Carbon::now()->subDays(30))->count();
        AuditLog::where('created_at',"<",Carbon::now()->subDays(30))->delete();
        ScheduleEvents::where('created_at',"<",Carbon::now()->subDays(30))->delete();
        $this->info("deleted $AuditLog audit log and $ScheduleEvents cron logs old logs in ". (microtime(true) - $start) . " Sec");
        return 0;
    }
}
