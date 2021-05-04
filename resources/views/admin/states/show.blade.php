@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.show') }} {{ trans('cruds.state.title') }}
    </div>

    <div class="card-body">
        <div class="form-group">
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.states.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
            <table class="table table-bordered table-striped">
                <tbody>
                    <tr>
                        <th>
                            {{ trans('cruds.state.fields.id') }}
                        </th>
                        <td>
                            {{ $state->id }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.state.fields.name') }}
                        </th>
                        <td>
                            {{ $state->name }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.state.fields.country_code') }}
                        </th>
                        <td>
                            {{ $state->country_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.state.fields.state_code') }}
                        </th>
                        <td>
                            {{ $state->state_code }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.state.fields.latitude') }}
                        </th>
                        <td>
                            {{ $state->latitude }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.state.fields.longitude') }}
                        </th>
                        <td>
                            {{ $state->longitude }}
                        </td>
                    </tr>
                    <tr>
                        <th>
                            {{ trans('cruds.state.fields.city') }}
                        </th>
                        <td>
                            @foreach($state->cities as $key => $city)
                                <span class="label label-info">{{ $city->name }}</span>
                            @endforeach
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="form-group">
                <a class="btn btn-default" href="{{ route('admin.states.index') }}">
                    {{ trans('global.back_to_list') }}
                </a>
            </div>
        </div>
    </div>
</div>



@endsection
