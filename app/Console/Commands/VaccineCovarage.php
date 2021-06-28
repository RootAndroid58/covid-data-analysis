<?php

namespace App\Console\Commands;

use App\Http\Helpers\ScraperHelper;
use Illuminate\Console\Command;

class VaccineCovarage extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:vaccine';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets Vaccine dataset';

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
        $this->info("starting VaccineCoverageData scraper\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        ScraperHelper::VaccineCoverageData();
        $this->info("completed VaccineCoverageData scraper\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());
        return 0;
    }
}
