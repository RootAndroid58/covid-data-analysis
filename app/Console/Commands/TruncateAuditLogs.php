<?php

namespace App\Console\Commands;

use App\Models\AuditLog;
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
    protected $description = 'Command description';

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
        AuditLog::where('created_at',"<",Carbon::now()->subDays(30))->delete();
    }
}
