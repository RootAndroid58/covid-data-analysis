<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Traits\CsvImportTrait;
use App\Http\Requests\MassDestroyCountryRequest;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;
use App\Models\Country;
use App\Models\State;
use Gate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Yajra\DataTables\Facades\DataTables;

class CountriesController extends Controller
{
    use CsvImportTrait;

    public function index(Request $request)
    {
        abort_if(Gate::denies('country_access'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        if ($request->ajax()) {
            $query = Country::with(['states'])->select(sprintf('%s.*', (new Country())->table));
            $table = Datatables::of($query);

            $table->addColumn('placeholder', '&nbsp;');
            $table->addColumn('actions', '&nbsp;');

            $table->editColumn('actions', function ($row) {
                $viewGate = 'country_show';
                $editGate = 'country_edit';
                $deleteGate = 'country_delete';
                $crudRoutePart = 'countries';

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
            $table->editColumn('name', function ($row) {
                return $row->name ? $row->name : '';
            });
            $table->editColumn('capital', function ($row) {
                return $row->capital ? $row->capital : '';
            });
            $table->editColumn('code', function ($row) {
                return $row->code ? $row->code : '';
            });
            $table->editColumn('phone_code', function ($row) {
                return $row->phone_code ? $row->phone_code : '';
            });
            $table->editColumn('region', function ($row) {
                return $row->region ? $row->region : '';
            });
            $table->editColumn('subregion', function ($row) {
                return $row->subregion ? $row->subregion : '';
            });
            $table->editColumn('state', function ($row) {
                $labels = [];
                foreach ($row->states as $state) {
                    $labels[] = sprintf('<span class="label label-info label-many">%s</span>', $state->name);
                }

                return implode(' ', $labels);
            });

            $table->rawColumns(['actions', 'placeholder', 'state']);

            return $table->make(true);
        }

        return view('admin.countries.index');
    }

    public function create()
    {
        abort_if(Gate::denies('country_create'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $states = State::all()->pluck('name', 'id');

        return view('admin.countries.create', compact('states'));
    }

    public function store(StoreCountryRequest $request)
    {
        $country = Country::create($request->all());
        $country->states()->sync($request->input('states', []));

        return redirect()->route('admin.countries.index');
    }

    public function edit(Country $country)
    {
        abort_if(Gate::denies('country_edit'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $states = State::all()->pluck('name', 'id');

        $country->load('states');

        return view('admin.countries.edit', compact('states', 'country'));
    }

    public function update(UpdateCountryRequest $request, Country $country)
    {
        $country->update($request->all());
        $country->states()->sync($request->input('states', []));

        return redirect()->route('admin.countries.index');
    }

    public function show(Country $country)
    {
        abort_if(Gate::denies('country_show'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $country->load('states');

        return view('admin.countries.show', compact('country'));
    }

    public function destroy(Country $country)
    {
        abort_if(Gate::denies('country_delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        $country->delete();

        return back();
    }

    public function massDestroy(MassDestroyCountryRequest $request)
    {
        Country::whereIn('id', request('ids'))->delete();

        return response(null, Response::HTTP_NO_CONTENT);
    }
}
