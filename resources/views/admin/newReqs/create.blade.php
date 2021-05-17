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
                <label class="required">{{ trans('cruds.newReq.fields.model') }}</label>
                <select class="form-control {{ $errors->has('model') ? 'is-invalid' : '' }}" name="model" id="model" required>
                    <option value disabled {{ old('model', null) === null ? 'selected' : '' }}>{{ trans('global.pleaseSelect') }}</option>
                    @foreach(App\Models\NewReq::MODEL_SELECT as $key => $label)
                        <option value="{{ $key }}" {{ old('model', '') === (string) $key ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @if($errors->has('model'))
                    <span class="text-danger">{{ $errors->first('model') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.model_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="email_id">{{ trans('cruds.newReq.fields.email') }}</label>
                {{-- <select class="form-control select2 {{ $errors->has('email') ? 'is-invalid' : '' }}" name="email_id" id="email_id" required>
                    @foreach($emails as $id => $entry)
                        <option value="{{ $id }}" {{ old('email_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select> --}}
                <input class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" type="hidden" name="email_id" id="email_id" value="{{ auth()->user()->id }}" required readonly>
                <input class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ auth()->user()->email }}" readonly>

                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="data">{{ trans('cruds.newReq.fields.data') }}</label>
                <textarea class="form-control {{ $errors->has('data') ? 'is-invalid' : '' }}" name="data" id="data" required>{{ old('data') }}</textarea>
                @if($errors->has('data'))
                    <span class="text-danger">{{ $errors->first('data') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.data_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="message">{{ trans('cruds.newReq.fields.message') }}</label>
                <input class="form-control {{ $errors->has('message') ? 'is-invalid' : '' }}" type="text" name="message" id="message" value="{{ old('message', '') }}" placeholder="Any thing you want to add? (in English only)">
                @if($errors->has('message'))
                    <span class="text-danger">{{ $errors->first('message') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.newReq.fields.message_helper') }}</span>
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
