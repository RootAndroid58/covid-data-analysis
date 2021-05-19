<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Cache;
use App\Models\Country;

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

    Static public function historical($days)
    {
        $data = Cache::get('historical');

        $ApiHelper = new ApiHelper;

        $response = $ApiHelper->getDays($data,$days);

        return response()->json($ApiHelper->SuccessorFail(200,['meta' =>$response]));

    }

    Static public function historicalbyCountry($name, $code ,$days)
    {
        // $data = Cache::get('casesResponse');
        // $data1 = Cache::get('deathsResponse');
        // $data2 = Cache::get('recoveredResponse');

        $data = Cache::get('historical');
        $find = Country::where('name',$name)->orWhere('code',$code)->first();

        $ApiHelper = new ApiHelper;

        $search_key = $ApiHelper->searcharray($data,$find);
        // dd($search_key);

        if($search_key != null){

            $reqired_data = $data[$search_key];

            $response = $ApiHelper->SuccessorFail(200,$ApiHelper->getDays($reqired_data,$days));
        }else{

            $response = $ApiHelper->SuccessorFail(200,['error'=>'Cannot find the Country']);
        }

        return $response;
    }

    public function searcharray($array,$find)
    {
        if(isset($find)){
            foreach ($array as $key => $val) {
                if ($val['country'] === $find->name) {
                    return $key;
                }
            }
        }
        return null;
    }

    public function sort($array,$array1,$array2,$day)
    {
        $remove = ['Province/State','Country/Region','Lat','Long'];

        for ($i=0; $i < max(count($array),count($array1),count($array2)); $i++) {
            dd($array);

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

            $app[$i] = array('country' => $array[$i]['Country/Region'], 'province' => $array[$i]['Province/State'], 'timeline' => array('cases' => $timeline , 'deaths' =>$timeline1 , 'recovered' => $timeline2));
        }
        return $app;
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
                    $deaths= array_slice($deaths, -intval($day));
                }
                if($recovered != null){
                    $recovered= array_slice($recovered, -intval($day));
                }

                $array[$i]['timeline']['cases'] = $cases;
                $array[$i]['timeline']['deaths'] = $deaths;
                $array[$i]['timeline']['recovered'] = $recovered;
            }
            return $array;
        }else{
            // dd($array);
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
            if($search == $val){
                return $key;
            }
        }
    }

}
