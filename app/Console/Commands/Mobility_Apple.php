<?php

namespace App\Console\Commands;

use App\Http\Helpers\ScraperHelper;
use Illuminate\Console\Command;

class Mobility_Apple extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:apple';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts Apple Molility data to download';

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
        $this->info("starting Apple Mobility report \t" .round( microtime(true) - $start ,8)."s\t\t". memory_get_peak_usage()."\t".memory_get_usage() );
        ScraperHelper::apple_mobility();
        $this->info("completed Apple Mobility report\t".round( microtime(true) - $start ,8)."s\t". memory_get_peak_usage()."\t".memory_get_usage() );
        $this->info("starting Apple Mobility trends \t".round( microtime(true) - $start ,8)."s\t". memory_get_peak_usage()."\t".memory_get_usage() );
        ScraperHelper::apple_mobility_trends();
        $this->info("completed Apple Mobility trends\t".round( microtime(true) - $start ,8). "s\t". memory_get_peak_usage()."\t".memory_get_usage() );
        return 0;
    }
}
