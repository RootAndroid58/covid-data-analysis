<?php

namespace App\Http\Helpers;

use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Models\Category;
use App\Models\Resource;
use App\Models\SubCategory;
use \SpreadsheetReader;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use ZanySoft\Zip\ZipManager;
use ZanySoft\Zip\ZipFacade as Zip;
use GuzzleHttp\Client;
use Carbon\Carbon;

class ScraperHelper
{
    /**
     * ====================================
     * Scrapper States Here
     * ====================================
     *
     * http://covidhelpnagpur.in/
     */

    static function Scrap_IN_MH_Nagpur()
    {
        $fields = array(
            'Service','Name','Contact','Comments'
        );
        $data = array();
        $data["country"] = "IN";
        $data["country_key"] = "code";
        $data["state"] = "MH";
        $data["state_key"] = "state_code";
        $data["city"] = "Nagpur";
        $data["city_key"] = "name";
        $data['path'] = "/INMHNagpur.csv";
        $data['fields'] = array();
        $data['fields'][0] = 'categary';
        $data['fields'][1] = 'name';
        $data['fields'][2] = 'phone_no';
        $data['fields'][3] = 'details';
        $data['hasHeader'] = true;
        $data['model'] = 'Resource';
        $data['modelRelationship'] = array();
        $data['modelRelationship'][0] = "Category";
        $data['website'] = "http://covidhelpnagpur.in/";

        $update = new ScraperHelper;
        $resp = $update->curlUrl("http://covidhelpnagpur.in/");

        $dom = HtmlDomParser::str_get_html($resp);

            $element = $dom->find('#pool > tr');

            $header = '';
            foreach ($fields as $value) {
                $header .= '"' . $value . '",';
            }
            $body = '';
            foreach($element as $node){
                foreach($node->find('td')->text() as $el){
                    $body .= '"'. str_replace(array("\n", "\r", "\t"), [' ',',',''],$el) . '",';
                }
                $body .=  "\n";
            }
            $csvfile = $header  . $body;
            try {
                Storage::disk('cron_temp')->put('INMHNagpur.csv', $csvfile);

                $data['status'] = $update->UpdateViaCSV('Resource',$data);

           } catch (\Exception $e) {

               throw $e;
           }
        return $data;
    }

    static public function COVID_worldometers()
    {
        $fields = array('index', 'country', 'cases', 'todayCases', 'deaths', 'todayDeaths', 'recovered', 'todayRecovered', 'active',
        'critical', 'casesPerOneMillion', 'deathsPerOneMillion', 'tests', 'testsPerOneMillion', 'population', 'continent', 'oneCasePerPeople', 'oneDeathPerPeople', 'oneTestPerPeople');
            $scraper_data = array();
            $scraper_data[] = array(
                'cache_key' => 'temp.worldometers.today',
                'path' => 'worldometers_today.csv',
                'hasHeader' => true,
                'website' => "https://www.worldometers.info/coronavirus/",
                'fields' => $fields,
            );
            $scraper_data[] = array(
                'cache_key' => 'temp.worldometers.yesterday',
                'path' => 'worldometers_yesterday.csv',
                'hasHeader' => true,
                'website' => "https://www.worldometers.info/coronavirus/",
                'fields' => $fields,
            );
            $scraper_data[]    = array(
                'cache_key' => 'temp.worldometers.yesterday2',
                'path' => 'worldometers_yesterday2.csv',
                'hasHeader' => true,
                'website' => "https://www.worldometers.info/coronavirus/",
                'fields' => $fields,
            );
        $update = new ScraperHelper;
        $resp = $update->curlUrl("https://www.worldometers.info/coronavirus/");
        $dom = HtmlDomParser::str_get_html($resp);
        // $usStates = HtmlDomParser::file_get_html("https://www.worldometers.info/coronavirus/country/us/");
        // $header_element = $dom->find('#main_table_countries_today > thead > tr');
        $header = '';
        foreach($fields as $node){
            $header .= '"'.str_replace(["\n",",",'&nbsp;'],['',"/","al "],$node). '",';
        }

        $data_element_today = $dom->findMulti('#main_table_countries_today > tbody:nth-child(2) > tr');
        $csvFile_today = $header . "\n";
        foreach($data_element_today as $node){
            $data = '';
            foreach($node->find('td')->text() as $td){
                $data .= '"'.$td . '",';
            }
            $data .= "\n";
            $csvFile_today .= $data;
        }

        $data_element_yesterday = $dom->findMulti('#main_table_countries_yesterday > tbody:nth-child(2) > tr');
        $csvFile_yestarday = $header . "\n";
        foreach($data_element_yesterday as $node){
            $data = '';
            foreach($node->find('td')->text() as $td){
                $data .= '"' . $td . '",';
            }
            $data .= "\n";
            $csvFile_yestarday .= $data;
        }

        $data_element_yesterday2 = $dom->findMulti('#main_table_countries_yesterday2 > tbody:nth-child(2) > tr');
        $csvFile_yestarday2 = $header . "\n";
        foreach($data_element_yesterday2 as $node){
            $data = '';
            foreach($node->find('td')->text() as $td){
                $data .= '"' . $td . '",';
            }
            $data .= "\n";
            $csvFile_yestarday2 .= $data;
        }
        try {
            Storage::disk('cron_temp')->put('worldometers_today.csv', $csvFile_today);
            Storage::disk('cron_temp')->put('worldometers_yesterday.csv', $csvFile_yestarday);
            Storage::disk('cron_temp')->put('worldometers_yesterday2.csv', $csvFile_yestarday2);
            $scraper = new ScraperHelper;
            foreach($scraper_data as $item){
                $arrays = $scraper->csvtoarray($item);
                Cache::forget($item['cache_key']);
                $success = Cache::tags(['temp','temp.worldometers'])->put($item['cache_key'], $arrays, now()->addMinutes(10));
                $values[] = $success ? true : false;
            }
            $scraper_data['success'] = $values;
        } catch (\Throwable $e) {
            throw $e;
        }
        $sort = new cacheUpdater;
        $sort->worldometer();
        $sort->COVID_worldometers_continent();
        $sort->COVID_worldometers_countries();

        return $scraper_data;
    }

    static public function COVID_worldometers_usa()
    {
        $fields = array('index', 'state', 'cases', 'todayCases', 'deaths', 'todayDeaths',
        'recovered', 'active', 'casesPerOneMillion', 'deathsPerOneMillion', 'tests',
        'testsPerOneMillion', 'population');
        $scraper_data = array();
        $scraper_data[] = array(
            'cache_key' => 'temp.worldometers.states.today',
            'path' => 'worldometers_states_today.csv',
            'hasHeader' => true,
            'website' => "https://www.worldometers.info/coronavirus/country/us/",
            'fields' => $fields,
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.worldometers.states.yesterday',
            'path' => 'worldometers_states_yesterday.csv',
            'hasHeader' => true,
            'website' => "https://www.worldometers.info/coronavirus/country/us/",
            'fields' => $fields,
        );
        $update = new ScraperHelper;
        $resp = $update->curlUrl("https://www.worldometers.info/coronavirus/country/us/");
        $dom = HtmlDomParser::str_get_html($resp);
        $header = '';
        foreach($fields as $node){
            $header .= '"'.str_replace(["\n",",",'&nbsp;'],['',"/","al "],$node). '",';
        }

        $data_element_today = $dom->findMulti('#usa_table_countries_today > tbody:nth-child(2) > tr');

        $csvFile_today = $header . "\n";
        foreach($data_element_today as $node){
            $data = '';
            foreach($node->find('td')->text() as $td){
                $data .= '"'.$td . '",';
            }
            $data .= "\n";
            $csvFile_today .= $data;
        }

        $data_element_yesterday = $dom->findMulti('#usa_table_countries_yesterday > tbody:nth-child(2) > tr');
        $csvFile_yestarday = $header . "\n";
        foreach($data_element_yesterday as $node){
            $data = '';
            foreach($node->find('td')->text() as $td){
                $data .= '"' . $td . '",';
            }
            $data .= "\n";
            $csvFile_yestarday .= $data;
        }

        try {
            Storage::disk('cron_temp')->put('worldometers_states_today.csv', $csvFile_today);
            Storage::disk('cron_temp')->put('worldometers_states_yesterday.csv', $csvFile_yestarday);
            $scraper = new ScraperHelper;
            foreach($scraper_data as $item){
                $arrays = $scraper->csvtoarray($item);
                Cache::forget($item['cache_key']);
                $success = Cache::tags(['temp','temp.worldometers.states'])->put($item['cache_key'], $arrays, now()->addMinutes(10));
                $values[] = $success ? true : false;
            }
            $scraper_data['success'] = $values;
        } catch (\Throwable $e) {
            throw $e;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->COVID_worldometers_usa();
        return $scraper_data;
    }

    static public function covid_historical()
    {
        $scraper_data = array();
        $scraper_data[] = array(
            'cache_key' => 'casesResponse_temp',
            'path' => 'hestorical_casesResponse.csv',
            'hasHeader' => true,
            'website' => "https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_confirmed_global.csv",
        );
        $scraper_data[] = array(
            'cache_key' => 'deathsResponse_temp',
            'path' => 'hestorical_deathsResponse.csv',
            'hasHeader' => true,
            'website' => "https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_deaths_global.csv",
        );
        $scraper_data[]    = array(
            'cache_key' => 'recoveredResponse_temp',
            'path' => 'hestorical_recoveredResponse.csv',
            'hasHeader' => true,
            'website' => "https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_time_series/time_series_covid19_recovered_global.csv",
        );

        foreach($scraper_data as $data){
            $update = new ScraperHelper;
            $resp = $update->curlUrl($data['website']);
            $dom = HtmlDomParser::str_get_html($resp);

            $csvfile = $dom->html();

            Storage::disk('cron_temp')->put($data['path'], $csvfile);


            $path = storage_path('cron_temp//'.$data['path']);
            $header = new SpreadsheetReader($path);

            foreach($header as $key => $row){
                if($key == 0){
                    $fields = $row;
                }else break;
            }
            $scraper = new ScraperHelper;

            $response = $scraper->csvtoarray(array('hasHeader'=> true , 'path'=> $data['path'], 'fields' => $fields));
            Cache::tags(['temp','temp.hestorical'])->put($data['cache_key'],$response, now()->addMinutes(10));
        }
        $sorted_data = cacheUpdater::historical();

        return true;

    }

    static public function Gov_Austria()
    {
        $scraper_data = array();
        $scraper_data[] = array(
            'path' => 'zips\\getAustria',
            'website' => 'https://covid19-dashboard.ages.at/data/data.zip',
            'type'  => 'zip',
            'Filename'  => 'getAustria.zip',
            'success'   => false,
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_austria_historical',
            'path' => 'zips\\getAustria\\CovidFaelle_Timeline_GKZ.csv',
            'hasHeader' => true,
            'fields'    => [
                'Time',
                'District',
                'GKZ',
                'population',
                'cases',
                'totalCases',
                'cases7days',
                '7daysIncidenceCases',
                'todayDeaths',
                'deaths',
                'todayRecovered',
                'recovered',
            ],
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_austria_by_age_grps',
            'path' => 'zips\\getAustria\\CovidFaelle_Altersgruppe.csv',
            'hasHeader' => true,
            'fields' => [
                'Age groupID',
                'Age group',
                'Federal state',
                'StateID',
                'population',
                'Gender',
                'cases',
                'recovered',
                'dead',
            ],
            'type'  => 'csv',
        );
        $scraper_data[]    = array(
            'cache_key' => 'temp.gov_austria_by_district',
            'path' => 'zips\\getAustria\\CovidFaelle_GKZ.csv',
            'hasHeader' => true,
            'fields' => [
                'District',
                'GKZ',
                'population',
                'cases',
                'dead',
                'cases7days',
            ],
            'type'  => 'csv',
        );
        $scraper_data[]    = array(
            'cache_key' => 'temp.gov_austria_hospital',
            'path' => 'zips\\getAustria\\CovidFallzahlen.csv',
            'hasHeader' => true,
            'fields' => [
                'date',
                'totalTests',
                'date_',
                'FZHosp',
                'FZICU',
                'FZHospFree',
                'FZICUFree',
                'StateID',
                'state',
            ],
            'type'  => 'csv',
        );
        $scraper_data[]    = array(
            'cache_key' => 'temp.gov_austria_version',
            'path' => 'zips\\getAustria\\Version.csv',
            'hasHeader' => true,
            'fields' => [
                'version','VersionsDate','CreationDate',
            ],
            'version' => 'V 2.4.0.0',
            'type'  => 'csv',
        );

        try {


            foreach($scraper_data as $data){
                if($data['type'] == 'zip'){
                    File::deleteDirectory(storage_path('cron_temp//'.$data['path']));
                    Storage::disk('cron_temp')->delete($data['Filename']);
                    $guzzle = new Client();
                    $response = $guzzle->get($data['website']);
                    Storage::disk('cron_temp')->put($data['Filename'], $response->getBody());
                    $path = storage_path('cron_temp//'.$data['Filename']);
                    $manager = new ZipManager();
                    $manager->addZip( Zip::open($path) );
                    $zip = Zip::open(storage_path('cron_temp//'.$data['Filename']));
                    $zip->extract(storage_path('cron_temp//'.$data['path']));
                    $scraper_data[0]['success'] = true;
                }
                if($scraper_data[0]['success'] && $data['type'] == 'csv'){
                    $scraper = new ScraperHelper;
                    $array =  $scraper->csvtoarray($data);
                    Cache::tags(['temp','temp.gov','temp.gov.Austria'])->put($data['cache_key'],$array, now()->addMinutes(10));
                }
                // dd($data);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        File::deleteDirectory(storage_path('cron_temp\\'.$scraper_data[0]['path']));
        File::delete(storage_path('cron_temp\\'.$scraper_data[0]['Filename']));

        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_Austria();


    }
    static public function Gov_Canada()
    {
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada',
            'path' => 'Canada.csv',
            'hasHeader' => true,
            'fields'    => [
                'ID','name','nameFR','date','update','casesConfirmed','casesProbable','deaths',
                'total','tested','tests','recover','percentrecover','ratetested','ratetests',
                'today','percentoday','ratetotal','ratedeaths','deathstoday','percentdeath',
                'testedtoday','teststoday','recoveredtoday','percentactive','active','rateactive',
                'total_last14','ratetotal_last14','deaths_last14','ratedeaths_last14','total_last7',
                'ratetotal_last7','deaths_last7','ratedeaths_last7','avgtotal_last7','avgincidence_last7',
                'avgdeaths_last7','avgratedeaths_last7'
            ],
            'website' => 'https://health-infobase.canada.ca/src/data/covidLive/covid19.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada_timeline_active',
            'path' => 'Canada_timeline_active.csv',
            'hasHeader' => true,
            'fields'    => [
                'province','date_active','cumulative_cases','cumulative_recovered','cumulative_deaths','active_cases','active_cases_change'
            ],
            'website' => 'https://raw.githubusercontent.com/ccodwg/Covid19Canada/master/timeseries_canada/active_timeseries_canada.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada_timeline_cases',
            'path' => 'Canada_timeline_cases.csv',
            'hasHeader' => true,
            'fields'    => [
                "province","date_report","cases","cumulative_cases"
            ],
            'website' => 'https://raw.githubusercontent.com/ccodwg/Covid19Canada/master/timeseries_canada/cases_timeseries_canada.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada_timeline_deaths',
            'path' => 'Canada_timeline_deaths.csv',
            'hasHeader' => true,
            'fields'    => [
                "province","date_death_report","deaths","cumulative_deaths"
            ],
            'website' => 'https://raw.githubusercontent.com/ccodwg/Covid19Canada/master/timeseries_canada/mortality_timeseries_canada.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada_timeline_recovered',
            'path' => 'Canada_timeline_recovered.csv',
            'hasHeader' => true,
            'fields'    => [
                "province","date_recovered","recovered","cumulative_recovered"
            ],
            'website' => 'https://raw.githubusercontent.com/ccodwg/Covid19Canada/master/timeseries_canada/recovered_timeseries_canada.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada_timeline_testing',
            'path' => 'Canada_timeline_testing.csv',
            'hasHeader' => true,
            'fields'    => [
                "province","date_testing","testing","cumulative_testing","testing_info"
            ],
            'website' => 'https://raw.githubusercontent.com/ccodwg/Covid19Canada/master/timeseries_canada/testing_timeseries_canada.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada_timeline_vaccine_administration',
            'path' => 'Canada_timeline_vaccine_administration.csv',
            'hasHeader' => true,
            'fields'    => [
                "province","date_vaccine_administered","avaccine","cumulative_avaccine"
            ],
            'website' => 'https://raw.githubusercontent.com/ccodwg/Covid19Canada/master/timeseries_canada/vaccine_administration_timeseries_canada.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada_timeline_vaccine_completion',
            'path' => 'Canada_timeline_vaccine_completion.csv',
            'hasHeader' => true,
            'fields'    => [
                "province","date_vaccine_completed","cvaccine","cumulative_cvaccine"
            ],
            'website' => 'https://raw.githubusercontent.com/ccodwg/Covid19Canada/master/timeseries_canada/vaccine_completion_timeseries_canada.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_canada_timeline_vaccine_distribution',
            'path' => 'Canada_timeline_vaccine_distribution.csv',
            'hasHeader' => true,
            'fields'    => [
                "province","date_vaccine_distributed","dvaccine","cumulative_dvaccine"
            ],
            'website' => 'https://raw.githubusercontent.com/ccodwg/Covid19Canada/master/timeseries_canada/vaccine_distribution_timeseries_canada.csv',
            'type'  => 'csv',
        );

        $scraper = new ScraperHelper;
        try {
            foreach($scraper_data as $data){

                $resp = $scraper->curlUrl($data['website']);
                $dom = HtmlDomParser::str_get_html($resp);

                $csvfile = $dom->html();

                Storage::disk('cron_temp')->put($data['path'], $csvfile);

                $array =  $scraper->csvtoarray($data);
                Cache::tags(['temp','temp.gov','temp.gov.Canada'])->put($data['cache_key'],$array, now()->addMinutes(10));
            }

        } catch (\Throwable $th) {
            throw $th;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_canada();
        $cacheUpdater->gov_updater_canada_timeline();

    }

    static public function Gov_Colombia()
    {

        $scraper_data[] = array(
            'cache_key' => 'temp.gov_colombia_vaccines_allocations',
            'path'      => 'Colombia_vaccines_allocations.csv',
            'hasHeader' => true,
            'fields'    => [
                'resolution',
                'resolution_date',
                'territory_code',
                'territory_name',
                'vaccine_lab',
                'amount',
                'use_vaccine',
                'cut_date',
            ],
            'website' => 'https://www.datos.gov.co/resource/sdvb-4x4j.csv',
            'website_dataset' => 'https://www.datos.gov.co/w/fnzt-ptjk/dneh-mcp2?cur=HK1Q2y0RRFI&from=root',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_colombia_pcr_tests_municipal',
            'path'      => 'Colombia_pcr_tests_municipal.csv',
            'hasHeader' => true,
            'fields'    => [
                'Department',
                'municipality',
                'municipal_code',
                'total_processed',
            ],
            'website' => 'https://www.datos.gov.co/resource/jrb3-mnpr.csv',
            'website_dataset' => 'https://www.datos.gov.co/w/fnzt-ptjk/dneh-mcp2?cur=HK1Q2y0RRFI&from=root',
            'type'  => 'csv',
        );
        // location for other datasets https://www.datos.gov.co/w/fnzt-ptjk/dneh-mcp2?cur=HK1Q2y0RRFI&from=root

        try {
            $scraper = new ScraperHelper;
            foreach($scraper_data as $data){

                $resp = $scraper->curlUrl($data['website']);
                $dom = HtmlDomParser::str_get_html($resp);

                $csvfile = $dom->html();

                Storage::disk('cron_temp')->put($data['path'], $csvfile);

                $array =  $scraper->csvtoarray($data);
                Cache::tags(['temp','temp.gov','temp.gov.Colombia'])->put($data['cache_key'],$array, now()->addMinutes(10));
                // dd(count($array));
            }

        } catch (\Throwable $th) {
            throw $th;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_colombia();


    }

    static public function Gov_Colombia_bigdata()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_colombia_bigdata',
            'path' => 'Colombia_bigdata.csv',
            'hasHeader' => true,
            'fields'    => [
                'date_reported','id','date','department_nom','department','city_municipality_nom','city_municipality',
                'age','unit_measured','sex','source_type_contagion','location','condition','travel_country_1_cod',
                'travel_country_1_nom','current_condition','start_date_symptoms','death_date','diagnostic_date','recovered_date',
                'recovery_type','per_etn_','group_name',
            ],
            'website' => 'https://www.datos.gov.co/api/views/gt2j-8ykr/rows.csv',
            'type'  => 'csv',
        );
        if($temp = Cache::get($scraper_data['cache_key']) !== null){
            Cache::tags(['temp','temp.gov','temp.gov.Colombia','temp.bigdata','temp.bigdata.old'])->put($scraper_data['cache_key'].'.old',$temp);
        }

        try {
            $scraper = new ScraperHelper;

            $resp = $scraper->curlUrl($scraper_data['website']);
            $dom = HtmlDomParser::str_get_html($resp);

            $csvfile = $dom->html();

            Storage::disk('cron_temp')->put($scraper_data['path'], $csvfile);

            $array =  $scraper->csvtoarray($scraper_data);

            Cache::tags(['temp','temp.gov','temp.gov.Colombia','temp.bigdata'])->put($scraper_data['cache_key'],array_chunk($array,5000), now()->addHours(6));


        } catch (\Throwable $th) {
            throw $th;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_colombia_bigdata();
    }

    static public function Gov_Germany()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_germany',
            'path' => 'Germany.csv',
            'hasHeader' => true,
            'fields'    => [
                'state','newCases','cases_last_7','incidence_7_days','deaths'
            ],
            'website' => 'https://www.rki.de/DE/Content/InfAZ/N/Neuartiges_Coronavirus/Fallzahlen.html',
            'type'  => 'html',
        );

        $scraper = new ScraperHelper;
        try {
            $resp = $scraper->curlUrl($scraper_data['website']);
            $dom = HtmlDomParser::str_get_html($resp);

            $table = $dom->find('table > tbody');
            $data = '';
            $header_ = '';
            foreach($scraper_data['fields'] as $header){
                $header_ .= '"'.$header . '",';
            }

            foreach($table->find('tr') as $tr){
                foreach($tr->find('td')->text() as $td){
                    $data .= '"'.$td . '",';
                }
                $data .= "\n";
            }
            $csvfile = $header_ . "\n" . $data;

            Storage::disk('cron_temp')->put($scraper_data['path'], $csvfile);

            $array =  $scraper->csvtoarray($scraper_data);

            Cache::tags(['temp','temp.gov','temp.gov.germany'])->put($scraper_data['cache_key'],$array, now()->addMinutes(10));
        } catch (\Throwable $th) {
            throw $th;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_germany();

    }
    static public function Gov_India()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_india',
            'fields'    => [
                'sno','state_name','active','positive','cured','death','new_active','new_positive','new_cured','new_death','state_code'
            ],
            'website' => 'https://www.mohfw.gov.in/data/datanew.json',
            'type'  => 'json',
        );

        try {
            $scraper = new ScraperHelper;
            $resp = $scraper->curlUrl($scraper_data['website']);
            $data = json_decode($resp);

            Cache::tags(['temp','temp.gov','temp.gov.india'])->put($scraper_data['cache_key'],$data, now()->addMinutes(10));
        } catch (\Throwable $th) {
            throw $th;
        }

        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_india();

    }

    static public function Gov_Israel()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_israel',
            'website' => 'https://datadashboardapi.health.gov.il/api/queries/_batch',
            'method'   => "POST",
            'param'     => "{\"requests\":[{\"id\":\"0\",\"queryName\":\"lastUpdate\",\"single\":true,\"parameters\":{}},{\"id\":\"1\",\"queryName\":\"infectedPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"2\",\"queryName\":\"updatedPatientsOverallStatus\",\"single\":false,\"parameters\":{}},{\"id\":\"3\",\"queryName\":\"sickPerDateTwoDays\",\"single\":false,\"parameters\":{}},{\"id\":\"4\",\"queryName\":\"sickPerLocation\",\"single\":false,\"parameters\":{}},{\"id\":\"5\",\"queryName\":\"patientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"6\",\"queryName\":\"deadPatientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"7\",\"queryName\":\"recoveredPerDay\",\"single\":false,\"parameters\":{}},{\"id\":\"8\",\"queryName\":\"testResultsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"9\",\"queryName\":\"infectedPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"10\",\"queryName\":\"patientsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"11\",\"queryName\":\"doublingRate\",\"single\":false,\"parameters\":{}},{\"id\":\"12\",\"queryName\":\"infectedByAgeAndGenderPublic\",\"single\":false,\"parameters\":{\"ageSections\":[0,10,20,30,40,50,60,70,80,90]}},{\"id\":\"13\",\"queryName\":\"isolatedDoctorsAndNurses\",\"single\":true,\"parameters\":{}},{\"id\":\"14\",\"queryName\":\"testResultsPerDate\",\"single\":false,\"parameters\":{}},{\"id\":\"15\",\"queryName\":\"contagionDataPerCityPublic\",\"single\":false,\"parameters\":{}},{\"id\":\"16\",\"queryName\":\"hospitalStatus\",\"single\":false,\"parameters\":{}}]}",
            'headers'   => [
                'Accept: application/json',
                'Content-Type: application/json',
            ],
        );
        $scraper = new ScraperHelper;
        $resp = $scraper->curlPOSTUrl($scraper_data['website'],$scraper_data['headers'],$scraper_data['param']);

        $resp = json_decode($resp);

        Cache::tags(['temp','temp.gov','temp.gov.israel'])->put($scraper_data['cache_key'],$resp, now()->addMinutes(10));

        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_israel();
    }

    static public function Gov_Indonesia()
    {
        $scraper_data = array();
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_indonesia.data',
            'website' => 'https://data.covid19.go.id/public/api/data.json',
            'type'  => 'json',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_indonesia.update',
            'website' => 'https://data.covid19.go.id/public/api/update.json',
            'type'  => 'json',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_indonesia.prev',
            'website' => 'https://data.covid19.go.id/public/api/prov.json',
            'type'  => 'json',
        );

        $scraper = new ScraperHelper;
        try {
            foreach($scraper_data as $data){
                $resp = $scraper->curlUrl($data['website']);
                $array = json_decode($resp);

                Cache::tags(['temp','temp.gov','temp.gov.indonesia'])->put($data['cache_key'],$array, now()->addMinutes(10));
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_indo();
    }

    static public function Gov_Italy()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_italy',
            'path' => 'Italy.csv',
            'hasHeader' => true,
            'fields'    => [
                'date', 'state', 'region_code', 'denomination_region', 'lat', 'long', 'hospitalized_with_symptoms',
                'intensive_care', 'total_hospitalized', 'home_insulation', 'total_positive', 'total_positive_variation',
                'new_positives', 'resigned_healed', 'deceased', 'cases_from_suspected_diagnostic', 'cases_from_screening',
                'total_cases', 'tampons', 'cases_tested', 'Note', 'intensive_therapy_inputs', 'note_test', 'note_cases',
                'total_positive_molecular_test', 'total_positive_test_antigenic_rapid', 'buffer_test_molecular',
                'swabs_test_antigenic_rapid', 'code_nuts_1', 'code_nuts_2',
            ],
            'website' => 'https://raw.githubusercontent.com/pcm-dpc/COVID-19/master/dati-regioni/dpc-covid19-ita-regioni-latest.csv',
            'type'  => 'csv',
        );

        try {
            $scraper = new ScraperHelper;

            $resp = $scraper->curlUrl($scraper_data['website']);
            $dom = HtmlDomParser::str_get_html($resp);

            $csvfile = $dom->html();

            Storage::disk('cron_temp')->put($scraper_data['path'], $csvfile);

            $array =  $scraper->csvtoarray($scraper_data);
            Cache::tags(['temp','temp.gov','temp.gov.Italy'])->put($scraper_data['cache_key'],$array, now()->addMinutes(10));
            // dd($array);

        } catch (\Throwable $th) {
            throw $th;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_italy();

    }

    static public function Gov_NewZealand()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_newzealand',
            'path' => 'NewZealand.csv',
            'hasHeader' => true,
            'fields'    => [
                'province', 'active', 'recovered', 'deaths', 'cases', '_'
            ],
            'website' => 'https://www.health.govt.nz/our-work/diseases-and-conditions/covid-19-novel-coronavirus/covid-19-data-and-statistics/covid-19-current-cases',
            'type'  => 'html',
        );

        $scraper = new ScraperHelper;
        $resp = $scraper->curlUrl($scraper_data['website']);
        $dom = HtmlDomParser::str_get_html($resp);

        $table = $dom->findMulti('table'); // [6]
        $header = '';
        foreach($scraper_data['fields'] as $headers){
            $header .= '"'.$headers . '",';
        }

        $data = '';
        foreach($table[6]->find('tbody > tr') as $node){
            foreach($node->find('td')->text() as $td){
                $data .= '"'.$td . '",';
            }
            $data .= "\n";
        }
        $csvfile = $header . "\n" . $data;

        Storage::disk('cron_temp')->put($scraper_data['path'], $csvfile);

        $array =  $scraper->csvtoarray($scraper_data);
        Cache::tags(['temp','temp.gov','temp.gov.newzealand'])->put($scraper_data['cache_key'],$array, now()->addMinutes(10));
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_NewZealand();
    }

    static public function Gov_Nigeria()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_nigeria',
            'path' => 'Nigeria.csv',
            'hasHeader' => true,
            'fields'    => [
                'state', 'cases', 'active', 'recovered', 'deaths'
            ],
            'website' => 'https://covid19.ncdc.gov.ng/report/',
            'type'  => 'html',
        );

        $header = '';
        foreach($scraper_data['fields'] as $headers){
            $header .= '"'.$headers . '",';
        }



        $scraper = new ScraperHelper;
        $resp = $scraper->curlUrl($scraper_data['website']);
        $dom = HtmlDomParser::str_get_html($resp);

        $table = $dom->find('#custom1 > tbody');
        $data = '';
        foreach($table->find('tr') as $node){
            foreach($node->find('td')->text() as $td){
                $data .= '"'.$td . '",';
            }
            $data .= "\n";
        }

        $csvfile = $header . "\n" . $data;

        Storage::disk('cron_temp')->put($scraper_data['path'], $csvfile);

        $array =  $scraper->csvtoarray($scraper_data);
        Cache::tags(['temp','temp.gov','temp.gov.nigeria'])->put($scraper_data['cache_key'],$array, now()->addMinutes(10));

        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_Nigeria();
    }

    static public function Gov_SouthAfrica()
    {
        $scraper_data = array();
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_south_africa_confirmed',
            'path' => 'SouthAfrica_confirmed.csv',
            'hasHeader' => true,
            'fields'    => [
                'date','YYYYMMDD','EC','FS','GP','KZN','LP','MP','NC','NW','WC','UNKNOWN','total','source'
            ],
            'website' => 'https://raw.githubusercontent.com/dsfsi/covid19za/master/data/covid19za_provincial_cumulative_timeline_confirmed.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_south_africa_deaths',
            'path' => 'SouthAfrica_deaths.csv',
            'hasHeader' => true,
            'fields'    => [
                'date','YYYYMMDD','EC','FS','GP','KZN','LP','MP','NC','NW','WC','UNKNOWN','total','source'
            ],
            'website' => 'https://raw.githubusercontent.com/dsfsi/covid19za/master/data/covid19za_provincial_cumulative_timeline_deaths.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_south_africa_recovered',
            'path' => 'SouthAfrica_recovered.csv',
            'hasHeader' => true,
            'fields'    => [
                'date','YYYYMMDD','EC','FS','GP','KZN','LP','MP','NC','NW','WC','UNKNOWN','total','source',
            ], // 'Eastern_Cape','Free_State','Gauteng','KwaZulu-Natal','Limpopo','Mpumalanga','North_West','Northern_Cape','Western_Cape'
            'website' => 'https://raw.githubusercontent.com/dsfsi/covid19za/master/data/covid19za_provincial_cumulative_timeline_recoveries.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_south_africa_testing',
            'path' => 'SouthAfrica_recovered.csv',
            'hasHeader' => true,
            'fields'    => [
                'date','YYYYMMDD','cumulative_tests','cumulative_tests_private','cumulative_tests_public','recovered',
                'hospitalisation','critical_icu','ventilation','deaths','contacts_identified','contacts_traced',
                'scanned_travellers','passengers_elevated_temperature','covid_suspected_criteria','source'
            ],
            'website' => 'https://raw.githubusercontent.com/dsfsi/covid19za/master/data/covid19za_timeline_testing.csv',
            'type'  => 'csv',
        );
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_south_africa_vaccination',
            'path' => 'SouthAfrica_recovered.csv',
            'hasHeader' => true,
            'fields'    => [
                'date','YYYYMMDD','vaccinations_all','vaccinations_phase_2','vaccinations','source'
            ],
            'website' => 'https://raw.githubusercontent.com/dsfsi/covid19za/master/data/covid19za_timeline_vaccination.csv',
            'type'  => 'csv',
        );

        try {

            $scraper = new ScraperHelper;
            foreach($scraper_data as $data){

                $resp = $scraper->curlUrl($data['website']);
                $dom = HtmlDomParser::str_get_html($resp);

                $csvfile = $dom->html();

                Storage::disk('cron_temp')->put($data['path'], $csvfile);

                $array =  $scraper->csvtoarray($data);
                Cache::tags(['temp','temp.gov','temp.gov.south_africa'])->put($data['cache_key'],$array, now()->addMinutes(10));
            }

            $cacheUpdater = new cacheUpdater;
            $cacheUpdater->gov_updater_southafrica();


        } catch (\Throwable $th) {
            throw $th;
        }


    }

    static public function Gov_SouthKorea()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_south_korea',
            'path' => 'south_korea.csv',
            'hasHeader' => true,
            'fields'    => [
                'city', 'todayCases', 'importedCasesToday', 'localCasesToday', 'cases', 'isolated', 'recovered', 'deaths', 'incidence'
            ],
            'website' => 'http://ncov.mohw.go.kr/en/bdBoardList.do?brdId=16&brdGubun=162&dataGubun=&ncvContSeq=&contSeq=&board_id=&gubun=',
            'type'  => 'html',
        );

        $scraper = new ScraperHelper;
        $resp = $scraper->curlUrl($scraper_data['website']);
        $dom = HtmlDomParser::str_get_html($resp);

        $table = $dom->find('table > tbody');
        $head = '';
        foreach($scraper_data['fields'] as $header){
            $head .= '"'. $header . '",';
        }
        $data = '';
        foreach($table->find('tr') as $node){
            foreach($node->find('th')->text() as $th){
                $data .= '"'.$th . '",';
            }
            foreach($node->find('td')->text() as $td){
                $data .= '"'.$td . '",';
            }
            $data .= "\n";
        }
        $csvfile = $head . "\n" . $data;

        Storage::disk('cron_temp')->put($scraper_data['path'], $csvfile);

        $array =  $scraper->csvtoarray($scraper_data);
        Cache::tags(['temp','temp.gov','temp.gov.south_korea'])->put($scraper_data['cache_key'],$array, now()->addMinutes(10));
        // dd($array);
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_southkorea();

    }

    static public function Gov_Switzerland()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_switzerland',
            'path' => 'Switzerland.csv',
            'hasHeader' => true,
            'fields'    => [
                'date','time','state_code','tested','ncumul_conf','new_hosp',
                'current_hosp','current_icu','current_vent','released','ncumul_deceased','source',
                'current_isolated','current_quarantined','current_quarantined_riskareatravel'
            ],
            'website' => 'https://raw.githubusercontent.com/openZH/covid_19/master/COVID19_Fallzahlen_CH_total_v2.csv',
            'type'  => 'csv',
        );
        try {
            $scraper = new ScraperHelper;

            $resp = $scraper->curlUrl($scraper_data['website']);
            $dom = HtmlDomParser::str_get_html($resp);

            $csvfile = $dom->html();

            Storage::disk('cron_temp')->put($scraper_data['path'], $csvfile);

            $array =  $scraper->csvtoarray($scraper_data);

            $array = array_chunk($array,5000);
            Cache::tags(['temp','temp.gov','temp.gov.switzerland'])->put($scraper_data['cache_key'],$array, now()->addMinutes(10));

        } catch (\Throwable $th) {
            throw $th;
        }

        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_switzerland();

    }

    static public function Gov_UK()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_UK',
            'website' => 'https://api.coronavirus.data.gov.uk/v1/data?filters=areaName=United%20Kingdom;areaType=overview&structure={%22date%22:%22date%22,%22todayTests%22:%22newTestsByPublishDate%22,%22tests%22:%22cumTestsByPublishDate%22,%22testCapacity%22:%22plannedCapacityByPublishDate%22,%22newCases%22:%22newCasesByPublishDate%22,%22cases%22:%22cumCasesByPublishDate%22,%22hospitalized%22:%22hospitalCases%22,%22usedVentilationBeds%22:%22covidOccupiedMVBeds%22,%22newAdmissions%22:%22newAdmissions%22,%22admissions%22:%22cumAdmissions%22,%22todayDeaths%22:%22newDeaths28DaysByPublishDate%22,%22totalDeaths%22:%22cumDeaths28DaysByPublishDate%22,%22ONSweeklyDeaths%22:%22newOnsDeathsByRegistrationDate%22,%22ONStotalDeaths%22:%22cumOnsDeathsByRegistrationDate%22}',
            'type'  => 'json',
            'home' => 'https://api.coronavirus.data.gov.uk',
        );

        try {
            $client = new Client();
            $response = $client->get($scraper_data['website']);
            $response = json_decode($response->getBody()->getContents());
            $dataWithPages = array($response);

            if($response->pagination->next !== null) $nextPage = true;
            else $nextPage = false;
            while ($nextPage) {
                $response = $client->get($scraper_data['home'].$response->pagination->next);
                $response = json_decode($response->getBody()->getContents());
                if($response !== null){
                    $dataWithPages = array_merge($dataWithPages,$response);
                }
                if( !isset($response->pagination) || $response->pagination->next == null) $nextPage = false;
            }

            Cache::tags(['temp','temp.gov','temp.gov.UK'])->put($scraper_data['cache_key'],$dataWithPages, now()->addMinutes(10));

        } catch (\Throwable $th) {
            throw $th;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_UK();

    }

    static public function Gov_Vietnam()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_vietnam',
            'path' => 'vietnam.csv',
            'hasHeader' => true,
            'fields'    => [
                'Patient', 'Age', 'Location' ,'Status' ,'Nationality'
            ],
            'website' => 'https://ncov.moh.gov.vn/',
            'type'  => 'html',
        );

        try {

            $scraper = new ScraperHelper;
            $resp = $scraper->curlUrl($scraper_data['website'],null,true);
            $dom = HtmlDomParser::str_get_html($resp);

            $deaths = $dom->findOne('#portlet_corona_trangchu_top_CoronaTrangchuTopPortlet_INSTANCE_RrVAbIFIPL7v > div > div.portlet-content-container > div > section.container > div:nth-child(2) > div:nth-child(2) > div > div.col-lg-12.d-none.d-lg-block > div > div:nth-child(2) > div.col.text-center.text-uppercase.text-danger-new1 > span')->text();
            $treated = $dom->findOne('#portlet_corona_trangchu_top_CoronaTrangchuTopPortlet_INSTANCE_RrVAbIFIPL7v > div > div.portlet-content-container > div > section.container > div:nth-child(2) > div:nth-child(2) > div > div.col-lg-12.d-none.d-lg-block > div > div:nth-child(2) > div.col.text-center.text-uppercase.text-warning1 > span')->text();
            $recovered = $dom->findOne('#portlet_corona_trangchu_top_CoronaTrangchuTopPortlet_INSTANCE_RrVAbIFIPL7v > div > div.portlet-content-container > div > section.container > div:nth-child(2) > div:nth-child(2) > div > div.col-lg-12.d-none.d-lg-block > div > div:nth-child(2) > div.col.text-center.text-uppercase.text-success > span')->text();
            $infected = $dom->findOne('#portlet_corona_trangchu_top_CoronaTrangchuTopPortlet_INSTANCE_RrVAbIFIPL7v > div > div.portlet-content-container > div > section.container > div:nth-child(2) > div:nth-child(2) > div > div.col-lg-12.d-none.d-lg-block > div > div:nth-child(2) > div.col.text-center.text-uppercase.text-danger-new > span')->text();

            $data = array(
                'updatedAt' => Carbon::now()->timestamp,
                'deaths' => str_replace('.',',',$deaths),
                'treated' => str_replace('.',',',$treated),
                'recovered' => str_replace('.',',',$recovered),
                'infected' => str_replace('.',',',$infected),

            );

            Cache::tags(['temp','temp.gov','temp.gov.vietnam'])->put($scraper_data['cache_key'],$data, now()->addMinutes(10));

        } catch (\Throwable $th) {
            throw $th;
        }
        $cacheUpdater = new cacheUpdater;
        $cacheUpdater->gov_updater_vietnam();

    }


    /**
     * ===========================================
     * Scrapper Helper Function Starts here
     * ===========================================
     */
    static public function UpdateViaCSV($model,$data)
    {
        try {
            $filename = $data['path'];
            $path     = storage_path('cron_temp//' . $filename);

            $hasHeader = $data['hasHeader'];

            $fields = $data['fields'];
            $fields = array_flip(array_filter($fields));

            $modelName = $data['model'];
            $model     = 'App\\Models\\' . $modelName;

            $reader = new SpreadsheetReader($path);
            $insert = [];

            $success = array();


            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {
                        $tmp[$header] = trim($row[$k],",");
                    }
                }


                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }

            $for_insert = array_chunk($insert, 100);

            foreach ($for_insert as $insert_item) {

                $scraper = new ScraperHelper;
                $success[] = $scraper->updateorinsert($model,$insert_item,$data);
            }

            $rows  = count($insert);
            $table = Str::plural($modelName);
            $update = 0;
            $insert = 0;
            $sus = array();
            foreach($success as $item){
                $update = $update + $item['updates'];
                $insert = $insert + $item['inserts'];
                $sus[] = $item['success'];
            }

            File::delete($path);

            return array("success"=>$sus, 'rows' => $rows, 'table' => $table , "updates" => $update , 'new_data' => $insert);

        } catch (\Exception $ex) {

            throw $ex;
        }
    }

    static public function csvtoarray($data)
    {
        $hasHeader = $data['hasHeader'];
        $filename = $data['path'];
        // $path     = Storage::disk('cron_temp')->path($filename);;
        $path     = storage_path('cron_temp//' . $filename);
        $fields = $data['fields'];
        $fields = array_flip(array_filter($fields));
        try {
            $reader = new SpreadsheetReader($path);
            $insert = [];

            foreach ($reader as $key => $row) {
                if ($hasHeader && $key == 0) {
                    continue;
                }

                $tmp = [];
                foreach ($fields as $header => $k) {

                    if (isset($row[$k])) {
                        $tmp[$header] = trim($row[$k],",");
                    }
                }


                if (count($tmp) > 0) {
                    $insert[] = $tmp;
                }
            }
            File::delete($path);
            return $insert;

        } catch (\Throwable $th) {
            // throw $th;
        }
        return array();

    }

    public function getIDofALL($data)
    {

        $country = Country::where($data['country_key'],$data['country'])->first();
        $state = State::where($data['state_key'],$data['state'])->where('country_code',$country->code)->first();
        $city = City::where($data['city_key'],$data['city'])->where('state_code',$state->state_code)->where('country_code',$country->code)->first();

        return array('city' => $city, 'state' => $state, 'country' => $country);
    }

    public function getCategory($data)
    {
        $category = Category::where('category_name',$data)->first();
        if($category == null){
            $sub_category = SubCategory::with('category')->where('name',$data)->first();

            if($sub_category == null){
                $sub_category = SubCategory::create([
                    'name' => "$data",
                    'category_id' => 0,
                ]);
            }else{
                $category =$sub_category->category;
            }
        }else{
            $sub_category = null;
        }

        return array('category'=> $category , 'sub_category' => $sub_category);
    }

    public function updateorinsert($model,  $insert_item,$data)
    {
        $new_updates = 0;
        $new_data = 0;
        foreach($insert_item as $item){
            $categary = isset($item['categary']) ? $item['categary'] : null;
            $name = isset($item['name']) ? $item['name']: null;
            $phone_no = isset($item['phone_no']) ? $item['phone_no'] : null;
            $details = isset($item['details']) ?$item['details'] : null ;
            $url = isset($item['url']) ?$item['url'] : null ;
            $note = isset($item['note']) ?$item['note'] : null ;
            $address = isset($item['address']) ?$item['address'] : null ;
            $email = isset($item['email']) ?$item['email'] : null ;

            if($phone_no == null || $name == null || $categary == null) continue;
            if(filter_var($phone_no, FILTER_VALIDATE_URL)){
                continue;
            }
            $scraper = new ScraperHelper;
            $get_category_info = $scraper->getCategory($categary);
            $categary_id = $get_category_info['category'];
            $subcategory_id = $get_category_info['sub_category'];

            $location = $scraper->getIDofALL($data);

            if(!isset($location['city']) ){
                Log::debug("There is no city for ".$data['website']." #pool id ");
                continue;
            }
            if(!isset($location['state'])) {
                Log::debug("There is no state for ".$data['website']." #pool id ");
                continue;
            }
            if(!isset($location['country'])) {
                Log::debug("There is no country for ".$data['website']." #pool id ");
                continue;
            }
            $updatedata = $model::updateOrCreate(
                [
                    'name' => $name,
                    'country_id' => $location['country']->id,
                    'state_id' => $location['state']->id,
                    'city_id' => $location['city']->id,
                ],
                [
                    'phone_no' => $phone_no,
                    'details' => $details,
                    'url' => $url,
                    'note' => $note,
                    'address' => $address,
                    'email' => $email,
                ]
                );

            if($categary_id != null){
                $updatedata->categories()->sync($categary_id['id']);
            }else{
                $updatedata->categories()->sync(0);
            }
            if($subcategory_id != null){
                $updatedata->subcats()->sync($subcategory_id['id']);
            }
            if ($updatedata->wasRecentlyCreated) {
                $new_data ++;
            } else {
                if ($updatedata->wasChanged()) {
                    $new_updates ++;
                } else {
                    // model has NOT been assigned new values to one of its attributes and saved as is
                }

            }
        }
        return array('updates'=> $new_updates,'inserts' => $new_data , 'success' => true);
    }

    public function curlUrl($site,$type = null,$ssl = false)
    {
        $curl = curl_init($site);
        curl_setopt($curl, CURLOPT_URL, $site);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        if($type == 'json'){
            $headers = array(
                'Accept: application/json',
            );
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.1.2) Gecko/20090729 Firefox/3.5.2 GTB5');
        }
        if($ssl){
            curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
        }
        // //for debug only!
        // curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        // curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

        $resp = curl_exec($curl);
        curl_close($curl);
        return $resp;
    }
    public function curlPOSTUrl($site,$headers,$postfields)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $site);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);

        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

        $resp = curl_exec($ch);
        // if (curl_errno($ch)) {
        //     echo 'Error:' . curl_error($ch);
        // }
        curl_close($ch);
        return $resp;
    }
}
