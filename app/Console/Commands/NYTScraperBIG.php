<?php

namespace App\Console\Commands;

use App\Http\Helpers\ScraperHelper;
use Illuminate\Console\Command;

class NYTScraperBIG extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:nyt-big';

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
        $this->info('Starting NYT default scraper'.memory_get_peak_usage(). "\t" . memory_get_usage());
        ScraperHelper::NYT_BIG();
        $this->info('completed NYT default scraper'.memory_get_peak_usage(). "\t" . memory_get_usage());
        return 0;
    }
}
