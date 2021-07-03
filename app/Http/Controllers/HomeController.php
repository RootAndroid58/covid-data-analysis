<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V1\AppleApiController;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiHelper;

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

    public function status()
    {
        $data = ApiHelper::worldometer(null,null);
        $data = $data['meta'];
        for ($i=0; $i < count((array)$data); $i++) {
            if( !isset($data[$i]['iso2'])){
                unset($data[$i]);
            }
        }
        $data = array_values((array)$data);
        // dd($data);
        return view('website.trends',compact('data'));
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

}
