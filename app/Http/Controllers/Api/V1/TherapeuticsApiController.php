<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiHelper;
use Illuminate\Http\Request;

class TherapeuticsApiController extends Controller
{
    public function index(Request $request)
    {
        $cacheKey = 'prod.therapeutics';
        $response = ApiHelper::TherapeuticsApi($cacheKey);

        return response()->json($response);
    }
}
