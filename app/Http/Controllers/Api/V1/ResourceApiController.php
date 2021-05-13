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
use Error;
use \Illuminate\Database\QueryException;

class ResourceApiController extends Controller
{
    public function Categary(Request $request)
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

    public function SubCategary(Request $request)
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
}
