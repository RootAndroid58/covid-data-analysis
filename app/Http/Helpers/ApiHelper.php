<?php

namespace App\Http\Helpers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ApiHelper
{
    Static public function SuccessorFail($type = null,$array = null,$deprecated = false)
    {
        if($type == 200){
            $status = array('status'=> "Success", 'status_code' => 200 , 'deprecated' => $deprecated);
        }elseif($type == 201){
            $status = array('status'=> "Created", 'status_code' => 201, 'deprecated' => $deprecated);
        }elseif($type == 500){
            $status = array('status'=> "System Error", 'status_code' => 500, 'deprecated' => $deprecated);
        }elseif($type == 401){
            $status = array('status'=> "Unauthorized", 'status_code' => 401, 'deprecated' => $deprecated);
        }elseif($type == 403){
            $status = array('status'=> "Forbidden", 'status_code' => 403, 'deprecated' => $deprecated);
        }elseif($type == 404){
            $status = array('status'=> "Not Found", 'status_code' => 404, 'deprecated' => $deprecated);
        }else{
            $status = array('status'=> "Bad Request", 'status_code' => 400, 'deprecated' => $deprecated);
        }
        if($array != null){
            if(is_array($array)){
                return array_merge($status,$array);
            }elseif(!is_array($array)){
                return array_merge($status,array("meta" => $array));
            }
        }
        return $status;
    }

}
