<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;

class AppleApiController extends Controller
{
    // https://github.com/ActiveConclusion/COVID19_mobility
    public function appleMobility(Request $request)
    {
        $request->validate([
            'country' => 'required',
            'region' => 'sometimes|nullable',
        ]);

        $country = $request->input('country');
        $region = $request->input('region');
        $cacheKey = 'prod.mobility.apple';

        $response = ApiHelper::apple_mobility($cacheKey,$country,$region);
        dd($response);


        return response()->json($response);
    }

    public function appleCountries(Request $request)
    {

        $cacheKey = 'prod.mobility.apple.country';

        $response = ApiHelper::apple_mobility_country($cacheKey);

        return response()->json($response);

    }

}
