<?php

namespace App\Console\Commands;

use App\Http\Helpers\ScraperHelper;
use Illuminate\Console\Command;

class therapeutics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:raps';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get therapeutics trial data from RAPS (Regulatory Affairs Professional Society)';

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
        $data = ScraperHelper::TherapeuticsApi();
        return 0;
    }
}
