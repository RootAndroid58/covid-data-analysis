<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class Gov_command extends Command
{
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
        $this->info('covid:gov-austria');
        $this->call('covid:gov-austria');

        $this->info('covid:gov-canada');
        $this->call('covid:gov-canada');

        $this->info('covid:gov-colombia');
        $this->call('covid:gov-colombia');

        $this->info('covid:gov-germany');
        $this->call('covid:gov-germany');

        $this->info('covid:gov-india');
        $this->call('covid:gov-india');

        $this->info('covid:gov-indo');
        $this->call('covid:gov-indo');

        $this->info('covid:gov-israel');
        $this->call('covid:gov-israel');

        $this->info('covid:gov-italy');
        $this->call('covid:gov-italy');

        $this->info('covid:gov-nz');
        $this->call('covid:gov-nz');

        $this->info('covid:gov-nigeria');
        $this->call('covid:gov-nigeria');

        $this->info('covid:gov-southafrica');
        $this->call('covid:gov-southafrica');

        $this->info('covid:gov-southkorea');
        $this->call('covid:gov-southkorea');

        $this->info('covid:gov-switzerland');
        $this->call('covid:gov-switzerland');

        $this->info('covid:gov-uk');
        $this->call('covid:gov-uk');

        $this->info('covid:gov-vietnam');
        $this->call('covid:gov-vietnam');

        return 0;
    }
}
