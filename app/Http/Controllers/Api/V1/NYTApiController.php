<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;

class NYTApiController extends Controller
{
    public $cacheKeys = array(
        array(
            'recent' => 'prod.NYT.us-countries-recent',
            'countries' => 'prod.NYT.us-countries',
            'us-states' => 'prod.NYT.us-states',
            'us' => 'prod.NYT.us',
        ),
        array(
            'recent' => 'prod.NYT.avarage.us-countries-recent',
            'us-states' => 'prod.NYT.avarage.us-states',
            'us' => 'prod.NYT.avarage.us',
            // 'prod.NYT.avarage.us-countries', // too big to process
        ),
    );
    public function nyt_default(Request $request)
    {
        $type = implode(',',array_flip($this->cacheKeys[0]));


        $request->validate([
            'type' => 'required|in:'. $type,
            'state' => 'required_unless:type,us',
            'county' => 'sometimes|nullable'
        ],[
            'type.required' => 'The type field is required. Supported fields : '.$type,
            'type.in' => 'Supported fields : '.$type,
            'state.required_unless' => 'The search field is required unless \type\ is in \us\.',
        ]);

        unset($type);
        $type = $request->input('type');
        $search = $request->input('state');
        if($search !== 'us-states'){
            $county = $request->input('county');
        }else $county = null;

        $cacheKeys = $this->cacheKeys[0][$type];

        $response = ApiHelper::NYT_complete($cacheKeys,'scraper:nyt','state',$search,$county);

        return response()->json($response);
    }
    public function nyt_average(Request $request)
    {
        $type = implode(',',array_flip($this->cacheKeys[1]));


        $request->validate([
            'type' => 'required|in:'. $type,
            'state' => 'required_unless:type,us',
            'county' => 'sometimes|nullable'
        ],[
            'type.required' => 'The type field is required. Supported fields : '.$type,
            'type.in' => 'Supported fields : '.$type,
            'state.required_unless' => "The search field is required unless type value is us.",
        ]);

        unset($type);
        $type = $request->input('type');
        $search = $request->input('state');
        $county = $request->input('county');

        $cacheKeys = $this->cacheKeys[0][$type];

        $response = ApiHelper::NYT_complete($cacheKeys,'scraper:nyt','state',$search,$county);

        return response()->json($response);
    }

    public function search(Request $request,$type = null)
    {
        if($type == null){
            $response = array('error' => "Supported url parameters : default,avarage","url" => 'v1/covid-19/nyt/search/{type}');

            return response()->json(ApiHelper::SuccessorFail(400,$response));
        }
        $call = 'scraper:nyt';
        if($type == 'default'){
            $cache_data = $this->cacheKeys[0];
        }else{
            $cache_data = $this->cacheKeys[1];
        }
        $cacheKey = implode(',',array_flip($cache_data));
        $request->validate([
            'type' =>   'required|in:'. $cacheKey,
            'state' => 'sometimes|nullable',
        ],[
            'type.required' => 'The type field is required. Supported fields : '.$cacheKey,
            'type.in' => 'Supported fields : '.$cacheKey,
        ]);

        $type = $request->input('type');
        $state = $request->input('state');

        $cacheKeys = $cache_data[$type];

        $response = ApiHelper::NYT_search($cacheKeys,$call,$state);

        return response()->json($response);
    }
}
