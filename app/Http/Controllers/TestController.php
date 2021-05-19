<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\City;
use App\Models\Country;
use App\Models\Resource;
use App\Models\State;
use Error;
use Illuminate\Support\Facades\Mail;
use Laravel\Socialite\Facades\Socialite;
use voku\helper\HtmlDomParser;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Helpers\ScraperHelper;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use \SpreadsheetReader;

class TestController extends Controller
{
public function index()
    {
        Artisan::call('covid:historical');
    }

    public function test2()
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

                $dom = HtmlDomParser::file_get_html($data['website']);
                $csvfile = $dom->html();
                Storage::disk('cron_temp')->put($data['path'], $csvfile);

                $path = storage_path('cron_temp\\' . $data['path']);
                $header = new SpreadsheetReader($path);

                foreach($header as $key => $row){
                    if($key == 0){
                        $fields = $row;
                    }else break;
                }
                $scraper = new ScraperHelper;
                // dd(array('hasHeader'=> true , 'path', $data['path'], 'fields' => $fields));
                $response = $scraper->csvtoarray(array('hasHeader'=> true , 'path'=> $data['path'], 'fields' => $fields));
                Cache::put($data['cache_key'],$response);//, now()->addMinutes(10));

            }


            dd($dom->html());


    }

    public function testMail(Request $request)
    {
            try{
                $to_name = 'ETHYT';
                $to_email = 'rootand58@gmail.com';
                $data = array('name'=>"Sam Jose", "body" => "Test mail");

                Mail::send('emails.mail', $data, function($message) use ($to_name, $to_email) {
                    $message->to($to_email, $to_name)
                            ->subject('Artisans Web Testing Mail');
                    // $message->from('contact@crada.ga','Artisans Web');
                });
            }catch(Error $e){
                dd($e);
            }

    }
    public function throwerror(Request $request)
    {
        // throw new Error('test');
        // Cache::forget('deathsResponse ');
        $data = Cache::get('casesResponse_temp');
        $data1 = Cache::get('deathsResponse_temp');
        $data2 = Cache::get('recoveredResponse_temp');
        // dd($data,$data1,$data2);
        $response = $this->sort($data,$data1,$data2,'all');
        Cache::put('historical', $response);

        return $response;
    }

    public function searcharray($array,$find)
    {

            foreach ($array as $key => $val) {
                if ($val['Country/Region'] === $find->name) {
                    return $key;
                }
            }
        return null;
    }
    public function sort($array,$array1,$array2)
    {
        $remove = ['Province/State','Country/Region','Lat','Long'];
        // dd($array,$array1,$array2);
        for ($i=0; $i < max(count($array),count($array1),count($array2)); $i++) {

            $timeline = array_diff_key($array[$i],array_flip($remove));
            if( isset($array1[$i])){
                if($array[$i]['Country/Region'] != $array1[$i]['Country/Region']){
                    $search_key = $this->SearchKey($array1,$array[$i]['Country/Region']);
                    if($search_key != null) $timeline1 = array_diff_key($array1[$search_key],array_flip($remove));
                    else $timeline1 = null;
                }else $timeline1 = array_diff_key($array1[$i],array_flip($remove));
            }else $timeline1 = null;
            if( isset($array2[$i])){
                if($array[$i]['Country/Region'] != $array2[$i]['Country/Region']){
                    $search_key = $this->SearchKey($array2,$array[$i]['Country/Region']);
                    if($search_key != null) $timeline2 = array_diff_key($array2[$search_key],array_flip($remove));
                    else $timeline2 = null;
                }else $timeline2 = array_diff_key($array2[$i],array_flip($remove));
            }else $timeline2 = null;

            $app[$i] = array('country' => $array[$i]['Country/Region'], 'province' => $array[$i]['Province/State'] == '' ? null : $array[$i]['Province/State'], 'timeline' => array('cases' => $timeline , 'deaths' =>$timeline1 , 'recovered' => $timeline2));
        }
        return $app;
    }

    public function SearchKey($array, $search)
    {
        foreach($array as $key => $val){
            if($search == $val){
                return $key;
            }
        }
    }

    public function getDays($array ,$day)
    {
        if(strtolower($day) == 'all'){
            return $array;
        }else if($day > max([count($array[0]['timeline']['cases']) , count($array[0]['timeline']['deaths']) ,count($array[0]['timeline']['recovered']) ])){
            return $array;
        }
        for ($i=0; $i < count($array); $i++) {
            $cases = $array[$i]['timeline']['cases'];
            $deaths = $array[$i]['timeline']['deaths'];
            $recovered = $array[$i]['timeline']['recovered'];

            if($cases != null){
                $cases = array_slice($cases, -$day);
            }
            if($deaths != null){
                $deaths= array_slice($deaths, -$day);
            }
            if($recovered != null){
                $recovered= array_slice($recovered, -$day);
            }

            $array[$i]['timeline']['cases'] = $cases;
            $array[$i]['timeline']['deaths'] = $deaths;
            $array[$i]['timeline']['recovered'] = $recovered;
        }
        return $array;
    }
}
