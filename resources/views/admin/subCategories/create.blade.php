@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.subCategory.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.sub-categories.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label for="category_id">{{ trans('cruds.subCategory.fields.category') }}</label>
                <select class="form-control select2 {{ $errors->has('category') ? 'is-invalid' : '' }}" name="category_id" id="category_id">
                    @foreach($categories as $id => $entry)
                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('category'))
                    <span class="text-danger">{{ $errors->first('category') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.subCategory.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="name">{{ trans('cruds.subCategory.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}" required>
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.subCategory.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="extra">{{ trans('cruds.subCategory.fields.extra') }}</label>
                <textarea class="form-control {{ $errors->has('extra') ? 'is-invalid' : '' }}" name="extra" id="extra">{{ old('extra') }}</textarea>
                @if($errors->has('extra'))
                    <span class="text-danger">{{ $errors->first('extra') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.subCategory.fields.extra_helper') }}</span>
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
