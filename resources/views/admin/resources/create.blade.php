@extends('layouts.admin')
@section('content')

<div class="card">
    <div class="card-header">
        {{ trans('global.create') }} {{ trans('cruds.resource.title_singular') }}
    </div>

    <div class="card-body">
        <form method="POST" action="{{ route("admin.resources.store") }}" enctype="multipart/form-data">
            @csrf
            <div class="form-group">
                <label  class="required" for="categories">{{ trans('cruds.resource.fields.category') }}</label>
                <div style="padding-bottom: 4px">
                    <span class="btn btn-info btn-xs select-all" style="border-radius: 0">{{ trans('global.select_all') }}</span>
                    <span class="btn btn-info btn-xs deselect-all" style="border-radius: 0">{{ trans('global.deselect_all') }}</span>
                </div>
                <select class="form-control select2 {{ $errors->has('categories') ? 'is-invalid' : '' }}" name="categories[]" id="categories" multiple required>
                    @foreach($categories as $id => $category)
                        <option value="{{ $id }}" {{ in_array($id, old('categories', [])) ? 'selected' : '' }}>{{ $category }}</option>
                    @endforeach
                </select>
                @if($errors->has('categories'))
                    <span class="text-danger">{{ $errors->first('categories') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.category_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="country">{{ trans('cruds.country.title') }}</label>
                <select class="form-control select2 {{ $errors->has('country') ? 'is-invalid' : '' }}"name="country_id" id="country_id" required>
                    @foreach($country as $id => $entry)
                        <option value="{{ $id }}" {{ old('country') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('country'))
                    <span class="text-danger">{{ $errors->first('country') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="state">{{ trans('cruds.state.title') }}</label>
                <select class="form-control select2 {{ $errors->has('state') ? 'is-invalid' : '' }}" name="state_id" id="state_id" required>
                    @foreach($state as $id => $entry)
                        <option value="{{ $id }}" {{ old('state') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('state'))
                    <span class="text-danger">{{ $errors->first('state') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="city_id">{{ trans('cruds.resource.fields.city') }}</label>
                <select class="form-control select2 {{ $errors->has('city') ? 'is-invalid' : '' }}" name="city_id" id="city_id" required>
                    @foreach($cities as $id => $entry)
                        <option value="{{ $id }}" {{ old('city_id') == $id ? 'selected' : '' }}>{{ $entry }}</option>
                    @endforeach
                </select>
                @if($errors->has('city'))
                    <span class="text-danger">{{ $errors->first('city') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.city_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="name">{{ trans('cruds.resource.fields.name') }}</label>
                <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', '') }}">
                @if($errors->has('name'))
                    <span class="text-danger">{{ $errors->first('name') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.name_helper') }}</span>
            </div>
            <div class="form-group">
                <label class="required" for="phone_no">{{ trans('cruds.resource.fields.phone_no') }}</label>
                <input class="form-control {{ $errors->has('phone_no') ? 'is-invalid' : '' }}" type="text" name="phone_no" id="phone_no" value="{{ old('phone_no', '') }}" required>
                @if($errors->has('phone_no'))
                    <span class="text-danger">{{ $errors->first('phone_no') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.phone_no_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="email">{{ trans('cruds.resource.fields.email') }}</label>
                <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', '') }}">
                @if($errors->has('email'))
                    <span class="text-danger">{{ $errors->first('email') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.email_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="address">{{ trans('cruds.resource.fields.address') }}</label>
                <textarea class="form-control {{ $errors->has('address') ? 'is-invalid' : '' }}" name="address" id="address">{{ old('address') }}</textarea>
                @if($errors->has('address'))
                    <span class="text-danger">{{ $errors->first('address') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.address_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="details">{{ trans('cruds.resource.fields.details') }}</label>
                <input class="form-control {{ $errors->has('details') ? 'is-invalid' : '' }}" type="text" name="details" id="details" value="{{ old('details', '') }}">
                @if($errors->has('details'))
                    <span class="text-danger">{{ $errors->first('details') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.details_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="note">{{ trans('cruds.resource.fields.note') }}</label>
                <textarea class="form-control {{ $errors->has('note') ? 'is-invalid' : '' }}" name="note" id="note">{{ old('note') }}</textarea>
                @if($errors->has('note'))
                    <span class="text-danger">{{ $errors->first('note') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.note_helper') }}</span>
            </div>
            <div class="form-group">
                <label for="url">{{ trans('cruds.resource.fields.url') }}</label>
                <input class="form-control {{ $errors->has('url') ? 'is-invalid' : '' }}" type="text" name="url" id="url" value="{{ old('url', '') }}">
                @if($errors->has('url'))
                    <span class="text-danger">{{ $errors->first('url') }}</span>
                @endif
                <span class="help-block">{{ trans('cruds.resource.fields.url_helper') }}</span>
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

@section('scripts')
    <script>
        $('#country_id').on('change', function (e){
            e.preventDefault();

            var country = $(this).val();

            if(country){
                let stateBody = "";
                stateBody += "<option>Select State</option>\n";

                $.ajax({
                    url: "{{ route('api.StateById') }}",
                    type: "POST",
                    data:{
                        country_id: country
                    },
                    success: function (data) {

                        data['states'].forEach(element => {
                            stateBody += `<option value="${element["id"]}">${element['name']}</option>\n`
                        });
                        $('#state_id').html(stateBody);
                    }
                })
            }else{
                $('#state_id').html("<option>Please Select</option>");
                $('#city_id').html("<option>Please Select</option>");
            }
        })
        $('#state_id').on('change', function (e){
            e.preventDefault();

            var state = $(this).val();

            if(state){
                let cityBody = "";
                cityBody += "<option>Select City</option>\n";

                $.ajax({
                    url: "{{ route('api.CityById') }}",
                    type: "POST",
                    data:{
                        state_id: state,
                    },
                    success: function (data) {
                        cityBody += `<option value="0">All Cities</option>\n`
                        data['cities'].forEach(element => {
                            // console.log(element);
                            cityBody += `<option value="${element["id"]}">${element['name']}</option>\n`
                        });
                        $('#city_id').html(cityBody);
                    }
                })
            }else{
                $('#city_id').html("<option>Please Select</option>");
            }
        })

    </script>
@endsection
