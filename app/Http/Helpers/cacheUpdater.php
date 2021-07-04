<?php

namespace App\Http\Helpers;

use App\Http\Helpers\CacheSorter;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;

class cacheUpdater
{
    static public function historical()
    {
        $data = array(
            ['cache' => 'casesResponse_temp','prod' => null],
            ['cache' => 'deathsResponse_temp','prod' => null],
            ['cache' => 'recoveredResponse_temp','prod' => null],
            ['cache' => null ,'prod' => 'historical_all'],
        );
        $cacheupdater = new cacheUpdater;
        $cases = $cacheupdater->getCache($data[0]['cache'],"covid:historical");
        $deaths = $cacheupdater->getCache($data[1]['cache'],"covid:historical");
        $recovered = $cacheupdater->getCache($data[2]['cache'],"covid:historical");

        $CacheSorter = new CacheSorter;
        $prod = $CacheSorter->historical($cases,$deaths,$recovered,'all');
        unset($cases,$deaths,$recovered);
        Cache::tags(['prod','prod.historical'])->put($data[3]['prod'], $prod, now()->addDays(1));
        unset($prod);
    }

    static public function worldometer()
    {
        $data = array(
            ['cache' => 'temp.worldometers.today' ,'prod' => null],
            ['cache' => 'temp.worldometers.yesterday' ,'prod' => null],
            ['cache' => 'temp.worldometers.yesterday2' ,'prod' => null],
            ['cache' => null ,'prod' => 'worldometer'],
            ['cache' => null ,'prod' => 'worldometer.continents'],
            ['cache' => null ,'prod' => 'worldometer.countries'],
        );
        $cacheupdater = new cacheUpdater;
        $today = $cacheupdater->getCache($data[0]['cache'],'covid:worldometers');
        $yesterday = $cacheupdater->getCache($data[1]['cache'],'covid:worldometers');
        $yesterday2 = $cacheupdater->getCache($data[2]['cache'],'covid:worldometers');


        $sort = new CacheSorter;
        $response = $sort->worldometer($today,$yesterday,$yesterday2);
        unset($today,$yesterday,$yesterday2);
        Cache::tags(['prod','prod.worldometer'])->put($data[3]['prod'], $response, now()->addDays(1));
        $worldometer_continent = $sort->worldometer_continent($response,$response);
        Cache::tags(['prod','prod.worldometer'])->put($data[4]['prod'], $worldometer_continent, now()->addDays(1));
        unset($worldometer_continent);
        $worldometer_countries = $sort->worldometer_countries($response,$response);
        Cache::tags(['prod','prod.worldometer'])->put($data[5]['prod'], $worldometer_countries, now()->addDays(1));
        unset($response,$worldometer_countries);

        return 0;
    }
    static public function COVID_worldometers_usa()
    {
        $data = array(
            ['cache' => 'temp.worldometers.states.today' ,'prod' => null],
            ['cache' => 'temp.worldometers.states.yesterday' ,'prod' => null],
            ['cache' => null ,'prod' => 'worldometer.usa'],
        );
        $cacheupdater = new cacheUpdater;
        $today = $cacheupdater->getCache($data[0]['cache'],'covid:worldometers');
        $yesterday = $cacheupdater->getCache($data[1]['cache'],'covid:worldometers');

        $sort = new CacheSorter;
        $response = $sort->worldometer_states($today,$yesterday);
        unset($today,$yesterday);
        Cache::tags(['prod','prod.worldometer','prod.worldometer.usa'])->put($data[2]['prod'], $response, now()->addDays(1));
        unset($response);
        return 0;
    }


    static public function gov_updater_Austria()
    {
        $data = array(
            ['cache' => 'temp.gov_austria_default','prod' => 'prod.gov.austria.default','type' => null],
            ['cache' => 'temp.gov_austria_historical','prod' => 'prod.gov.austria.historical','type' => 'timeline'],
            ['cache' => 'temp.gov_austria_historical_cases_ems','prod' => 'prod.gov.austria.historical_cases_ems','type' => 'timeline'],
            ['cache' => 'temp.gov_austria_vaccination','prod' => 'prod.gov.austria.vaccination','type' => null],
            ['cache' => 'temp.gov_austria_by_age_grps','prod' => 'prod.gov.austria.byage','type' => 'age'],
            ['cache' => 'temp.gov_austria_by_district','prod' => 'prod.gov.austria.bydistrict','type' => 'district'],
            ['cache' => 'temp.gov_austria_timeline_bbg','prod' => 'prod.gov.austria.timeline_bbg','type' => null],
            ['cache' => 'temp.gov_austria_timeline','prod' => 'prod.gov.austria.timeline','type' => null],
            ['cache' => 'temp.gov_austria_hospital','prod' => 'prod.gov.austria.hospital','type' => 'hospital'],
            ['cache' => 'temp.gov_austria_timeline_faelle_bundeslaender','prod' => 'prod.gov.austria.timeline_cases_federal_states','type' => null],
            ['cache' => null,'prod' => 'prod.gov.austria.hospital_beds' ,'type' => 'hospital_beds'],
        );
        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;

        foreach ($data as $data_) {
            if($data_['cache'] == null) continue;
            $temp = $cacheupdater->getCache($data_['cache'],"covid:gov-austria");

            if($data_['prod'] == 'prod.gov.austria.default'){
                $prod = $CacheSorter->gov_sorter_Austria_hospital($temp);
                $cacheKey = $CacheSorter->search_by_find($data,'type','hospital_beds');
                Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[$cacheKey]['prod'],$prod,  now()->addDays(1));
                unset($prod,$cacheKey);
            }
            $prod = $CacheSorter->gov_sorter_Austria($temp);
            unset($temp);
            Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data_['prod'],$prod,  now()->addDays(1));
            unset($prod);
        }
        return 0;

    }

    static public function gov_updater_canada()
    {
        $cacheupdater = new cacheUpdater;
        $data = $cacheupdater->getCache('temp.gov_canada','covid:gov-canada');

        $CacheSorter = new CacheSorter;
        $canada = $CacheSorter->gov_sorter_canada($data);
        unset($data);

        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put('prod.gov.canada',$canada,  now()->addDays(1));
        unset($canada);

        return 0;
    }

    static public function gov_updater_canada_timeline()
    {
        $data = array(
            ['cache' => 'temp.gov_canada_timeline_active','prod' => 'prod.gov.canada.timeline_active','type' => 'active'],
            ['cache' => 'temp.gov_canada_timeline_cases','prod' => 'prod.gov.canada.timeline_cases','type' => 'cases'],
            ['cache' => 'temp.gov_canada_timeline_deaths','prod' => 'prod.gov.canada.timeline_deaths','type' => 'deaths'],
            ['cache' => 'temp.gov_canada_timeline_recovered','prod' => 'prod.gov.canada.timeline_recovered','type' => 'recovered'],
            ['cache' => 'temp.gov_canada_timeline_testing','prod' => 'prod.gov.canada.timeline_testing','type' => 'testing'],
            ['cache' => 'temp.gov_canada_timeline_vaccine_administration','prod' => 'prod.gov.canada.timeline_vaccine_administration','type' => 'vaccine_administration'],
            ['cache' => 'temp.gov_canada_timeline_vaccine_completion','prod' => 'prod.gov.canada.vaccine_completion','type' => 'vaccine_completion'],
            ['cache' => 'temp.gov_canada_timeline_vaccine_distribution','prod' => 'prod.gov.canada.vaccine_distribution','type' => 'vaccine_distribution'],
        );
        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;

        foreach ($data as $data) {
            $temp =  $cacheupdater->getCache($data['cache'],'covid:gov-canada');
            $prod = $CacheSorter->gov_sorter_canada_timeline($temp,$data['type']);
            unset($temp);
            Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data['prod'],$prod,  now()->addDays(1));
            unset($prod);
        }

        return 0;
    }

    static public function gov_updater_colombia()
    {
        $data = array(
            ['cache' => 'temp.gov_colombia_vaccines_allocations','prod' => 'prod.gov.canada.vaccines_allocations'],
            ['cache' => 'temp.gov_colombia_pcr_tests_municipal','prod' => 'prod.gov.canada.pcr_tests_municipal'],
        );
        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;

        foreach ($data as $data) {
            $temp = $cacheupdater->getCache($data['cache'],'covid:gov-colombia');
            $prod =  $CacheSorter->gov_sorter_Colombia($temp);
            unset($temp);
            Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data['prod'],$prod,  now()->addDays(1));
            unset($prod);
        }

        return 0;
    }

    static public function gov_updater_colombia_bigdata()
    {
        $data = array(
            ['cache' => 'temp.gov_colombia_bigdata','prod' => 'prod.gov.colombia.bigdata'],
        );

        $cacheupdater = new cacheUpdater;
        $cases = $cacheupdater->getCache($data[0]['cache'],'covid:gov-colombia-bigdata');


        $CacheSorter = new CacheSorter;
        $bigdata = $CacheSorter->gov_sorter_Colombia_bigdata($cases);
        unset($cases);


        Cache::tags(['prod','prod.gov','prod.gov.colombia'])->put($data[0]['prod'],$bigdata, now()->addDays(1));
        unset($bigdata);
        return 0;
    }

    static public function gov_updater_germany()
    {
        $data = array(
            ['cache' => 'temp.gov_germany','prod' => 'prod.gov.germany'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-germany');

        $CacheSorter = new CacheSorter;
        $Germany_data = $CacheSorter->gov_sorter_germany($temp_data);
        unset($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[0]['prod'],$Germany_data,  now()->addDays(1));
        unset($Germany_data);

        return 0;
    }

    static public function gov_updater_india()
    {
        $data = array(
            ['cache' => 'temp.gov_india','prod' => 'prod.gov.india'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-india');

        $CacheSorter = new CacheSorter;
        $India_data = $CacheSorter->gov_sorter_india($temp_data);
        unset($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[0]['prod'],$India_data,  now()->addDays(1));
        unset($India_data);
        return 0;
    }

    static public function gov_updater_indo()
    {
        $data = array(
            ['cache' => 'temp.gov_indonesia.data','prod' => 'prod.gov.indonesia.data'],
            ['cache' => 'temp.gov_indonesia.update','prod' => 'prod.gov.indonesia.update'],
            ['cache' => 'temp.gov_indonesia.prev','prod' => 'prod.gov.indonesia.prev'],
        );
        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;

        foreach($data as $data){
            $temp = $cacheupdater->getCache($data['cache'],'covid:gov-indo');
            $prod = $CacheSorter->gov_sorter_indo($temp);
            unset($temp);
            Cache::tags(['prod','prod.gov','prod.gov.indonesia'])->put($data['prod'],$prod,  now()->addDays(1));
            unset($prod);
        }
        return 0;


    }

    static public function gov_updater_israel()
    {
        $data = array(
            ['cache' => 'temp.gov_israel','prod' => 'prod.gov.israel_data'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-indo');

        $CacheSorter = new CacheSorter;
        $israel_data = $CacheSorter->gov_sorter_israel($temp_data);
        unset($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.israel'])->put($data[0]['prod'],$israel_data,  now()->addDays(1));
        unset($israel_data);

        return 0;
    }
    static public function gov_updater_italy()
    {
        $data = array(
            ['cache' => 'temp.gov_italy','prod' => 'prod.gov.italy'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-italy');

        $CacheSorter = new CacheSorter;
        $italy_data = $CacheSorter->gov_sorter_italy($temp_data);
        unset($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.italy'])->put($data[0]['prod'],$italy_data,  now()->addDays(1));
        unset($italy_data);
        return 0;
    }

    static public function gov_updater_NewZealand()
    {
        $data = array(
            ['cache' => 'temp.gov_newzealand','prod' => 'prod.gov.newzealand'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-nz');


        $CacheSorter = new CacheSorter;
        $newzealand_data = $CacheSorter->gov_sorter_NewZealand($temp_data);
        unset($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.newzealand'])->put($data[0]['prod'],$newzealand_data,  now()->addDays(1));
        unset($newzealand_data);
        return 0;
    }
    static public function gov_updater_Nigeria()
    {
        $data = array(
            ['cache' => 'temp.gov_nigeria','prod' => 'prod.gov.nigeria'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-nigeria');

        $CacheSorter = new CacheSorter;
        $nigeria_data = $CacheSorter->gov_sorter_nigeria($temp_data);
        unset($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.nigeria'])->put($data[0]['prod'],$nigeria_data,  now()->addDays(1));
        unset($nigeria_data);
        return 0;
    }

    static public function gov_updater_southafrica()
    {
        $data = array(
            ['cache' => 'temp.gov_south_africa_confirmed','prod' => 'prod.gov.southafrica.confirmed'],
            ['cache' => 'temp.gov_south_africa_deaths','prod' => 'prod.gov.southafrica.deaths'],
            ['cache' => 'temp.gov_south_africa_recovered','prod' => 'prod.gov.southafrica.recovered'],
            ['cache' => 'temp.gov_south_africa_testing','prod' => 'prod.gov.southafrica.testing'],
            ['cache' => 'temp.gov_south_africa_vaccination','prod' => 'prod.gov.southafrica.vaccination'],
            ['cache' => null,'prod' => 'prod.gov.southafrica.fullforms'],
        );
        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;

        foreach($data as $data){
            if($data['cache'] == null){
                $fullforms = [
                    'message' => 'What is EC, FS ,etc you ask? This is what I got from google search. These are the names of provinces.',
                    'data' => array(
                        'EC' => 'Eastern Cape',
                        'FS' => 'Free State',
                        'GP' => 'Gauteng',
                        'KZN' => 'KwaZulu-Natal',
                        'LP' => 'Limpopo',
                        'MP' => 'Mpumalanga',
                        'NC' => 'North West',
                        'NW' => 'Northern Cape',
                        'WC' => 'Western Cape',
                    ),
                    'source_of_info' => 'https://www.gov.za/about-sa/south-africas-provinces',
                ];
                Cache::tags(['prod','prod.gov','prod.gov.southafrica'])->put($data['prod'],$fullforms);
                continue;
            }
            $temp = $cacheupdater->getCache($data['cache'],'covid:gov-southafrica');
            $prod = $CacheSorter->gov_sorter_southafrica($temp);
            unset($temp);
            Cache::tags(['prod','prod.gov','prod.gov.southafrica'])->put($data['prod'],$prod,  now()->addDays(1));
            unset($prod);
        }

        return 0;
    }

    static public function gov_updater_southkorea()
    {
        $data = array(
            ['cache' => 'temp.gov_south_korea','prod' => 'prod.gov.southkorea'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-southkorea');

        $CacheSorter = new CacheSorter;
        $newzealand_data = $CacheSorter->gov_sorter_southkorea($temp_data);
        unset($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.southkorea'])->put($data[0]['prod'],$newzealand_data,  now()->addDays(1));
        unset($newzealand_data);
        return 0;
    }
    static public function gov_updater_switzerland()
    {
        $data = array(
            ['cache' => 'temp.gov_switzerland','prod' => 'prod.gov.switzerland'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-switzerland');

        $CacheSorter = new CacheSorter;
        $switzerland = $CacheSorter->gov_sorter_switzerland($temp_data);
        unset($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.switzerland'])->put($data[0]['prod'],$switzerland,  now()->addDays(1));
        unset($switzerland);

        return 0;
    }
    static public function gov_updater_UK()
    {
        $data = array(
            ['cache' => 'temp.gov_UK','prod' => 'prod.gov.UK'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-uk');

        $CacheSorter = new CacheSorter;
        $uk_data = $CacheSorter->gov_sorter_uk($temp_data);
        unset($temp_data);
        Cache::tags(['prod','prod.gov','prod.gov.UK'])->put($data[0]['prod'],$uk_data,  now()->addDays(1));
        unset($uk_data);
        return 0;
    }
    static public function gov_updater_vietnam()
    {
        $data = array(
            ['cache' => 'temp.gov_vietnam','prod' => 'prod.gov.vietnam.stats'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_stats = $cacheupdater->getCache($data[0]['cache'],'covid:gov-vietnam');

        $CacheSorter = new CacheSorter;
        $stats = $CacheSorter->gov_sorter_vietnam($temp_stats);
        unset($temp_stats);
        Cache::tags(['prod','prod.gov','prod.gov.vietnam'])->put($data[0]['prod'],$stats,  now()->addDays(1));
        unset($stats);

        return 0;
    }

    static public function Mobility_apple()
    {
        $data = array(
            ['cache' => 'temp.apple_mobility','prod' => 'prod.mobility.apple'],
            ['cache' => 'temp.apple_mobility_us','prod' => 'prod.mobility.apple_us'],
            ['cache' => null,'prod' => 'prod.mobility.apple.country'],
            ['cache' => null,'prod' => 'prod.mobility.apple_us.states'],
        );

        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;

        $raw_apple = $cacheupdater->getCache($data[0]['cache'],'scraper:apple');
        $apple = $CacheSorter->mobility($raw_apple);
        unset($raw_apple);
        Cache::tags(['prod','prod.mobility','prod.mobility.apple'])->put($data[0]['prod'],$apple, now()->addDays(1));
        $country_apple = $cacheupdater->chunkSearch($apple['pages'],'country');
        Cache::tags(['prod','prod.mobility','prod.mobility.apple'])->put($data[2]['prod'],$country_apple, now()->addDays(1));
        unset($country_apple);

        $raw_apple_us = $cacheupdater->getCache($data[1]['cache'],'scraper:apple');
        $apple_us = $CacheSorter->mobility($raw_apple_us);
        unset($raw_apple_us);
        Cache::tags(['prod','prod.mobility','prod.mobility.apple'])->put($data[1]['prod'],$apple_us, now()->addDays(1));
        $country_apple_us = $cacheupdater->chunkSearch($apple_us['pages'],'state');
        Cache::tags(['prod','prod.mobility','prod.mobility.apple'])->put($data[3]['prod'],$country_apple_us, now()->addDays(1));
        unset($country_apple_us);

        return 0;
    }

    static public function Mobility_apple_trends()
    {
        $data = array(
            ['cache' => 'temp.apple_mobility_trends','prod' => 'prod.mobility.appletrends'],
            ['cache' => null,'prod' => 'prod.mobility.appletrends.regions'],
        );

        $cacheupdater = new cacheUpdater;
        $raw_appletrends = $cacheupdater->getCache($data[0]['cache'],'scraper:apple');

        $CacheSorter = new CacheSorter;
        $appletrends = $CacheSorter->mobility_apple_trends($raw_appletrends);
        unset($raw_appletrends);
        $country_apple_us = $cacheupdater->chunkSearch($appletrends['pages'],'region');

        Cache::tags(['prod','prod.mobility','prod.mobility.apple'])->put($data[0]['prod'],$appletrends, now()->addDays(1));
        unset($appletrends);
        Cache::tags(['prod','prod.mobility','prod.mobility.apple'])->put($data[1]['prod'],$country_apple_us, now()->addDays(1));
        unset($country_apple_us);
        return 0;
    }

    static public function therapeutics()
    {
        $data = array(
            ['cache' => 'temp.therapeutics','prod' => 'prod.therapeutics'],
        );

        $cacheupdater = new cacheUpdater;
        $raw_therapeutics = $cacheupdater->getCache($data[0]['cache'],'scraper:raps');

        $CacheSorter = new CacheSorter;
        $therapeutics = $CacheSorter->therapeutics($raw_therapeutics);
        unset($raw_therapeutics);

        Cache::tags(['prod','prod.raps'])->put($data[0]['prod'],$therapeutics, now()->addDays(1));
        unset($therapeutics);
        return 0;
    }

    static public function VaccineCoverage()
    {
        $data = array(
            ['cache' => 'temp.VaccineCoverage','prod' => 'prod.vaccine'],
            ['cache' => null,'prod' => 'prod.vaccine.location'],
        );

        $cacheupdater = new cacheUpdater;
        $raw_vaccine = $cacheupdater->getCache($data[0]['cache'],'scraper:vaccine');

        $CacheSorter = new CacheSorter;
        $vaccine = $CacheSorter->mobility($raw_vaccine);
        unset($raw_vaccine);
        $location_vaccine = $cacheupdater->chunkSearch($vaccine['pages'],'location');

        Cache::tags(['prod','prod.vaccine'])->put($data[0]['prod'],$vaccine, now()->addDays(1));
        Cache::tags(['prod','prod.vaccine'])->put($data[1]['prod'],$location_vaccine, now()->addDays(1));
        unset($vaccine,$location_vaccine);
        return 0;
    }

    static public function NYT()
    {
        $data = array(
            ['cache' => 'temp.NYT.us-counties-recent','prod' => 'prod.NYT.us-countries-recent'],
            ['cache' => 'temp.NYT.us-counties','prod' => 'prod.NYT.us-countries'],
            ['cache' => 'temp.NYT.us-states','prod' => 'prod.NYT.us-states'],
            ['cache' => 'temp.NYT.us','prod' => 'prod.NYT.us'],
        );

        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;

        foreach($data as $data){
            $raw = $cacheupdater->getCache($data['cache'],'scraper:nyt');
            $prod = $CacheSorter->mobility($raw);
            unset($raw);
            Cache::tags(['prod','prod.NYT'])->put($data['prod'],$prod, now()->addDays(1));
            if($data['prod'] !== 'prod.NYT.us'){
                $state = $cacheupdater->chunkSearch($prod['pages'],'state');
                unset($prod);
                Cache::tags(['prod','prod.NYT'])->put($data['prod'].".states",$state, now()->addDays(1));
                unset($state);

            }
        }
        return 0;
    }
    static public function NYT_1()
    {
        $data = array(
            ['cache' => 'temp.NYT.rolling-averages.us-counties-recent','prod' => 'prod.NYT.avarage.us-countries-recent'],
            ['cache' => 'temp.NYT.rolling-averages.us-states','prod' => 'prod.NYT.avarage.us-states'],
            ['cache' => 'temp.NYT.rolling-averages.us','prod' => 'prod.NYT.avarage.us'],
        );

        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;
        foreach($data as $data){

            $raw = $cacheupdater->getCache($data['cache'],'scraper:nyt');
            $prod = $CacheSorter->mobility($raw);
            unset($raw);
            if($data['prod'] !== 'prod.NYT.avarage.us'){
                $state = $cacheupdater->chunkSearch($prod['pages'],'state');
                Cache::tags(['prod','prod.NYT'])->put($data['prod'].".states",$state, now()->addDays(1));
                unset($state);

            }
            Cache::tags(['prod','prod.NYT','prod.NYT.avarage'])->put($data['prod'],$prod, now()->addDays(1));
        }
        return 0;
    }
    static public function nyt_big($total,$count)
    {
        $data = array(
            ['cache' => 'temp.NYT.rolling-averages.us-counties','prod' => 'prod.NYT.avarage.us-countries'],
        );
        Cache::tags(['prod','prod.NYT','prod.NYT.avarage'])->put($data[0]['prod'].".count",$count, now()->addDays(1));
        Cache::tags(['prod','prod.NYT','prod.NYT.avarage'])->put($data[0]['prod'].".total",$total, now()->addDays(1));

        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;
        foreach($data as $data){
            for ($i=0; $i < $count; $i++) {
                $temp_data = $cacheupdater->getCache($data['cache']."_$i",'scraper:nyt-big');
                $Newdata= $CacheSorter->nyt_big($temp_data);
                unset($temp_data);
                Cache::tags(['prod','prod.NYT','prod.NYT.avarage'])->put($data['prod']."_$i" ,$Newdata, now()->addDays(1));
            }
        }

        return 0;
    }

    static public function google_mobility($cacheKey)
    {
        $cacheupdater = new cacheUpdater;
        $CacheSorter = new CacheSorter;
        $data = $cacheupdater->getCache($cacheKey,"scraper:google");
        $data = $CacheSorter->mobility($data);
        $prod = explode('.',$cacheKey);
        cache::tags(['prod','prod.google'])->put("prod.google.$prod[2].$prod[3]",$data,now()->addDays(1));
        unset($data);
        return "prod.google.$prod[2].$prod[3]";
    }














    static public function getCache($key,$call)
    {
        $data = Cache::get($key);
        if($data == null){
            Artisan::call($call);
            $data = Cache::get($key);
        }
        return $data;
    }
    static public function chunkSearch($chunk,$find)
    {
        $search = collect($chunk)->map(function ($array) use ($find){
            return collect($array)->unique($find)->all();
        })->toArray();
        $search = array_merge(...$search);

        $search = collect($search)->unique($find)->pluck($find)->toArray();
        return $search;
    }
}
