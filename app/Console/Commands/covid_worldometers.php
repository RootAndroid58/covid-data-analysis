<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Helpers\ScraperHelper;

class covid_worldometers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covid:worldometers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes worldometers covid data and saved it to cache';

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
        $data = ScraperHelper::COVID_worldometers();
        $this->info("scraped and saved COVID_worldometers into cache of today:". ($data['success'][0] ? "success": "failed") ." ,yesterday:". ($data['success'][1] ? "success": "failed") ." ,yesterday2:". ($data['success'][2] ? "success": "failed"));
        $data1 = ScraperHelper::COVID_worldometers_usa();
        $this->info("scraped and saved COVID_worldometers into cache of today:". ($data1['success'][0] ? "success": "failed") ." ,yesterday:". ($data1['success'][1] ? "success": "failed"));
        return 0;
    }
}
