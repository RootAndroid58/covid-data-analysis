<?php

namespace App\Console\Commands;

use App\Http\Helpers\ScraperHelper;
use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;

class Gov_Colombia_bigdata extends Command
{
    // use WithoutOverlapping;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'covid:gov-colombia-bigdata';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Big Government Data scraper for Colombia.';

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
        $data = ScraperHelper::Gov_Colombia_bigdata();
        return 0;
    }
}
