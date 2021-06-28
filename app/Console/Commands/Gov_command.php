<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminated\Console\WithoutOverlapping;

class Gov_command extends Command
{
    use WithoutOverlapping;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:government';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Runs All Gov Scraper sync';

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
        $this->info("starting scraper:government scraper\t\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        $this->call('covid:gov-austria');
        $this->call('covid:gov-canada');
        $this->call('covid:gov-colombia');
        $this->call('covid:gov-germany');
        $this->call('covid:gov-india');
        $this->call('covid:gov-indo');
        $this->call('covid:gov-israel');
        $this->call('covid:gov-italy');
        $this->call('covid:gov-nz');
        $this->call('covid:gov-nigeria');
        $this->call('covid:gov-southafrica');
        $this->call('covid:gov-southkorea');
        $this->call('covid:gov-switzerland');
        $this->call('covid:gov-uk');
        $this->call('covid:gov-vietnam');
        $this->info("completed scraper:government scraper\t\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());
        return 0;
    }
}
