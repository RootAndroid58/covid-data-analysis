@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.city.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.cities.update", [$city->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.city.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $city->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="lat">{{ trans('cruds.city.fields.lat') }}</label>
                <input class="form-control {{ $errors->has('lat') ? 'is-invalid' : '' }}" type="text" name="lat" id="lat" value="{{ old('lat', $city->lat) }}">
                @if($errors->has('lat'))
                    <span class="text-danger">{{ $errors->first('lat') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.lat_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="lng">{{ trans('cruds.city.fields.lng') }}</label>
                <input class="form-control {{ $errors->has('lng') ? 'is-invalid' : '' }}" type="text" name="lng" id="lng" value="{{ old('lng', $city->lng) }}">
                @if($errors->has('lng'))
                    <span class="text-danger">{{ $errors->first('lng') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.lng_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="population">{{ trans('cruds.city.fields.population') }}</label>
                <input class="form-control {{ $errors->has('population') ? 'is-invalid' : '' }}" type="text" name="population" id="population" value="{{ old('population', $city->population) }}">
                @if($errors->has('population'))
                    <span class="text-danger">{{ $errors->first('population') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.population_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="state_code">{{ trans('cruds.city.fields.state_code') }}</label>
                <input class="form-control {{ $errors->has('state_code') ? 'is-invalid' : '' }}" type="text" name="state_code" id="state_code" value="{{ old('state_code', $city->state_code) }}" required>
                @if($errors->has('state_code'))
                    <span class="text-danger">{{ $errors->first('state_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.city.fields.state_code_helper') }}</span>
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
