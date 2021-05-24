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
}
