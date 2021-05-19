<?php

namespace App\Http\Helpers;

use App\Http\Helpers\ApiHelper;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class cacheUpdater
{
    static public function historical()
    {
        $data = Cache::get('casesResponse_temp');
        $data1 = Cache::get('deathsResponse_temp');
        $data2 = Cache::get('recoveredResponse_temp');
        if($data == null || $data1 == null || $data2 == null){
            Artisan::call('scraper:covid');
            $data = Cache::get('casesResponse_temp');
            $data1 = Cache::get('deathsResponse_temp');
            $data2 = Cache::get('recoveredResponse_temp');
        }

        $ApiHelper = new ApiHelper;
        $response = $ApiHelper->sort($data,$data1,$data2,'all');
        Cache::put('historical', $response);
    }
}
