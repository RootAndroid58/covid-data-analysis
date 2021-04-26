@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.newReq.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.new-reqs.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="catogary">{{ trans('cruds.newReq.fields.catogary') }}</label>
                <input class="form-control {{ $errors->has('catogary') ? 'is-invalid' : '' }}" type="text" name="catogary" id="catogary" value="{{ old('catogary', '') }}">
                @if($errors->has('catogary'))
                    <span class="text-danger">{{ $errors->first('catogary') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.catogary_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="country">{{ trans('cruds.newReq.fields.country') }}</label>
                <input class="form-control {{ $errors->has('country') ? 'is-invalid' : '' }}" type="text" name="country" id="country" value="{{ old('country', '') }}">
                @if($errors->has('country'))
                    <span class="text-danger">{{ $errors->first('country') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.country_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="state">{{ trans('cruds.newReq.fields.state') }}</label>
                <input class="form-control {{ $errors->has('state') ? 'is-invalid' : '' }}" type="text" name="state" id="state" value="{{ old('state', '') }}">
                @if($errors->has('state'))
                    <span class="text-danger">{{ $errors->first('state') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.state_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="city">{{ trans('cruds.newReq.fields.city') }}</label>
                <input class="form-control {{ $errors->has('city') ? 'is-invalid' : '' }}" type="text" name="city" id="city" value="{{ old('city', '') }}">
                @if($errors->has('city'))
                    <span class="text-danger">{{ $errors->first('city') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="extra">{{ trans('cruds.newReq.fields.extra') }}</label>
                <textarea class="form-control {{ $errors->has('extra') ? 'is-invalid' : '' }}" name="extra" id="extra">{{ old('extra') }}</textarea>
                @if($errors->has('extra'))
                    <span class="text-danger">{{ $errors->first('extra') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.extra_helper') }}</span>
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
