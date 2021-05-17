<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Country;
use App\Models\State;
use App\Models\City;
use App\Http\Helpers\ApiHelper;
use Illuminate\Support\Facades\Validator;

class WorldApiController extends Controller
{

    public function Country(Request $request)
    {
        $request->validate([
            'search' => 'nullable'
        ]);

        $search = $request->input('search');
        $data = $this->search(Country::class,$search,['id','name','code']);
        return response()->json($data);
    }

    public function State(Request $request)
    {
        $request->validate([
            'search' => 'nullable'
        ]);
        $search = $request->input('search');
        $data = $this->search(State::class,$search,['id','name','state_code']);
        return response()->json($data);

    }
    public function City(Request $request)
    {
        $request->validate([
            'search' => 'nullable'
        ]);
        $search = $request->input('search');
        $data = $this->search(City::class,$search,['id','name']);
        return response()->json($data);

    }

    public function search($model,$search = null,$params = null)
    {
        try {
            $data = $model::where(function ($query) use ($search,$params){
                if($search != null && $params != null){
                    foreach($params as $param){
                        $query->orWhere($param,'like',"%$search%");
                    }
                }
            })->paginate(500);

            $data->appends(["search"=>$search]);


            return ApiHelper::SuccessorFail(200,$data);
        } catch (\Error $th) {
            // throw $th;
            return ApiHelper::SuccessorFail(500,array("error" => $th));
        }
    }

    public function StateById(Request $request)
    {
        $request->validate([
            'country_id' => 'required'
        ]);
        $country_id = $request->input('country_id');

        $country = $this->getByID(Country::class,'id',$country_id);

        $data = State::where('country_code',$country->code)->get();
        $data = ApiHelper::SuccessorFail(200,$data);
        return $data;
    }
    public function CityById(Request $request)
    {
        $request->validate([
            'state_id' => 'required',
            'country_id' => 'required'
        ]);
        $state_id = $request->input('state_id');
        $country_id = $request->input('country_id');

        $state = $this->getByID(State::class,'id',$state_id);
        $country = $this->getByID(Country::class,'id',$country_id);

        $data = City::where('state_code',$state->state_code)->where('country_code',$country->code)->get();
        $data = ApiHelper::SuccessorFail(200,$data);
        return $data;
    }

    public function getByID($model ,$param = null , $value = null)
    {
        $data = $model::where($param,$value)->first();

        return $data;
    }
}
