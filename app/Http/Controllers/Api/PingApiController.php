<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PingApiController extends Controller
{
    public function ping()
    {
        $array = array(
            "status"=> "Success",
            "status_code" => 200,
            "data" => array(
                "response" => "pong"
            ),
        );
        return response()->json($array ,200);
    }
}
