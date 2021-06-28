<?php

namespace App\Console\Commands;

use App\Http\Helpers\cacheUpdater;
use App\Http\Helpers\ScraperHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class GoogleMobility extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:google';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Scrapes google mobility data';

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
        $this->info('Starting Mibility Scraper');
        $data = ScraperHelper::google_mobility();
        // dd($data[127]);
        $count = count($data); //270
        $i = 1;
        // skip 127 file
        $this->info("%\tno\tpeak mem\tmemory   \tfile    \t\t\t\ttime \t\t\t-");
        foreach($data as $data_){
            if($data_ == '2020_US_Region_Mobility_Report.csv'){ //2020_US_Region_Mobility_Report.csv
                $this->info(round(($i/$count) * 100 , 1) . "%" . "\t$i\t" . memory_get_peak_usage(). "\t" . memory_get_usage(). "\t".$data_. "\t".(microtime(true) - $start). "\t\tSkipped");
                $i++;
                continue;
            }
            $this->info(round(($i/$count) * 100 , 1) . "%" . "\t$i\t" . memory_get_peak_usage(). "\t" . memory_get_usage(). "\t".$data_ . "\t".(microtime(true) - $start));
            $set[] =  ScraperHelper::google_mobility_fun($data_);
            $i ++;
        }
        $time_required = microtime(true) - $start;
        foreach($set as $temp){
            $prod = explode('.',$temp);
            $dataset[$prod[2]][] = $prod[3];
        }
        cache::tags(['prod','prod.google','prod.google.search'])->put('prod.google.search',$dataset,now()->addDays(1));
        $time_elapsed_secs = microtime(true) - $start;
        $this->info("Time required to get raw dataset $time_required");
        $this->info("Time required to process  dataset ".($time_elapsed_secs - $time_required));
        $this->info("Total time requried to process $time_elapsed_secs");

        return 0;
    }
}
