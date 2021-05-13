<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\State;
use App\Models\Country;
use App\Models\City;
use App\Models\Category;
use App\Models\SubCategory;
use App\Http\Helpers\ApiHelper;
use App\Models\Resource;
use Error;
use \Illuminate\Database\QueryException;

class ResourceApiController extends Controller
{
    public function Category(Request $request)
    {
        $request->validate([
            'search' => 'nullable'
        ]);
        $search = $request->input('search');
        try {
            $data = Category::with('subcatogary:id,name,category_id,extra')->where(function ($query) use ($search) {
                if($search != null){
                    $query->orWhere('id','like',"%$search%")
                        ->orWhere('name','like',"%$search%");
                }
            })->paginate(20);

        } catch (QueryException $th) {
            return response()->json(ApiHelper::SuccessorFail(500, array("exception" => $th)));
        } catch (Error $th){
            return response()->json(ApiHelper::SuccessorFail(500, array("exception" => $th)));
        }


        return response()->json(ApiHelper::SuccessorFail(200,$data));

    }

    public function SubCategory(Request $request)
    {
        $request->validate([
            'search' => 'nullable'
        ]);
        $search = $request->input('search');

        $data = SubCategory::with('category:id,name,slug')->where(function ($query) use ($search) {
            if($search != null){
                $query->orWhere('id','like',"%$search%")
                    ->orWhere('name','like',"%$search%")
                    ->orWhere('category_id','like',"%$search%");
            }
        })->paginate(20);

        return response()->json(array(ApiHelper::SuccessorFail(200),$data));
    }

    public function Resources(Request $request)
    {
        $request->validate([
            'country_id' => 'nullable|numeric',
            'state_id' => 'nullable|numeric',
            'city_id' => 'nullable|numeric',
            'name' => 'nullable|string',
            'phone_no' => 'nullable|string'
        ]);

        $country_id = $request->input('country_id');
        $state_id = $request->input('state_id');
        $city_id = $request->input('city_id');
        $name = $request->input('name');
        $phone_no = $request->input('phone_no');

        try {
            $data = Resource::with('categories:id,name,slug','city:id,name','state:id,name,state_code','country:id,name,code')->where(function ($query) use ($country_id,$state_id,$city_id,$name,$phone_no){
                if($country_id != null){
                    $query->orWhere('country_id',$country_id);
                }
                if($state_id != null){
                    $query->orWhere('state_id',$state_id);
                }
                if($city_id != null){
                    $query->orWhere('city_id',$city_id);
                }
                if($name != null){
                    $query->orWhere('name',"like","%$name%");
                }
                if($phone_no != null){
                    $query->orWhere('phone_no',"like","%$phone_no%");
                }
            })->paginate(50);
        } catch (\Throwable $th) {
            return ApiHelper::SuccessorFail(500,array("error" => $th));
        }
        return response()->json(array(ApiHelper::SuccessorFail(200),$data));

    }
}
