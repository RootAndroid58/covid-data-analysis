<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;

class MobilityStarter extends Command
{
    use WithoutOverlapping;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:mobility';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Starts Scraping All mobility Commands';

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
        $this->info('scraper:apple');
        $this->call('scraper:apple');
        return 0;
    }
}
