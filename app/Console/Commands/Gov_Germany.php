<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Helpers\ScraperHelper;

class Gov_Germany extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covid:gov-germany';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Government scraper for Germany';

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
        $this->info("starting Gov_Germany scraper\t\t\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        ScraperHelper::Gov_Germany();
        $this->info("completed Gov_Germany scraper\t\t\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());
        return 0;
    }
}
