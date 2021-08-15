{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <div class="postman-run-button"
data-postman-action="collection/fork"
data-postman-var-1="13642074-7fad3869-3032-45b7-bc83-e8213302b87e"
data-postman-collection-url="entityId=13642074-7fad3869-3032-45b7-bc83-e8213302b87e&entityType=collection"></div>
<script type="text/javascript">
  (function (p,o,s,t,m,a,n) {
    !p[s] && (p[s] = function () { (p[t] || (p[t] = [])).push(arguments); });
    !o.getElementById(s+t) && o.getElementsByTagName("head")[0].appendChild((
      (n = o.createElement("script")),
      (n.id = s+t), (n.async = 1), (n.src = m), n
    ));
  }(window, document, "_pm", "PostmanRunObject", "https://run.pstmn.io/button.js"));
</script>
</body>
</html> --}}

{{-- <!DOCTYPE html>
<html>
<head>
    <title>CRADA API documentation</title>
    <!-- needed for adaptive design -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://fonts.googleapis.com/css?family=Montserrat:300,400,700|Roboto:300,400,700" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icomoon-style.css')}}">

    <!--
    ReDoc doesn't change outer page styles
    -->
    <style>
        body {
            margin: 0;
            padding: 0;
        }
    </style>
</head>
<body>
    <div class="site-wrap">
        <div class="site-mobile-menu site-navbar-target">
            <div class="site-mobile-menu-header">
                <div class="site-mobile-menu-close mt-3">
                    <span class="icon-close2 js-menu-toggle"></span>
                </div>
            </div>
            <div class="site-mobile-menu-body"></div>
        </div>
        <header class="site-navbar light js-sticky-header site-navbar-target" role="banner">
            <div class="container">
                <div class="row align-items-center justify-content-center">
                    <div class="col" style="flex-basis: unset !important; width: auto  !important;">
                        <div class="mb-0 site-logo logo"><a href="{{ route('homepage') }}" class="mb-0">
                                <logo class="text-primary">CRADA</logo>
                            </a></div>
                    </div>
                    <div class="col d-none d-xl-block" style="flex-basis: unset !important;width: auto  !important;">
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                                <li class="{{ request()->is("/") ? "active" : "" }}"><a href="{{ route('homepage') }}#"
                                        class="nav-link">Home</a></li>
                                <li class="has-children">
                                    <a href="#" class="nav-link">Analysis</a>
                                    <ul class="dropdown">
                                        <li><a href="{{ route('apple_trends') }}" class="nav-link">Apple Trends</a></li>
                                        <li><a href="{{ route('worldometer') }}" class="nav-link">Worldometer</a></li>
                                        <li><a href="{{ route('status') }}#" class="nav-link">Status</a></li>
                                    </ul>
                                </li>
                                <li><a href="{{ route('helpline') }}#" class="nav-link">Help Line</a></li>
                                <li><a href="#" class="nav-link">Contact</a></li>
                                @if (Route::has('login'))
                                @auth
                                <li>
                                    <a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                                </li>
                                @else
                                <li>
                                    <a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                                </li>

                                @if (Route::has('register'))
                                <li><a href="{{ route('register') }}"
                                        class="ml-4 text-sm text-gray-700 underline">Register</a></li>
                                @endif
                                @endauth
                                @endif

                                <li class="btn btn-sm btn-primary" style="padding: 0">
                                    <a href="{{ route('apidoc') }}"
                                        class="nav-link" style="color: white !important;padding: 10px;">API documentation
                                    </a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col d-inline-block d-xl-none ml-md-0 py-3" style="position: relative; top: 3px;flex-basis: unset !important;width: auto  !important;"><a
                            href="#" class="site-menu-toggle js-menu-toggle float-right"><span
                                class="icon-menu h3 text-black"></span></a></div>
                </div>
            </div>
        </header>

        <div class="site-section bg-primary-light">
            <redoc spec-url='{{ asset('doc/crada-doc.yaml') }}'></redoc>
        </div>
        <div class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <h2 class="footer-heading mb-4">About</h2>
                        <p>CRADA is an initiative for common people to provide them with various
                            essential resources information in their city .</p>
                        <div class="my-5">
                            <a href="#" class="pl-0 pr-3"><span class="icon-facebook"></span></a>
                            <a href="#" class="pl-3 pr-3"><span class="icon-twitter"></span></a>
                            <a href="#" class="pl-3 pr-3"><span class="icon-instagram"></span></a>
                            <a href="#" class="pl-3 pr-3"><span class="icon-linkedin"></span></a>
                        </div>
                    </div>
                    <div class="col-lg-8">
                        <div class="row">
                            <div class="col-lg-4">
                                <h2 class="footer-heading mb-4">Quick Links</h2>
                                <ul class="list-unstyled">
                                    <li><a href="#">Symptoms</a></li>
                                    <li><a href="#">Prevention</a></li>
                                    <li><a href="#">FAQs</a></li>
                                    <li><a href="#">About Coronavirus</a></li>
                                    <li><a href="#">Contact Us</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <h2 class="footer-heading mb-4">Helpful Link</h2>
                                <ul class="list-unstyled">
                                    <li><a href="#">Helathcare Professional</a></li>
                                    <li><a href="#">LGU Facilities</a></li>
                                    <li><a href="#">Protect Your Family</a></li>
                                    <li><a href="#">World Health</a></li>
                                </ul>
                            </div>
                            <div class="col-lg-4">
                                <h2 class="footer-heading mb-4">Resources</h2>
                                <ul class="list-unstyled">
                                    <li><a href="#">WHO Website</a></li>
                                    <li><a href="#">CDC Website</a></li>
                                    <li><a href="#">Gov Website</a></li>
                                    <li><a href="#">DOH Website</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row text-center">
                    <div class="col-md-12">
                        <div class="border-top pt-5">
                            <p class="copyright">
                                <small>
                                    Copyright &copy;
                                    <script>
                                        document.write(new Date().getFullYear());

                                    </script>
                                </small></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>





<script src="https://cdn.jsdelivr.net/npm/redoc@next/bundles/redoc.standalone.js"> </script>
<script src="{{ asset('js/try.js') }}"></script>
<script>
    initTry("{{ asset('doc/crada-doc.yaml') }}")
  </script>
</body>
</html> --}}



@extends('layouts.frontend')

@section('content')
<div class="site-section bg-primary-light">
    <redoc spec-url='{{ asset('doc/crada-doc.yaml') }}'></redoc>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/redoc@next/bundles/redoc.standalone.js"> </script>
@endsection

@section('title')
    CRADA API documentation
@endsection
