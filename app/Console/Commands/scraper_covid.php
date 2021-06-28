<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class scraper_covid extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:covid';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrape all covid api data and save it to cache';

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
        $this->info("starting covid:worldometers scraper\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        $this->call('covid:worldometers');
        $this->info("completed covid:worldometers scraper\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());
        $this->info("starting covid:historical scraper\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        $this->call('covid:historical');
        $this->info("completed covid:historical scraper\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());

        return 0;
    }
}
