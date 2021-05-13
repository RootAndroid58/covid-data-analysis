<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiHelper
{
    Static public function SuccessorFail($type = null)
    {
        if($type == 200){
            return array('status'=> "Success", 'status_code' => 200);
        }elseif($type == 201){
            return array('status'=> "Created", 'status_code' => 201);
        }elseif($type == 500){
            return array('status'=> "System Error", 'status_code' => 500);
        }elseif($type == 401){
            return array('status'=> "Unauthorized", 'status_code' => 401);
        }elseif($type == 403){
            return array('status'=> "Forbidden", 'status_code' => 403);
        }else{
            return array('status'=> "Bad Request", 'status_code' => 400);
        }
    }

}
