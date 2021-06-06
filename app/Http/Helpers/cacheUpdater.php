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
        Cache::tags('prod.worldometer')->flush();
        Cache::tags(['prod','prod.worldometer'])->put('worldometer', $response,now()->addMinutes(10));

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
        Cache::tags('prod.worldometer.usa')->flush();
        Cache::tags(['prod','prod.worldometer','prod.worldometer.usa'])->put('worldometer.usa', $response,now()->addMinutes(10));

        return $response;
    }

    static public function COVID_worldometers_continent()
    {
        $data = Cache::get('worldometer');
        if($data == null){
            Artisan::call('covid:worldometers');
            $data = Cache::get('worldometer');
        }

        $sort = new CacheSorter;
        $response = $sort->worldometer_continent($data,$data);

        Cache::tags('prod.worldometer.continents')->flush();
        Cache::tags(['prod','prod.worldometer','prod.worldometer.continents'])->put('worldometer.continents',$response, now()->addMinutes(10));

        return $response;
    }
    static public function COVID_worldometers_countries()
    {
        $data = Cache::get('worldometer');
        if($data == null){
            Artisan::call('covid:worldometers');
            $data = Cache::get('worldometer');
        }

        $sort = new CacheSorter;
        $response = $sort->worldometer_countries($data,$data);

        Cache::tags('prod.worldometer.countries')->flush();
        Cache::tags(['prod','prod.worldometer','prod.worldometer.countries'])->put('worldometer.countries',$response, now()->addMinutes(10));

        return $response;
    }

    static public function gov_updater_Austria()
    {
        $data = array(
            ['cache' => 'temp.gov_austria_historical','prod' => 'prod.gov.austria.historical'],
            ['cache' => 'temp.gov_austria_by_age_grps','prod' => 'prod.gov.austria.byage'],
            ['cache' => 'temp.gov_austria_by_district','prod' => 'prod.gov.austria.bydistrict'],
            ['cache' => 'temp.gov_austria_hospital','prod' => 'prod.gov.austria.hospital'],
            ['cache' => 'temp.gov_austria_version','prod' => 'prod.gov.austria.version'],
        );
        $cacheupdater = new cacheUpdater;
        $historical = $cacheupdater->getCache($data[0],"covid:gov-austria");
        $byage = $cacheupdater->getCache($data[1],"covid:gov-austria");
        $bydistrict = $cacheupdater->getCache($data[2],"covid:gov-austria");
        $hospital = $cacheupdater->getCache($data[3],"covid:gov-austria");
        $version = $cacheupdater->getCache($data[4],"covid:gov-austria");

        $CacheSorter = new CacheSorter;
        $data_historical = $CacheSorter->gov_sorter_Austria_historical($historical);
        $data_byage = $CacheSorter->gov_sorter_Austria_byage($byage);
        $data_bydistrict = $CacheSorter->gov_sorter_Austria_bydistrict($bydistrict);
        $data_hospital = $CacheSorter->gov_sorter_Austria_hospital($hospital);
        $data_version = $CacheSorter->gov_sorter_Austria_version($version);

        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[0]['prod'],$data_historical, now()->addMinutes(10));
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[1]['prod'],$data_byage, now()->addMinutes(10));
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[2]['prod'],$data_bydistrict, now()->addMinutes(10));
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[3]['prod'],$data_hospital, now()->addMinutes(10));
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[4]['prod'],$data_version, now()->addMinutes(10));

        return true;

    }

    static public function getCache($array,$call)
    {
        $data = Cache::get($array['cache']);
        if($data == null){
            Artisan::call($call);
            $data = Cache::get($array['cache']);
        }
        return $data;
    }
}
