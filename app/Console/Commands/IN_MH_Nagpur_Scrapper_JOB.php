<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Helpers\ScraperHelper;

class IN_MH_Nagpur_Scrapper_JOB extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:INMHNagpur';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This command will scrap data from covidhelpnagpur.in';

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
        $this->info("starting IN_MH_Nagpur scraper\t\t\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        $data = ScraperHelper::Scrap_IN_MH_Nagpur();
        $this->info("completed IN_MH_Nagpur scraper\t\t\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());
        // $this->info('IN-MH-Nagpur successfully scraped of '.$data['status']['rows'].' rows updated:'.$data['status']['updates'].' Added:'.$data['status']['new_data']." new data");
        return 0;
    }
}
