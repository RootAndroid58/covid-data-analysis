<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;

class VaccineApiController extends Controller
{
    public function vaccine_country(Request $request)
    {
        $response = $this->get_country();

        return response()->json(ApiHelper::SuccessorFail(200,array("country" => $response),true));
    }

    public function vaccine(Request $request,$vaccine)
    {
        $cacheKey = "prod.vaccine";
        $data = $this->get_country();
        $match = false;
        foreach($data as $temp){
            if (strpos($vaccine, $temp) !== FALSE) { // Yoshi version

                $match = true;
                break;
            }
        }
        if(!$match){
            return response()->json(ApiHelper::SuccessorFail(400,
                array(
                    'error' => 'incorrect/invalid country name passed.',
                    'message' => "country name is case sensitive",
                    'supported' => $data
                ),true));
        }
        $response = ApiHelper::vaccineAPI($cacheKey,$vaccine);

        return response()->json($response);
    }

    public function get_country()
    {
        $cacheKey = "prod.vaccine.location";

        return ApiHelper::getCache($cacheKey,'scraper:vaccine');
    }
}
