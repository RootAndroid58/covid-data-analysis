<?php

namespace App\Http\Controllers;

use App\Http\Helpers\ApiHelper;
use App\Http\Helpers\CacheSorter;
use App\Http\Helpers\cacheUpdater;
use App\Http\Helpers\DataHelper;
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
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use \SpreadsheetReader;
use ZanySoft\Zip\ZipManager;
use Zip;


class TestController extends Controller
{
    public function index()
    {
        // phpinfo();
        // Artisan::call('inspire');
        // return Artisan::output();

        $data = ApiHelper::apple_trends_region("prod.mobility.apple.country");
        // dd($data['meta']);
        return view('test');
    }

    public function test2(Request $request)
    {
        return Artisan::call('covid:gov-austria');
    }

    public function worldometer_continent($data,$filter_data)
    {
        $dataset = $filter_data;
        $location = array(
            'Asia'              =>  array('lat' => 23.7027273,'long' => 62.3750637 ),
            'North America'     =>  array('lat' => 31.6768272,'long' => -146.4707474 ),
            'South America'     =>  array('lat' => -15.6551563,'long' => -100.7484231 ),
            'Europe'            =>  array('lat' => 25.771324,'long' => -35.6012256),
            'Africa'            =>  array('lat' => 1.7383867,'long' => -16.3094636),
            'Australia/Oceania' =>  array('lat' => -8.6599161,'long' => 91.1469847)
        );
        $sort = new CacheSorter;

        $country_name = array();

        foreach($filter_data as $search){
            $country_key = $sort->search($filter_data,$data['continent'],'continent');
            $key[] = $country_key;
            unset($filter_data[$country_key]);
        }
        $key = array_filter($key, fn($value) => !is_null($value) && $value !== '' );
        $filter_data = array_values($filter_data);

        foreach($key as $search){
            $country_name[] = $dataset[$search]['country'];
        }

        $response = array(
            'continent' => $data['continent'],
            'continentInfo' => $location[$data['continent']],
            'countries' => $country_name,
            'timeline' => array(
                'today' => array(
                    'cases'         => $data['timeline']['today']['cases'],
                    'todayCases'    => $data['timeline']['today']['todayCases'],
                    'deaths'        => $data['timeline']['today']['deaths'],
                    'todayDeaths'   => $data['timeline']['today']['todayDeaths'],
                    'recovered'     => $data['timeline']['today']['recovered'],
                    'todayRecovered'=> $data['timeline']['today']['todayRecovered'],
                    'active'        => $data['timeline']['today']['active'],
                    'critical'      => $data['timeline']['today']['critical'],
                ),
                'yesterday' => array(
                    'cases'         => $data['timeline']['yesterday']['cases'],
                    'todayCases'    => $data['timeline']['yesterday']['todayCases'],
                    'deaths'        => $data['timeline']['yesterday']['deaths'],
                    'todayDeaths'   => $data['timeline']['yesterday']['todayDeaths'],
                    'recovered'     => $data['timeline']['yesterday']['recovered'],
                    'todayRecovered'=> $data['timeline']['yesterday']['todayRecovered'],
                    'active'        => $data['timeline']['yesterday']['active'],
                    'critical'      => $data['timeline']['yesterday']['critical'],
                ),
                'yesterday2' => array(
                    'cases'         => $data['timeline']['yesterday2']['cases'],
                    'todayCases'    => $data['timeline']['yesterday2']['todayCases'],
                    'deaths'        => $data['timeline']['yesterday2']['deaths'],
                    'todayDeaths'   => $data['timeline']['yesterday2']['todayDeaths'],
                    'recovered'     => $data['timeline']['yesterday2']['recovered'],
                    'todayRecovered'=> $data['timeline']['yesterday2']['todayRecovered'],
                    'active'        => $data['timeline']['yesterday2']['active'],
                    'critical'      => $data['timeline']['yesterday2']['critical'],
                ),
            ),
        );
        return $response;
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
        Artisan::call('cron:sync');
        // Cache::forget('deathsResponse ');
        // $data = Cache::get('casesResponse_temp');
        // $data1 = Cache::get('deathsResponse_temp');
        // $data2 = Cache::get('recoveredResponse_temp');
        // // dd($data,$data1,$data2);
        // $response = $this->hostorical_sort($data,$data1,$data2,'all');
        // Cache::put('historical', $response);

        // return $response;
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
