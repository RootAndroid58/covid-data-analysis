<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;

class AppleApiController extends Controller
{
    // https://github.com/ActiveConclusion/COVID19_mobility
    public function appleMobility(Request $request,$country,$region = null)
    {

        $cacheKey = 'prod.mobility.apple';

        $response = ApiHelper::apple_mobility($cacheKey,$country,$region);

        return response()->json($response);
    }

    public function appleCountries(Request $request)
    {

        $cacheKey = 'prod.mobility.apple.country';

        $response = ApiHelper::apple_mobility_country($cacheKey);

        return response()->json($response);

    }

    public function MobilityUS_states(Request $request)
    {
        $cacheKey = 'prod.mobility.apple_us.states';

        $response = ApiHelper::apple_mobility_country($cacheKey);

        return response()->json($response);
    }
    public function MobilityUS(Request $request,$state,$county = null)
    {
        $cacheKey = 'prod.mobility.apple_us';

        $response = ApiHelper::apple_mobility_us($cacheKey,$state,$county);

        return response()->json($response);
    }

    public function trends_regions(Request $request)
    {


        $cacheKey = 'prod.mobility.appletrends.regions';

        $response = ApiHelper::apple_trends_region($cacheKey);

        return response()->json($response);
    }

    public function trends(Request $request,$region)
    {
        $cacheKey = 'prod.mobility.appletrends';

        $response = ApiHelper::apple_trends($cacheKey,$region);

        return response()->json($response);
    }

}
