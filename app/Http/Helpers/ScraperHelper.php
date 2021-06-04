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

    static public function covid_hestorical()
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
            'version' => 'V 2.4.0.0',
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
            'version' => 'V 2.4.0.0',
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
            'version' => 'V 2.4.0.0',
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
            'version' => 'V 2.4.0.0',
            'type'  => 'csv',
        );
        $scraper_data[]    = array(
            'cache_key' => 'gov_austria_version',
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
                    File::deleteDirectory(storage_path('cron_temp\\'.$data['path']));
                    Storage::disk('cron_temp')->delete($data['Filename']);
                    $guzzle = new Client();
                    $response = $guzzle->get($data['website']);
                    Storage::disk('cron_temp')->put($data['Filename'], $response->getBody());
                    $path = storage_path('cron_temp\\'.$data['Filename']);
                    $manager = new ZipManager();
                    $manager->addZip( Zip::open($path) );
                    $zip = Zip::open(storage_path('cron_temp\\'.$data['Filename']));
                    $zip->extract(storage_path('cron_temp\\'.$data['path']));
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


    }
    static public function Gov_Canada()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_canada',
            'path' => 'Canada.csv',
            'hasHeader' => true,
            'fields'    => [
                'ID','name','nameFR','date','update','caeseConfirmed','casesProbable','deaths',
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

        try {
            $scraper = new ScraperHelper;
            $resp = $scraper->curlUrl($scraper_data['website']);
            $dom = HtmlDomParser::str_get_html($resp);

            $csvfile = $dom->html();

            Storage::disk('cron_temp')->put($scraper_data['path'], $csvfile);

            $array =  $scraper->csvtoarray($scraper_data);
            Cache::tags(['temp','temp.gov','temp.gov.Canada'])->put($scraper_data['cache_key'],$array, now()->addMinutes(10));
            dd($array);

        } catch (\Throwable $th) {
            throw $th;
        }


    }

    static public function Gov_Colombia()
    {
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_colombia_positive_cases',
            'path' => 'Colombia_positive_cases.csv',
            'hasHeader' => true,
            'fields'    => [
                'date_reported','id','date','department_nom','department','city_municipality_nom','city_municipality',
                'age','unit_measured','sex','source_type_contagion','location','condition','travel_country_1_cod',
                'travel_country_1_nom','recovered','start_date_symptoms','death_date','diagnostic_date','recovered_date',
                'recovery_type','per_etn _','group_name',
            ],
            'website' => 'https://www.datos.gov.co/resource/gt2j-8ykr.csv',
            'website_dataset' => 'https://www.datos.gov.co/w/fnzt-ptjk/dneh-mcp2?cur=HK1Q2y0RRFI&from=root',
            'type'  => 'csv',
        );
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
        $scraper_data[] = array(
            'cache_key' => 'temp.gov_colombia_positive_cases',
            'path' => 'Colombia_positive_cases.csv',
            'hasHeader' => true,
            'fields'    => [
                'date_reported','id','date','department_name','department','city_municipality_name','city_municipality',
                'age','unit_measured','sex','source_type_contagion','location','condition','travel_country_code',
                'travel_country_name','recovered','start_date_symptoms','death_date','diagnostic_date','recovered_date',
                'recovery_type','per_etn _','group_name',
            ],
            'website' => 'https://www.datos.gov.co/resource/gt2j-8ykr.csv',
            'website_dataset' => 'https://www.datos.gov.co/w/fnzt-ptjk/dneh-mcp2?cur=HK1Q2y0RRFI&from=root',
            'type'  => 'csv',
            'to_do' => 'get all data and add sort by or show all data as it is, calculate total cases,deaths,recovered.'
        );

        try {
            $scraper = new ScraperHelper;
            foreach($scraper_data as $data){

                $resp = $scraper->curlUrl($data['website']);
                $dom = HtmlDomParser::str_get_html($resp);

                $csvfile = $dom->html();

                Storage::disk('cron_temp')->put($data['path'], $csvfile);

                $array =  $scraper->csvtoarray($data);
                Cache::tags(['temp','temp.gov','temp.gov.Colombia'])->put($data['cache_key'],$array, now()->addMinutes(10));
            }
            // dd($array);

        } catch (\Throwable $th) {
            throw $th;
        }


    }

    static public function Gov_Germany()
    {
        $scraper_data = array(
            'cache_key' => 'temp.gov_germany_positive_cases',
            'path' => 'Colombia_positive_cases.csv',
            'hasHeader' => true,
            'fields'    => [
                'state','newCases','Cases_last_7','incidence_7_days','deaths'
            ],
            'website' => 'https://www.datos.gov.co/resource/gt2j-8ykr.csv',
            'website_dataset' => 'https://www.datos.gov.co/w/fnzt-ptjk/dneh-mcp2?cur=HK1Q2y0RRFI&from=root',
            'type'  => 'csv',
        );

        $scraper = new ScraperHelper;
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

        Storage::disk('cron_temp')->put($data['path'], $csvfile);

        $array =  $scraper->csvtoarray($data);
        Cache::tags(['temp','temp.gov','temp.gov.germany'])->put($data['cache_key'],$array, now()->addMinutes(10));


        dd($csvfile);

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

        $scraper = new ScraperHelper;
        $resp = $scraper->curlUrl($scraper_data['website']);
        $data = json_decode($resp);

        Cache::tags(['temp','temp.gov','temp.gov.india'])->put($scraper_data['cache_key'],$data, now()->addMinutes(10));

        dd($data);

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
        dd($resp);
    }

    static public function Gov_Indonesia()
    {
        # code...
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
            dd($array);

        } catch (\Throwable $th) {
            throw $th;
        }


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
        dd($array);
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
        dd($array);
    }

    static public function Gov_SouthAfrica()
    {

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

    public function curlUrl($site)
    {
        $curl = curl_init($site);
        curl_setopt($curl, CURLOPT_URL, $site);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

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
