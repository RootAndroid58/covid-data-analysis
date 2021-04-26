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
                <label class="required" for="slug">{{ trans('cruds.state.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', $state->slug) }}" required>
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.slug_helper') }}</span>
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
                <label for="lat">{{ trans('cruds.state.fields.lat') }}</label>
                <input class="form-control {{ $errors->has('lat') ? 'is-invalid' : '' }}" type="text" name="lat" id="lat" value="{{ old('lat', $state->lat) }}">
                @if($errors->has('lat'))
                    <span class="text-danger">{{ $errors->first('lat') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.lat_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="lon">{{ trans('cruds.state.fields.lon') }}</label>
                <input class="form-control {{ $errors->has('lon') ? 'is-invalid' : '' }}" type="text" name="lon" id="lon" value="{{ old('lon', $state->lon) }}">
                @if($errors->has('lon'))
                    <span class="text-danger">{{ $errors->first('lon') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.state.fields.lon_helper') }}</span>
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
