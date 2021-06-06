<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Models\Country;
use Illuminate\Support\Facades\Artisan;

class ApiHelper
{

    Static public function SuccessorFail($type = null,$array = null,$meta = false,$deprecated = false)
    {
        switch ($type) {
            case 200:
                $status = array('status'=> "Success", 'status_code' => 200 , 'deprecated' => $deprecated);
                break;
            case 201:
                $status = array('status'=> "Created", 'status_code' => 201, 'deprecated' => $deprecated);
                break;
            case 500:
                $status = array('status'=> "System Error", 'status_code' => 500, 'deprecated' => $deprecated);
                break;
            case 401:
                $status = array('status'=> "Unauthorized", 'status_code' => 401, 'deprecated' => $deprecated);
                break;
            case 403:
                $status = array('status'=> "Forbidden", 'status_code' => 403, 'deprecated' => $deprecated);
                break;
            case 404:
                $status = array('status'=> "Not Found", 'status_code' => 404, 'deprecated' => $deprecated);
                break;
            default:
                $status = array('status'=> "Bad Request", 'status_code' => 400, 'deprecated' => $deprecated);
                break;
        }
        if($array != null){
            if($meta || !is_array($array)){
                return array_merge($status,array("meta" => $array));
            }
                return array_merge($status,$array);
        }
        return $status;
    }

    Static public function worldometer($param,$search = null)
    {
        $search_param = ['today','yesterday','yesterday2'];
        $ApiHelper = new ApiHelper;
        try {
            $data = $ApiHelper->getCache('worldometer','covid:worldometers');

            if($param == 'today' || $param == 'yesterday' || $param == 'yesterday2'){
                $response = $ApiHelper->worldometer_output($data,$param,$search_param);
            }else{
                $response = $data;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        if($search != null){
            $response_key = $ApiHelper->searcharray($response,$search,'country');
            $response = $data[$response_key];
        }
        return $ApiHelper->SuccessorFail(200,$response,true);
    }
    Static public function worldometer_state($param,$search)
    {
        $search_param = ['today','yesterday'];
        try {
            $ApiHelper = new ApiHelper;
            $data = $ApiHelper->getCache('worldometer.states','covid:worldometers');

            if($param == 'today' || $param == 'yesterday'){
                $response = $ApiHelper->worldometer_output($data,$param,$search_param);
            }else{
                $response = $data;
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        if($search != null){
            $response_key = $ApiHelper->searcharray($response,$search,'state');
            $response = $data[$response_key];
        }
        return $ApiHelper->SuccessorFail(200,$response,true);
    }
    Static public function worldometer_continents($param,$search)
    {
        $search_param = ['today','yesterday','yesterday2'];
        try {
            $ApiHelper = new ApiHelper;
            $data = $ApiHelper->getCache('worldometer.continents','covid:worldometers');
            if($param == 'today' || $param == 'yesterday' || $param == 'yesterday2'){
                $response = $ApiHelper->worldometer_output($data,$param,$search_param);
            }else{
                $response = $data;
            }

            if($search != null){
                if($search == 'Oceania'|| $search == 'Australia'){
                    $search = 'Australia/Oceania';
                }
                $response_key = $ApiHelper->searcharray($response,$search,'continent');
                $response = $data[$response_key];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $ApiHelper->SuccessorFail(200,$response,true);
    }

    Static public function worldometer_countries($param,$search)
    {
        $search_param = ['today','yesterday','yesterday2'];
        try {
            $ApiHelper = new ApiHelper;
            $data = $ApiHelper->getCache('worldometer.countries','covid:worldometers');
            if($param == 'today' || $param == 'yesterday' || $param == 'yesterday2'){
                $response = $ApiHelper->worldometer_output($data,$param,$search_param);
            }else{
                $response = $data;
            }

            if($search != null){
                $response_key = $ApiHelper->searcharray($response,$search,'country');
                $response = $data[$response_key];
            }
        } catch (\Throwable $th) {
            throw $th;
        }
        return $ApiHelper->SuccessorFail(200,$response,true);
    }



    Static public function historical($days)
    {
        $ApiHelper = new ApiHelper;
        $data = $ApiHelper->getCache('historical_all','covid:historical');

        $response = $ApiHelper->getDays($data,$days);

        return response()->json($ApiHelper->SuccessorFail(200,$response,true));

    }

    Static public function historicalbyCountry($name, $code ,$days)
    {
        $ApiHelper = new ApiHelper;
        $data = $ApiHelper->getCache('historical_all','covid:historical');
        $find = Country::where('name',$name)->orWhere('code',$code)->first();
        $search_key = null;
        if($find) {
            $search_key = $ApiHelper->searcharray($data,$find->name,'country');
        }


        if($search_key != null){

            $reqired_data = $data[$search_key];

            $response = $ApiHelper->SuccessorFail(200,$ApiHelper->getDays($reqired_data,$days),true);
        }else{

            $response = $ApiHelper->SuccessorFail(200,['error'=>'Cannot find the Country']);
        }

        return $response;
    }

    static public function gov_Austria($cacheKey)
    {
        $ApiHelper = new ApiHelper;
        $data = $ApiHelper->getCache($cacheKey,'covid:gov-austria');
        $response = $ApiHelper->SuccessorFail(200, $data,true);
        return $response;
    }








    /**
     * Helper for Api functions
     */

    public function searcharray($array,$find,$search_key)
    {
        if(isset($find)){
            foreach ($array as $key => $val) {
                if ($val[$search_key] === $find || strcasecmp($val[$search_key],$find) == 0) {
                    return $key;
                }
            }
        }
        return null;
    }

    public function getDays($array ,$day)
    {
        if(isset($array[0])){

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
                    $cases = array_slice($cases, - intval($day));
                }
                if($deaths != null){
                    $deaths= array_slice($deaths, - intval($day));
                }
                if($recovered != null){
                    $recovered= array_slice($recovered, - intval($day));
                }

                $array[$i]['timeline']['cases'] = $cases;
                $array[$i]['timeline']['deaths'] = $deaths;
                $array[$i]['timeline']['recovered'] = $recovered;
            }
            return $array;
        }else{
            $cases = $array['timeline']['cases'];
            $deaths = $array['timeline']['deaths'];
            $recovered = $array['timeline']['recovered'];

            if($cases != null){
                $cases = array_slice($cases, - intval($day));
            }
            if($deaths != null){
                $deaths= array_slice($deaths, -intval($day));
            }
            if($recovered != null){
                $recovered= array_slice($recovered, -intval($day));
            }

            $array['timeline']['cases'] = $cases;
            $array['timeline']['deaths'] = $deaths;
            $array['timeline']['recovered'] = $recovered;
            return $array;
        }
    }

    public function worldometer_output($array,$search,$data)
    {
        $remove = array_values(array_diff($data,[$search]));
        if(isset($array[0])){
            for ($i=0; $i < count($array); $i++) {
                unset($array[$i]['timeline'][$remove[0]]);
                if(isset($remove[1])){
                    unset($array[$i]['timeline'][$remove[1]]);
                }
            }

        }
        return $array;
    }

    public function SearchKey($array, $search)
    {
        foreach($array as $key => $val){
            if($search == $val || strcasecmp($search,$val) == 0){
                return $key;
            }
        }
    }


    public function getCache($cache,$call)
    {
        $data = Cache::get($cache);
        if($data == null){
            Artisan::call($call);
            $data = Cache::get($cache);
        }
        return $data;
    }

}
