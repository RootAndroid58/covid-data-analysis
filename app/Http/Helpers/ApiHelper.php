<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Models\Country;
use Illuminate\Support\Facades\Artisan;

class ApiHelper
{

    Static public function SuccessorFail($type = null,$array = null,$deprecated = false)
    {
        if($type == 200){
            $status = array('status'=> "Success", 'status_code' => 200 , 'deprecated' => $deprecated);
        }elseif($type == 201){
            $status = array('status'=> "Created", 'status_code' => 201, 'deprecated' => $deprecated);
        }elseif($type == 500){
            $status = array('status'=> "System Error", 'status_code' => 500, 'deprecated' => $deprecated);
        }elseif($type == 401){
            $status = array('status'=> "Unauthorized", 'status_code' => 401, 'deprecated' => $deprecated);
        }elseif($type == 403){
            $status = array('status'=> "Forbidden", 'status_code' => 403, 'deprecated' => $deprecated);
        }elseif($type == 404){
            $status = array('status'=> "Not Found", 'status_code' => 404, 'deprecated' => $deprecated);
        }else{
            $status = array('status'=> "Bad Request", 'status_code' => 400, 'deprecated' => $deprecated);
        }
        if($array != null){
            if(is_array($array)){
                return array_merge($status,$array);
            }elseif(!is_array($array)){
                return array_merge($status,array("meta" => $array));
            }
        }
        return $status;
    }

    Static public function worldometer($param,$search = null)
    {
        $search_param = ['today','yesterday','yesterday2'];
        try {
            $data = Cache::get('worldometer');
            if($data == null){
                Artisan::call('covid:worldometers');
                $data = Cache::get('worldometer');
            }

            $ApiHelper = new ApiHelper;
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
        return $ApiHelper->SuccessorFail(200,['meta' =>$response]);
    }
    Static public function worldometer_state($param,$search)
    {
        $search_param = ['today','yesterday'];
        try {
            $data = Cache::get('worldometer.states');
            if($data == null){
                Artisan::call('covid:worldometers');
                $data = Cache::get('worldometer.states');
            }

            $ApiHelper = new ApiHelper;
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
        return $ApiHelper->SuccessorFail(200,['meta' =>$response]);
    }
    Static public function worldometer_continents($param,$search)
    {
        $search_param = ['today','yesterday','yesterday2'];
        try {
            $data = Cache::get('worldometer.continents');
            if($data == null){
                Artisan::call('covid:worldometers');
                $data = Cache::get('worldometer.continents');
            }
            $ApiHelper = new ApiHelper;
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
        return $ApiHelper->SuccessorFail(200,['meta' =>$response]);
    }

    Static public function worldometer_countries($param,$search)
    {
        $search_param = ['today','yesterday','yesterday2'];
        try {
            $data = Cache::get('worldometer.countries');
            if($data == null){
                Artisan::call('covid:worldometers');
                $data = Cache::get('worldometer.countries');
            }
            $ApiHelper = new ApiHelper;
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
        return $ApiHelper->SuccessorFail(200,['meta' =>$response]);
    }



    Static public function historical($days)
    {
        $data = Cache::get('historical_all');

        if($data == null){
            ScraperHelper::covid_hestorical();
            $data = Cache::get('historical_all');
        }

        $ApiHelper = new ApiHelper;

        $response = $ApiHelper->getDays($data,$days);

        return response()->json($ApiHelper->SuccessorFail(200,['meta' =>$response]));

    }

    Static public function historicalbyCountry($name, $code ,$days)
    {
        $data = Cache::get('historical_all');
        if($data == null){
            ScraperHelper::covid_hestorical();
            $data = Cache::get('historical_all');
        }
        $find = Country::where('name',$name)->orWhere('code',$code)->first();

        $ApiHelper = new ApiHelper;

        $search_key = $ApiHelper->searcharray($data,$find->name,'country');

        if($search_key != null){

            $reqired_data = $data[$search_key];

            $response = $ApiHelper->SuccessorFail(200,$ApiHelper->getDays($reqired_data,$days));
        }else{

            $response = $ApiHelper->SuccessorFail(200,['error'=>'Cannot find the Country']);
        }

        return $response;
    }









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

    // public function getcontinents($array,$find)
    // {
    //     if(isset($find)){
    //         foreach($array as $key => $val){
    //             if($key == 'index' && $val == null){

    //             }
    //         }
    //     }
    // }



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

    public function SearchKey($array, $search)
    {
        foreach($array as $key => $val){
            if($search == $val || strcasecmp($search,$val) == 0){
                return $key;
            }
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

}
