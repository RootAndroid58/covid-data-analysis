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
        \Log::info("Job to scraper IN-MH-Nagpur Started Successfully!");

        $data = ScraperHelper::Scrap_IN_MH_Nagpur();

        $this->info('IN-MH-Nagpur successfully scraped of '.$data['status']['rows'].' rows updated:'.$data['status']['updates'].' Added:'.$data['status']['new_data']." new data");
        return 0;
    }
}
