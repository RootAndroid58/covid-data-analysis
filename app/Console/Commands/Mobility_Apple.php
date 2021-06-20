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
        $this->info('starting Apple Mobility report');
        $data = ScraperHelper::apple_mobility();
        $data = ScraperHelper::apple_mobility_trends();
        return 0;
    }
}
