<?php

namespace App\Console\Commands;

use App\Http\Helpers\ScraperHelper;
use Illuminate\Console\Command;

class NYTScraper extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:nyt';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'New York Times covid Data Scraper';

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
        $this->info("Starting NYT default scraper\t". memory_get_peak_usage(). "\t" . memory_get_usage(). "\t".(microtime(true) - $start));
        ScraperHelper::NYT();
        $this->info("completed NYT default scraper\t". memory_get_peak_usage(). "\t" . memory_get_usage(). "\t".(microtime(true) - $start));
        $this->info("Starting NYT avarage scraper\t". memory_get_peak_usage(). "\t" . memory_get_usage(). "\t".(microtime(true) - $start));
        ScraperHelper::NYT_1();
        $this->info("completed NYT avarage scraper\t". memory_get_peak_usage(). "\t" . memory_get_usage(). "\t".(microtime(true) - $start));
        return 0;
    }
}
