<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class scrapeStartCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:start';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will start scraping all the websites that are added here';

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
        $this->info("starting scraper:start scraper\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        $this->call('scraper:INMHNagpur');
        $this->info("completed scraper:start scraper\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());
        return 0;
    }
}
