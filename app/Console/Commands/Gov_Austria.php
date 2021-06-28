<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Helpers\ScraperHelper;

class Gov_Austria extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covid:gov-austria';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Government scraper for Austria.';

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
        $this->info("starting Gov_Austria scraper\t\t\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        ScraperHelper::Gov_Austria();
        $this->info("completed Gov_Austria scraper\t\t\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());
        return 0;
    }
}
