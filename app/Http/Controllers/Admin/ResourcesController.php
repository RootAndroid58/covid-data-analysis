<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MassDestroyResourceRequest;
use App\Http\Requests\StoreResourceRequest;
use App\Http\Requests\UpdateResourceRequest;
use App\Models\City;
use App\Models\State;
use App\Models\Country;
use App\Models\Resource;
use App\Models\Category;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class ResourcesController extends Controller
{
    public function index(Request $request)
    {
        abort_if(Gate::denies('resource_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Resource::with(['categories','country', 'state', 'city'])->select(sprintf('%s.*', (new Resource())->table));
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
            $table->editColumn('category', function ($row) {
                $labels = [];
                foreach ($row->categories as $category) {
                    $labels[] = sprintf('<span class="badge badge-info ">%s</span>', $category->name);
                }

                return implode(' ', $labels);
            });
            $table->addColumn('city_name', function ($row) {
                return $row->city ? $row->city->name : '';
            });

            $table->editColumn('city.population', function ($row) {
                return $row->city ? (is_string($row->city) ? $row->city : $row->city->population) : '';
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
                return $row->up_vote == '' ? 0 : $row->up_vote;
            });
            $table->editColumn('down_vote', function ($row) {
                return $row->down_vote == '' ? 0 : $row->down_vote ;
            });

            $table->rawColumns(['actions', 'placeholder', 'category', 'city']);

            return $table->make(true);
        }

        $categories = Category::get();

        // $cities = City::get();
        $cities = array(trans('global.pleaseSelect') => '');

        return view('admin.resources.index', compact('cities','categories'));
    }

    public function create(Request $request)
    {
        abort_if(Gate::denies('resource_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if($request->ajax()){
            dd($request);
        }

        $categories = Category::all()->pluck('name', 'id');

        $country = Country::with('states')->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $state = State::take(0)->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        $cities = City::take(0)->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.resources.create', compact('country','cities','state','categories'));
    }

    public function store(StoreResourceRequest $request)
    {
        $resource = Resource::create($request->all());

        $resource->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.resources.index');
    }

    public function edit(Resource $resource)
    {
        abort_if(Gate::denies('resource_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $categories = Category::all()->pluck('name', 'id');
        $resource->load('city');
        $cities = City::where('id',$resource->city_id)->get()->pluck('name', 'id')->prepend(trans('global.pleaseSelect'), '');

        return view('admin.resources.edit', compact('categories', 'cities', 'resource'));
    }

    public function update(UpdateResourceRequest $request, Resource $resource)
    {
        $resource->update($request->all());

        $resource->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.resources.index');
    }

    public function show(Resource $resource)
    {
        abort_if(Gate::denies('resource_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resource->load('city');

        return view('admin.resources.show', compact('resource'));
    }

    public function destroy(Resource $resource)
    {
        abort_if(Gate::denies('resource_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $resource->delete();

        return back();
    }

    public function massDestroy(MassDestroyResourceRequest $request)
    {
        Resource::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
