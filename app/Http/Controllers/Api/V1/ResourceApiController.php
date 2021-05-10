<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use App\Models\City;

class ResourceApiController extends Controller
{
    public function getStateById(Request $request)
    {
        $request->validate([
            'country_id' => 'required|integer'
        ]);

        $country_id = $request->country_id;

        $data = Country::with('states')->where('id',$country_id)->first();

        return response()->json($data);
    }

    public function getCityById(Request $request)
    {
        $request->validate([
            'state_id' => 'required|integer',
        ]);

        $state_id = $request->state_id;

        $data = State::with('cities')->where('id',$state_id)->first();

        return response()->json($data);
    }
}
