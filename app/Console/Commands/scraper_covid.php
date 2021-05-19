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
        $this->info('scraping covid:worldometers');
        Artisan::call('covid:worldometers');
        return 0;
    }
}
