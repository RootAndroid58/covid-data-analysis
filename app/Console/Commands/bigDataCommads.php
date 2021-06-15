<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminated\Console\WithoutOverlapping;

class bigDataCommads extends Command
{
    use WithoutOverlapping;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:big-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command runs all bigdata commands in sync';

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
        $this->info('gov-colombia-bigdata');
        $this->call('covid:gov-colombia-bigdata');
        return 0;
    }
}
