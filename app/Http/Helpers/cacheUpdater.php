<?php

namespace App\Http\Helpers;

use App\Http\Helpers\ApiHelper;
use App\Http\Helpers\CacheSorter;
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
            return Artisan::call('scraper:covid');
        }

        $CacheSorter = new CacheSorter;
        $response = $CacheSorter->historical($data,$data1,$data2,'all');
        Cache::tags(['prod','prod.historical'])->put('historical_all', $response,now()->addMinutes(10));
    }

    static public function worldometer()
    {
        $today = Cache::get('temp.worldometers.today');
        $yesterday = Cache::get('temp.worldometers.yesterday');
        $yesterday2 = Cache::get('temp.worldometers.yesterday2');
        if($today == null || $yesterday == null || $yesterday2 == null){
            Artisan::call('scraper:worldometers');
            $today = Cache::get('temp.worldometers.today');
            $yesterday = Cache::get('temp.worldometers.yesterday');
            $yesterday2 = Cache::get('temp.worldometers.yesterday2');
        }

        $sort = new CacheSorter;
        $response = $sort->worldometer($today,$yesterday,$yesterday2);
        Cache::put('historical_all', $response,now()->addMinutes(10));

        return $response;
    }
    static public function COVID_worldometers_usa()
    {
        $today = Cache::get('temp.worldometers.states.today');
        $yesterday = Cache::get('temp.worldometers.states.yesterday');
        if($today == null || $yesterday == null ){
            Artisan::call('scraper:worldometers');
            $today = Cache::get('temp.worldometers.states.today');
            $yesterday = Cache::get('temp.worldometers.states.yesterday');
        }

        $sort = new CacheSorter;
        $response = $sort->worldometer_states($today,$yesterday);
        Cache::put('historical_all', $response,now()->addMinutes(10));

        return $response;
    }
}
