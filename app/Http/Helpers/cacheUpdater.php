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
        Cache::tags(['prod','prod.historical'])->put('historical_all', $response,now()->addHour());
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
        Cache::tags(['prod','prod.worldometer'])->put('worldometer', $response,now()->addHour());

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
        Cache::tags(['prod','prod.worldometer','prod.worldometer.usa'])->put('worldometer.usa', $response,now()->addHour());

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
        Cache::tags(['prod','prod.worldometer','prod.worldometer.continents'])->put('worldometer.continents',$response, now()->addHour());

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
        Cache::tags(['prod','prod.worldometer','prod.worldometer.countries'])->put('worldometer.countries',$response, now()->addHour());

        return $response;
    }

    static public function gov_updater_Austria()
    {
        $data = array(
            ['cache' => 'temp.gov_austria_default','prod' => 'prod.gov.austria.default'],
            ['cache' => 'temp.gov_austria_historical','prod' => 'prod.gov.austria.historical'],
            ['cache' => 'temp.gov_austria_historical_cases_ems','prod' => 'prod.gov.austria.historical_cases_ems'],
            ['cache' => 'temp.gov_austria_vaccination','prod' => 'prod.gov.austria.vaccination'],
            ['cache' => 'temp.gov_austria_by_age_grps','prod' => 'prod.gov.austria.byage'],
            ['cache' => 'temp.gov_austria_by_district','prod' => 'prod.gov.austria.bydistrict'],
            ['cache' => 'temp.gov_austria_timeline_bbg','prod' => 'prod.gov.austria.timeline_bbg'],
            ['cache' => 'temp.gov_austria_timeline','prod' => 'prod.gov.austria.timeline'],
            ['cache' => 'temp.gov_austria_hospital','prod' => 'prod.gov.austria.hospital'],
            ['cache' => 'temp.gov_austria_timeline_faelle_bundeslaender','prod' => 'prod.gov.austria.timeline_cases_federal_states'],
            ['cache' => null,'prod' => 'prod.gov.austria.hospital_beds'],
        );
        $cacheupdater = new cacheUpdater;
        $default = $cacheupdater->getCache($data[0]['cache'],"covid:gov-austria");
        $historical = $cacheupdater->getCache($data[1]['cache'],"covid:gov-austria");
        $historical_cases_ems = $cacheupdater->getCache($data[2]['cache'],"covid:gov-austria");
        $vaccination = $cacheupdater->getCache($data[3]['cache'],"covid:gov-austria");
        $byage = $cacheupdater->getCache($data[4]['cache'],"covid:gov-austria");
        $bydistrict = $cacheupdater->getCache($data[5]['cache'],"covid:gov-austria");
        $timeline_bbg = $cacheupdater->getCache($data[6]['cache'],"covid:gov-austria");
        $timeline = $cacheupdater->getCache($data[7]['cache'],"covid:gov-austria");
        $hospital = $cacheupdater->getCache($data[8]['cache'],"covid:gov-austria");
        $timeline_faelle_bundeslaender = $cacheupdater->getCache($data[9]['cache'],"covid:gov-austria");

        $CacheSorter = new CacheSorter;
        $data_default = $CacheSorter->gov_sorter_Austria($default);
        $data_historical = $CacheSorter->gov_sorter_Austria($historical,'timeline');
        $data_historical_cases_ems = $CacheSorter->gov_sorter_Austria($historical_cases_ems,'timeline');
        $data_vaccination = $CacheSorter->gov_sorter_Austria($vaccination);
        $data_byage = $CacheSorter->gov_sorter_Austria($byage,'age');
        $data_bydistrict = $CacheSorter->gov_sorter_Austria($bydistrict,'district');
        $data_timeline_bbg = $CacheSorter->gov_sorter_Austria($timeline_bbg);
        $data_timeline = $CacheSorter->gov_sorter_Austria($timeline);
        $data_hospital = $CacheSorter->gov_sorter_Austria($hospital,'hospital');
        $data_timeline_faelle_bundeslaender = $CacheSorter->gov_sorter_Austria($timeline_faelle_bundeslaender);
        $hospital_beds = $CacheSorter->gov_sorter_Austria_hospital($default);

        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[0]['prod'],$data_default, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[1]['prod'],$data_historical, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[2]['prod'],$data_historical_cases_ems, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[3]['prod'],$data_vaccination, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[4]['prod'],$data_byage, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[5]['prod'],$data_bydistrict, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[6]['prod'],$data_timeline_bbg, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[7]['prod'],$data_timeline, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[8]['prod'],$data_hospital, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[9]['prod'],$data_timeline_faelle_bundeslaender, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.austria'])->put($data[10]['prod'],$hospital_beds, now()->addHour());

        return true;

    }

    static public function gov_updater_canada()
    {
        $cacheupdater = new cacheUpdater;
        $data = $cacheupdater->getCache('temp.gov_canada','covid:gov-canada');

        $CacheSorter = new CacheSorter;
        $canada = $CacheSorter->gov_sorter_canada($data);

        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put('prod.gov.canada',$canada, now()->addHour());

        return $canada;
    }

    static public function gov_updater_canada_timeline()
    {
        $data = array(
            ['cache' => 'temp.gov_canada_timeline_active','prod' => 'prod.gov.canada.timeline_active'],
            ['cache' => 'temp.gov_canada_timeline_cases','prod' => 'prod.gov.canada.timeline_cases'],
            ['cache' => 'temp.gov_canada_timeline_deaths','prod' => 'prod.gov.canada.timeline_deaths'],
            ['cache' => 'temp.gov_canada_timeline_recovered','prod' => 'prod.gov.canada.timeline_recovered'],
            ['cache' => 'temp.gov_canada_timeline_testing','prod' => 'prod.gov.canada.timeline_testing'],
            ['cache' => 'temp.gov_canada_timeline_vaccine_administration','prod' => 'prod.gov.canada.timeline_vaccine_administration'],
            ['cache' => 'temp.gov_canada_timeline_vaccine_completion','prod' => 'prod.gov.canada.vaccine_completion'],
            ['cache' => 'temp.gov_canada_timeline_vaccine_distribution','prod' => 'prod.gov.canada.vaccine_distribution'],
        );
        $cacheupdater = new cacheUpdater;
        $active = $cacheupdater->getCache($data[0]['cache'],'covid:gov-canada');
        $cases = $cacheupdater->getCache($data[1]['cache'],'covid:gov-canada');
        $deaths = $cacheupdater->getCache($data[2]['cache'],'covid:gov-canada');
        $recovered = $cacheupdater->getCache($data[3]['cache'],'covid:gov-canada');
        $testing = $cacheupdater->getCache($data[4]['cache'],'covid:gov-canada');
        $vaccine_administration = $cacheupdater->getCache($data[5]['cache'],'covid:gov-canada');
        $vaccine_completion = $cacheupdater->getCache($data[6]['cache'],'covid:gov-canada');
        $vaccine_distribution = $cacheupdater->getCache($data[7]['cache'],'covid:gov-canada');

        $CacheSorter = new CacheSorter;
        $data_active = $CacheSorter->gov_sorter_canada_timeline($active,'active');
        $data_cases = $CacheSorter->gov_sorter_canada_timeline($cases,'cases');
        $data_deaths =$CacheSorter->gov_sorter_canada_timeline($deaths,'deaths');
        $data_recovered = $CacheSorter->gov_sorter_canada_timeline($recovered,'recovered');
        $data_testing =$CacheSorter->gov_sorter_canada_timeline($testing,'testing');
        $data_vaccine_administration =$CacheSorter->gov_sorter_canada_timeline($vaccine_administration,'vaccine_administration');
        $data_vaccine_completion =$CacheSorter->gov_sorter_canada_timeline($vaccine_completion,'vaccine_completion');
        $data_vaccine_distribution =$CacheSorter->gov_sorter_canada_timeline($vaccine_distribution,'vaccine_distribution');

        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[0]['prod'],$data_active, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[1]['prod'],$data_cases, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[2]['prod'],$data_deaths, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[3]['prod'],$data_recovered, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[4]['prod'],$data_testing, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[5]['prod'],$data_vaccine_administration, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[6]['prod'],$data_vaccine_completion, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[7]['prod'],$data_vaccine_distribution, now()->addHour());

        return true;
    }

    static public function gov_updater_colombia()
    {
        $data = array(
            ['cache' => 'temp.gov_colombia_vaccines_allocations','prod' => 'prod.gov.canada.vaccines_allocations'],
            ['cache' => 'temp.gov_colombia_pcr_tests_municipal','prod' => 'prod.gov.canada.pcr_tests_municipal'],
        );
        $cacheupdater = new cacheUpdater;
        $vaccines = $cacheupdater->getCache($data[0]['cache'],'covid:gov-colombia');
        $pcrtest = $cacheupdater->getCache($data[1]['cache'],'covid:gov-colombia');

        $CacheSorter = new CacheSorter;
        $vaccines_allocations = $CacheSorter->gov_sorter_Colombia($vaccines);
        $pcr_tests_municipal = $CacheSorter->gov_sorter_Colombia($pcrtest);

        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[0]['prod'],$vaccines_allocations, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[1]['prod'],$pcr_tests_municipal, now()->addHour());

        return true;
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

        Cache::tags(['prod','prod.gov','prod.gov.colombia'])->put($data[0]['prod'],$bigdata, now()->addHours(6));
        return true;
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

        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[0]['prod'],$Germany_data, now()->addHour());
        return true;
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

        Cache::tags(['prod','prod.gov','prod.gov.canada'])->put($data[0]['prod'],$India_data, now()->addHour());
        return true;
    }

    static public function gov_updater_indo()
    {
        $data = array(
            ['cache' => 'temp.gov_indonesia.data','prod' => 'prod.gov.indonesia.data'],
            ['cache' => 'temp.gov_indonesia.update','prod' => 'prod.gov.indonesia.update'],
            ['cache' => 'temp.gov_indonesia.prev','prod' => 'prod.gov.indonesia.prev'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-indo');
        $temp_update = $cacheupdater->getCache($data[1]['cache'],'covid:gov-indo');
        $temp_prev = $cacheupdater->getCache($data[2]['cache'],'covid:gov-indo');

        $CacheSorter = new CacheSorter;
        $indo_data = $CacheSorter->gov_sorter_indo($temp_data);
        $indo_update = $CacheSorter->gov_sorter_indo($temp_update);
        $indo_prev = $CacheSorter->gov_sorter_indo($temp_prev);

        Cache::tags(['prod','prod.gov','prod.gov.indonesia'])->put($data[0]['prod'],$indo_data, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.indonesia'])->put($data[1]['prod'],$indo_update, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.indonesia'])->put($data[2]['prod'],$indo_prev, now()->addHour());
        return true;


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

        Cache::tags(['prod','prod.gov','prod.gov.israel'])->put($data[0]['prod'],$israel_data, now()->addHour());
        return true;
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

        Cache::tags(['prod','prod.gov','prod.gov.italy'])->put($data[0]['prod'],$italy_data, now()->addHour());
        return true;
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

        Cache::tags(['prod','prod.gov','prod.gov.newzealand'])->put($data[0]['prod'],$newzealand_data, now()->addHour());
        return true;
    }
    static public function gov_updater_Nigeria()
    {
        $data = array(
            ['cache' => 'temp.gov_nigeria','prod' => 'prod.gov.nigeria'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-nigeria');


        $CacheSorter = new CacheSorter;
        $newzealand_data = $CacheSorter->gov_sorter_nigeria($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.newzealand'])->put($data[0]['prod'],$newzealand_data, now()->addHour());
        return true;
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
        $temp_confirmed = $cacheupdater->getCache($data[0]['cache'],'covid:gov-southafrica');
        $temp_deaths = $cacheupdater->getCache($data[1]['cache'],'covid:gov-southafrica');
        $temp_recovered = $cacheupdater->getCache($data[2]['cache'],'covid:gov-southafrica');
        $temp_testing = $cacheupdater->getCache($data[3]['cache'],'covid:gov-southafrica');
        $temp_vaccination = $cacheupdater->getCache($data[4]['cache'],'covid:gov-southafrica');


        $CacheSorter = new CacheSorter;
        $southafrica_confirmed = $CacheSorter->gov_sorter_southafrica($temp_confirmed);
        $southafrica_deaths = $CacheSorter->gov_sorter_southafrica($temp_deaths);
        $southafrica_recovered = $CacheSorter->gov_sorter_southafrica($temp_recovered);
        $southafrica_testing = $CacheSorter->gov_sorter_southafrica($temp_testing);
        $southafrica_vaccination = $CacheSorter->gov_sorter_southafrica($temp_vaccination);
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

        Cache::tags(['prod','prod.gov','prod.gov.southafrica'])->put($data[0]['prod'],$southafrica_confirmed, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.southafrica'])->put($data[1]['prod'],$southafrica_deaths, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.southafrica'])->put($data[2]['prod'],$southafrica_recovered, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.southafrica'])->put($data[3]['prod'],$southafrica_testing, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.southafrica'])->put($data[4]['prod'],$southafrica_vaccination, now()->addHour());
        Cache::tags(['prod','prod.gov','prod.gov.southafrica'])->put($data[5]['prod'],$fullforms);

        return true;
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

        Cache::tags(['prod','prod.gov','prod.gov.southkorea'])->put($data[0]['prod'],$newzealand_data, now()->addHour());
        return true;
    }
    static public function gov_updater_switzerland()
    {
        $data = array(
            ['cache' => 'temp.gov_switzerland','prod' => 'prod.gov.switzerland'],
        );
        $cacheupdater = new cacheUpdater;
        $temp_data = $cacheupdater->getCache($data[0]['cache'],'covid:gov-switzerland');

        $CacheSorter = new CacheSorter;
        $newzealand_data = $CacheSorter->gov_sorter_switzerland($temp_data);

        Cache::tags(['prod','prod.gov','prod.gov.switzerland'])->put($data[0]['prod'],$newzealand_data, now()->addHour());
        return true;
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

        Cache::tags(['prod','prod.gov','prod.gov.UK'])->put($data[0]['prod'],$uk_data, now()->addHour());
        return true;
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

        Cache::tags(['prod','prod.gov','prod.gov.vietnam'])->put($data[0]['prod'],$stats, now()->addHour());

        return true;
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
}
