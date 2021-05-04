@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.city.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.cities.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.city.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="latitude">{{ trans('cruds.city.fields.latitude') }}</label>
                <input class="form-control {{ $errors->has('latitude') ? 'is-invalid' : '' }}" type="text" name="latitude" id="latitude" value="{{ old('latitude', '') }}">
                @if($errors->has('latitude'))
                    <span class="text-danger">{{ $errors->first('latitude') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.latitude_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="longitude">{{ trans('cruds.city.fields.longitude') }}</label>
                <input class="form-control {{ $errors->has('longitude') ? 'is-invalid' : '' }}" type="text" name="longitude" id="longitude" value="{{ old('longitude', '') }}">
                @if($errors->has('longitude'))
                    <span class="text-danger">{{ $errors->first('longitude') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.longitude_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="population">{{ trans('cruds.city.fields.population') }}</label>
                <input class="form-control {{ $errors->has('population') ? 'is-invalid' : '' }}" type="text" name="population" id="population" value="{{ old('population', '') }}">
                @if($errors->has('population'))
                    <span class="text-danger">{{ $errors->first('population') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.population_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="state_code">{{ trans('cruds.city.fields.state_code') }}</label>
                <input class="form-control {{ $errors->has('state_code') ? 'is-invalid' : '' }}" type="text" name="state_code" id="state_code" value="{{ old('state_code', '') }}" required>
                @if($errors->has('state_code'))
                    <span class="text-danger">{{ $errors->first('state_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.state_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="country_code">{{ trans('cruds.city.fields.country_code') }}</label>
                <input class="form-control {{ $errors->has('country_code') ? 'is-invalid' : '' }}" type="text" name="country_code" id="country_code" value="{{ old('country_code', '') }}">
                @if($errors->has('country_code'))
                    <span class="text-danger">{{ $errors->first('country_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.country_code_helper') }}</span>
            </div>
            <div class="form-group">
                <button class="btn btn-danger" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </form>
    </div>
</div>



@endsection
