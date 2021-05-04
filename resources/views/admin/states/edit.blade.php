@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.edit') }} {{ trans('cruds.state.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.states.update", [$state->id]) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.state.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', $state->name) }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="country_code">{{ trans('cruds.state.fields.country_code') }}</label>
                <input class="form-control {{ $errors->has('country_code') ? 'is-invalid' : '' }}" type="text" name="country_code" id="country_code" value="{{ old('country_code', $state->country_code) }}">
                @if($errors->has('country_code'))
                    <span class="text-danger">{{ $errors->first('country_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.country_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="state_code">{{ trans('cruds.state.fields.state_code') }}</label>
                <input class="form-control {{ $errors->has('state_code') ? 'is-invalid' : '' }}" type="text" name="state_code" id="state_code" value="{{ old('state_code', $state->state_code) }}">
                @if($errors->has('state_code'))
                    <span class="text-danger">{{ $errors->first('state_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.state_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="latitude">{{ trans('cruds.state.fields.latitude') }}</label>
                <input class="form-control {{ $errors->has('latitude') ? 'is-invalid' : '' }}" type="text" name="latitude" id="latitude" value="{{ old('latitude', $state->latitude) }}">
                @if($errors->has('latitude'))
                    <span class="text-danger">{{ $errors->first('latitude') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.latitude_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="longitude">{{ trans('cruds.state.fields.longitude') }}</label>
                <input class="form-control {{ $errors->has('longitude') ? 'is-invalid' : '' }}" type="text" name="longitude" id="longitude" value="{{ old('longitude', $state->longitude) }}">
                @if($errors->has('longitude'))
                    <span class="text-danger">{{ $errors->first('longitude') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.longitude_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="cities">{{ trans('cruds.state.fields.city') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('cities') ? 'is-invalid' : '' }}" name="cities[]" id="cities" multiple>
                    @foreach($cities as $id => $city)
                        <option value="{{ $id }}" {{ (in_array($id, old('cities', [])) || $state->cities->contains($id)) ? 'selected' : '' }}>{{ $city }}</option>
                    @endforeach
                </select>
                @if($errors->has('cities'))
                    <span class="text-danger">{{ $errors->first('cities') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.city_helper') }}</span>
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
