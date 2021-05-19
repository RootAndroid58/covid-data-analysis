<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Models\Country;

class JHUCSSEApiController extends Controller
{
    public function historicalbyCountry(Request $request,$country)
    {
        $request->validate([
            'lastdays' => 'required'
        ]);
        $days = $request->input('lastdays');

        $response = ApiHelper::historicalbyCountry($country,$country,$days);

        return response()->json($response) ;
    }
    public function historical(Request $request)
    {
        $request->validate([
            'lastdays' => 'required'
        ]);

        $days = $request->input('lastdays');


        $response = ApiHelper::historical($days);

        return $response;
    }

}
