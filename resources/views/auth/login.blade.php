@extends('layouts.app')
@section('content')
<div class="login-box">
    <div class="login-logo">
        <div class="login-logo">
            <a href="{{ route('admin.home') }}">
                {{ trans('panel.site_title') }}
            </a>
        </div>
    </div>
    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">
                {{ trans('global.login') }}
            </p>

            @if(session()->has('message'))
                <p class="alert alert-info">
                    {{ session()->get('message') }}
                </p>
            @endif

            <form action="{{ route('login') }}" method="POST">
                @csrf

                <div class="form-group">
                    <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" required autocomplete="email" autofocus placeholder="{{ trans('global.login_email') }}" name="email" value="{{ old('email', null) }}">

                    @if($errors->has('email'))
                        <div class="invalid-feedback">
                            {{ $errors->first('email') }}
                        </div>
                    @endif
                </div>

                <div class="form-group">
                    <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required placeholder="{{ trans('global.login_password') }}">

                    @if($errors->has('password'))
                        <div class="invalid-feedback">
                            {{ $errors->first('password') }}
                        </div>
                    @endif
                </div>


                <div class="row">
                    <div class="col-8">
                        <div class="icheck-primary">
                            <input type="checkbox" name="remember" id="remember">
                            <label for="remember">{{ trans('global.remember_me') }}</label>
                        </div>
                    </div>
                    <!-- /.col -->
                    <div class="col-4">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            {{ trans('global.login') }}
                        </button>
                    </div>
                    <!-- /.col -->
                </div>
            </form>


            @if(Route::has('password.request'))
                <p class="mb-1">
                    <a href="{{ route('password.request') }}">
                        {{ trans('global.forgot_password') }}
                    </a>
                </p>
            @endif
            <p class="mb-1 text-center">
                OR <br>
                Signin / Signup with Social Account

            </p>
            <div class="col">
                <div class="row text-center justify-content-center">
                    <p class="mb-1 text-center ml-2">
                        <a class="btn btn-danger" href="{{ url('/login/google') }}" title="Login with Google"><i class="fab fa-google"></i></a>
                    </p>
                    <p class="mb-1 text-center ml-2">
                        <a class="btn btn-dark" href="{{ url('/login/github') }}" title="Login with Github"><i class="fab fa-github"></i></a>
                    </p>
                    <p class="mb-1 text-center ml-2">
                        <a class="btn" href="{{ url('/login/facebook') }}" style="background: #3b5998; color: white;" title="Login with Facebook"><i class="fab fa-facebook"></i></a>
                    </p>
                    <p class="mb-1 text-center ml-2">
                        <a class="btn" href="{{ url('/login/twitter') }}" style="background: #55acee; color: white;" title="Login with Twitter"><i class="fab fa-twitter"></i></a>
                    </p>
                    <p class="mb-1 text-center ml-2">
                        <a class="btn" href="{{ url('/login/linkedin') }}" style="background: #337ab7; color: white;" title="Login with LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                    </p>
                    <p class="mb-1 text-center ml-2">
                        <a class="btn" href="{{ url('/login/bitbucket') }}" style="background: #205081; color: white;" title="Login with Bitbucket"><i class="fab fa-bitbucket"></i></a>
                    </p>
                </div>
            </div>
        </div>
        <!-- /.login-card-body -->
    </div>
</div>
@endsection
