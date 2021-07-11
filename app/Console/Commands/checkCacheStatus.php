<?php

namespace App\Console\Commands;

use App\Http\Helpers\DataHelper;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class checkCacheStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scraper:status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Gets The status of the cache';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    public $data = array();

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
        $this->info("starting status scraper\t\ttime \t\t".memory_get_peak_usage(). "\t" . memory_get_usage());
        $DataHelper = new DataHelper;
        $data = $DataHelper->cacheKeys;
        $status = array();

        foreach($data as $item){
            $status = array_merge($status, $this->checkCache($item)) ;
        }
        // dd($status);
        Cache::tags(['status'])->put('cache.status',$status);
        $this->info("completed status scraper\t". round(microtime(true) - $start,11). "\t" .memory_get_peak_usage(). "\t" . memory_get_usage());

        return 0;
    }

    public function checkCache($items)
    {
        // dd($items);
        $data = array();
        $temp = true;
        $prod = true;
        if(isset($items['keys'])){
            foreach($items['keys'] as $item){
                $disabled = isset($items['disabled']) ? true : false ;
                $temp_available = $this->cache($item['temp'],$disabled);
                $prod_available = $this->cache($item['prod'],$disabled);
                if(!$temp_available)$temp = false;
                if(!$prod_available) $prod = false;
                $data[$item['name']]['temp'] = $temp_available;
                $data[$item['name']]['prod'] = $prod_available;
            }
        }
        return array_merge($this->data, [array( 'title' => $items['title'], 'keys' => $data, 'temp_available' => $temp, 'prod_available' => $prod , 'disabled' => $disabled )  ]);
        // dd($temp);
    }
    public function cache($cacheKey,$disabled)
    {
        if($disabled) return false;
        if($cacheKey !== null){
            $has = Cache::has($cacheKey);
            if($has){
                unset($has);
                return true;
            }
        }
        if($cacheKey == null) return true;
        return false;
    }
}
