<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiHelper
{
    Static public function SuccessorFail($type = null,$array = null)
    {
        if($type == 200){
            $status = array('status'=> "Success", 'status_code' => 200);
        }elseif($type == 201){
            $status = array('status'=> "Created", 'status_code' => 201);
        }elseif($type == 500){
            $status = array('status'=> "System Error", 'status_code' => 500);
        }elseif($type == 401){
            $status = array('status'=> "Unauthorized", 'status_code' => 401);
        }elseif($type == 403){
            $status = array('status'=> "Forbidden", 'status_code' => 403);
        }else{
            $status = array('status'=> "Bad Request", 'status_code' => 400);
        }
        if($array != null){
            if(is_array($array)){
                $status = array_merge($status,$array);
            }elseif(!is_array($array)){
                $status = array_merge($status,array("meta" => $array));
            }
        }
        return $status;
    }

}
