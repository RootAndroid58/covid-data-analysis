@extends('layouts.admin')
@section('content')
<div class="row">
    <div class="col-md-6">
        <div class="card" style="min-height: 96%">
            <div class="card-header">
                {{ trans('global.my_profile') }}
            </div>

            <div class="card-body">
                <form method="POST" id="name_email" action="{{ route("profile.password.updateProfile") }}">
                    @csrf
                    <div class="form-group">
                        <label class="required" for="name">{{ trans('cruds.user.fields.name') }}</label>
                        <input class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}" type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}" required>
                        @if($errors->has('name'))
                            <div class="invalid-feedback">
                                {{ $errors->first('name') }}
                            </div>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="title">{{ trans('cruds.user.fields.email') }}</label>
                        <input class="form-control {{ $errors->has('email') ? 'is-invalid' : '' }}" type="text" name="email" id="email" value="{{ old('email', auth()->user()->email) }}" required>
                        @if($errors->has('email'))
                            <div class="invalid-feedback">
                                {{ $errors->first('email') }}
                            </div>
                        @endif
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button class="btn btn-danger" form="name_email" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{ trans('global.change_password') }}
            </div>
            <div class="card-body">
                <form method="POST" id="formPassword" action="{{ route("profile.password.update") }}">
                    @csrf
                    @php
                        $user = auth()->user();
                    @endphp
                    @if ($user->password != null)
                    <div class="form-group">
                        <label class="required" for="old_password">Old {{ trans('cruds.user.fields.password') }}</label>
                        <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="old_password" id="old_password" required>
                        @if($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('old_password') }}</span>
                        @endif
                    </div>
                    @endif
                    <div class="form-group">
                        <label class="required" for="password">New {{ trans('cruds.user.fields.password') }}</label>
                        <input class="form-control {{ $errors->has('password') ? 'is-invalid' : '' }}" type="password" name="password" id="password" required>
                        @if($errors->has('password'))
                            <span class="text-danger">{{ $errors->first('password') }}</span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="required" for="password_confirmation">Repeat New {{ trans('cruds.user.fields.password') }}</label>
                        <input class="form-control {{ $errors->has('password_confirmation') ? 'is-invalid' : '' }}" type="password" name="password_confirmation" id="password_confirmation" required>
                    </div>
                </form>
            </div>
            <div class="card-footer">
                <button class="btn btn-danger" form="formPassword" type="submit">
                    {{ trans('global.save') }}
                </button>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col">
        <div class="card">
            <div class="card-header">
               Token
            </div>


            <div class="card-body">
                <div class="input-group mb-3" style="width: -webkit-fill-available">
                    <input type="text" class="form-control" name="token" id="Bearer"  value="{{ $Bearer }}" readonly>
                    <div class="input-group-append">
                        <button class="btn btn-outline-secondary" onclick="copyToClipboard('#Bearer')" type="button">copy</button>
                    </div>
                </div>
                <p class="text-danger">Note: once the page reloads you wont be able to copy the token ever again</p>
            </div>
            <div class="card-footer">
                <button class="btn btn-success" form="newToken" type="submit">
                    Generate/ Regenerate Token
                </button>
                <button class="btn btn-danger float-right" form="removeToken"  type="submit">
                    Delete Token
                </button>
                <form id="newToken">
                    @csrf
                </form>
                <form method="POST" id="removeToken" action="{{ route("profile.password.removeToken") }}">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</div>
<div class="row">
    @can('user_management_access')
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                {{ trans('global.delete_account') }}
            </div>

            <div class="card-body">
                <form method="POST" action="{{ route("profile.password.destroyProfile") }}" onsubmit="return prompt('{{ __('global.delete_account_warning') }}') == '{{ auth()->user()->email }}'">
                    @csrf
                    <div class="form-group">
                        <button class="btn btn-danger" type="submit">
                            {{ trans('global.delete') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endcan
    @if(Route::has('profile.password.toggleTwoFactor'))
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ trans('global.two_factor.title') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route("profile.password.toggleTwoFactor") }}">
                        @csrf
                        <div class="form-group">
                            <button class="btn btn-danger" type="submit">
                                {{ auth()->user()->two_factor ? trans('global.two_factor.disable') : trans('global.two_factor.enable') }}
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        @endif

</div>

@endsection

@section('scripts')
    <script>
        function copyToClipboard(element) {
            $(element).focus().select()
            document.execCommand("copy");
        }
    </script>
    <script type="text/javascript">
        $(document).ready(function(){
            console.log('ready')
            $("#newToken").on("submit", function(event){
                event.preventDefault();
                console.log('submit');
                var formValues= $('#newToken').serialize();

                $.post("{{ route("profile.password.createToken") }}", formValues, function(data){
                    $('#Bearer').val(data);
                });
            });
        });
    </script>
@endsection
