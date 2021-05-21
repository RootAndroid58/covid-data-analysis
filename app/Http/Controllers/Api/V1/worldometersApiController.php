<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class worldometersApiController extends Controller
{
    public function getAll(Request $request)
    {
        $request->validate([
            'type' => 'sometimes|in:today,yesterday,yesterday2|nullable'
        ]);

        $type = $request->input('type');

        $response = ApiHelper::worldometer($type);

        return response()->json($response);
    }

    public function getStates(Request $request)
    {
        $request->validate([
            'type' => 'sometimes|in:today,yesterday|nullable',
            'search'=> 'sometimes|nullable',
        ]);

        $type = $request->input('type');
        $search = $request->input('search');
        // dd($search);

        $response = ApiHelper::worldometer_state($type,$search,'state');

        return response()->json($response);

    }
    public function getcontinents(Request $request)
    {
        $request->validate([
            'type' => 'sometimes|in:today,yesterday|nullable',
            'search'=> 'sometimes|in:Asia,North America,South America,Europe,Africa,Oceania,Australia,Australia-Oceania|nullable',
        ]);

        $type = $request->input('type');
        $search = $request->input('search');
        // dd($search);

        $response = ApiHelper::worldometer_continents($type,$search);

        return response()->json($response);

    }


}
