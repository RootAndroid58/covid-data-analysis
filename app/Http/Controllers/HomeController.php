<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V1\AppleApiController;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiHelper;
use App\Http\Helpers\DataHelper;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('website.index');
    }

    public function trends()
    {
        $country = ApiHelper::apple_mobility_country('prod.mobility.apple.country');

        return view('website.trends', compact('country'));
    }

    public function country(Request $request,$search)
    {
        $data = ApiHelper::apple_trends("prod.mobility.appletrends",$search);
        $data1 = ApiHelper::apple_mobility("prod.mobility.apple",$search,null);
        $data = array_merge(...(array)$data['meta']['pages']);
        // dd($data1);
        $data1 = collect(array_merge(...(array)$data1['meta']['pages']))->groupBy('sub-region')->toArray();
        if(count($data) > 0){
            $data = json_encode($data);
            return view('website.apple',compact('search','data','data1'));
        }
        return view('website.trends',compact('data'))->with(['error' => "cannot find ".$search,'search' => $search]);

    }

    public function worldometer(Request $request)
    {
        return view('website.worldometer');
    }

    public function status(Request $request)
    {
        return view('website.status');
    }

}
