<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Api\V1\AppleApiController;
use Illuminate\Http\Request;
use App\Http\Helpers\ApiHelper;
use App\Http\Helpers\DataHelper;
use App\Models\Category;
use App\Models\Resource;
use Yajra\DataTables\Facades\DataTables;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('website.index');
    }

    public function trends()
    {
        return view('website.trends');
    }

    public function country(Request $request,$search)
    {
        $data = ApiHelper::apple_trends("prod.mobility.appletrends",$search);
        $data1 = ApiHelper::apple_mobility("prod.mobility.apple",$search,null);
        $data = array_merge(...(array)$data['meta']['pages']);
        // dd($data1);
        $data1 = collect(array_merge(...(array)$data1['meta']['pages']))->groupBy('sub-region')->toArray();
        if(count($data) > 0){
            $data = json_encode($data);
            return view('website.apple',compact('search','data','data1'));
        }
        return view('website.trends',compact('data'))->with(['error' => "cannot find ".$search,'search' => $search]);

    }

    public function worldometer(Request $request)
    {
        return view('website.worldometer');
    }

    public function status(Request $request)
    {
        return view('website.status');
    }

    public function helpline(Request $request)
    {
        if ($request->ajax()) {
            $query = Resource::with(['categories', 'country', 'state', 'city', 'subcats'])->select(sprintf('%s.*', (new Resource())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'resource_show';
                $editGate = 'resource_edit';
                $deleteGate = 'resource_delete';
                $crudRoutePart = 'resources';

                return view('partials.datatablesActions', compact(
                'viewGate',
                'editGate',
                'deleteGate',
                'crudRoutePart',
                'row'
            ));
            });

            $table->editColumn('id', function ($row) {
                return $row->id ? $row->id : '';
            });
            $table->editColumn('category_category_name', function ($row) {
                $labels = [];
                foreach ($row->categories as $category) {
                    $labels[] = sprintf('<span class="badge badge-info ">%s</span>', $category->category_name);
                }

                return implode(' ', $labels);
            });
            $table->addColumn('city_name', function ($row) {
                return $row->city ? $row->city->name : '';
            });

            $table->editColumn('city.state_code', function ($row) {
                return $row->city ? (is_string($row->city) ? $row->city : $row->city->state_code) : '';
            });
            $table->editColumn('city.latitude', function ($row) {
                return $row->city ? (is_string($row->city) ? $row->city : $row->city->latitude) : '';
            });
            $table->editColumn('city.longitude', function ($row) {
                return $row->city ? (is_string($row->city) ? $row->city : $row->city->longitude) : '';
            });
            $table->editColumn('city.country_code', function ($row) {
                return $row->city ? (is_string($row->city) ? $row->city : $row->city->country_code) : '';
            });
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('phone_no', function ($row) {
                return $row->phone_no ? $row->phone_no : '';
            });
            $table->editColumn('email', function ($row) {
                return $row->email ? $row->email : '';
            });
            $table->editColumn('details', function ($row) {
                return $row->details ? $row->details : '';
            });
            $table->editColumn('up_vote', function ($row) {
                return $row->up_vote ?  $row->up_vote : 0;
            });
            $table->editColumn('down_vote', function ($row) {
                return $row->down_vote ? $row->down_vote : 0;
            });

            $table->rawColumns(['actions', 'placeholder', 'category_category_name', 'city']);

            return $table->make(true);
        }

        $categories     = Category::get();

        return view('website.helpline',compact('categories'));
    }

}
