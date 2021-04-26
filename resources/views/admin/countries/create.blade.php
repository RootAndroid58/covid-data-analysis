@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.country.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.countries.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.country.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="slug">{{ trans('cruds.country.fields.slug') }}</label>
                <input class="form-control {{ $errors->has('slug') ? 'is-invalid' : '' }}" type="text" name="slug" id="slug" value="{{ old('slug', '') }}" required>
                @if($errors->has('slug'))
                    <span class="text-danger">{{ $errors->first('slug') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.slug_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_code">{{ trans('cruds.country.fields.phone_code') }}</label>
                <input class="form-control {{ $errors->has('phone_code') ? 'is-invalid' : '' }}" type="text" name="phone_code" id="phone_code" value="{{ old('phone_code', '') }}" required>
                @if($errors->has('phone_code'))
                    <span class="text-danger">{{ $errors->first('phone_code') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.phone_code_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="region">{{ trans('cruds.country.fields.region') }}</label>
                <input class="form-control {{ $errors->has('region') ? 'is-invalid' : '' }}" type="text" name="region" id="region" value="{{ old('region', '') }}" required>
                @if($errors->has('region'))
                    <span class="text-danger">{{ $errors->first('region') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.region_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="subregion">{{ trans('cruds.country.fields.subregion') }}</label>
                <input class="form-control {{ $errors->has('subregion') ? 'is-invalid' : '' }}" type="text" name="subregion" id="subregion" value="{{ old('subregion', '') }}" required>
                @if($errors->has('subregion'))
                    <span class="text-danger">{{ $errors->first('subregion') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.country.fields.subregion_helper') }}</span>
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
