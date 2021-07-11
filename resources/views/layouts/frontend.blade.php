<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;700;900&amp;display=swap" rel="stylesheet">
    <title>CRADA</title>

    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <link rel="stylesheet" href="{{ asset('css/icomoon-style.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('css/css-bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/css-jquery-ui.css')}}"> --}}
    <link rel="stylesheet" href="{{ asset('css/css-owl.carousel.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/css-owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/css-owl.theme.default.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/css-jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{ asset('css/css-bootstrap-datepicker.css')}}">
    <link rel="stylesheet" href="{{ asset('css/font-flaticon.css')}}">
    <link rel="stylesheet" href="{{ asset('css/css-aos.css')}}">
    {{-- <link rel="stylesheet" href="{{ asset('css/css-style.css')}}"> --}}
    <style>
        .logo a:hover:before {
            content: 'Covid Resources And Data Analysis';
            color: #b81e2d;
        }

        .logo a:hover logo {
            display: none;
        }
        .logo a logo {
            color: #bc1d2c !important;
        }

    </style>
    @livewireStyles
    @livewireScripts
    @yield('style')
</head>

<body data-spy="scroll" data-target=".site-navbar-target" data-offset="300">
    <div id="overlayer"></div>
    <div class="loader">
        <div class="spinner-border text-primary" role="status">
            <span class="sr-only">Loading...</span>
        </div>
    </div>
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
                                        {{-- <li><a href="#" class="nav-link">Keep social distancing</a></li>
                                        <li><a href="#" class="nav-link">Wear facemasl</a></li>
                                        <li><a href="#" class="nav-link">Wash your hands</a></li>
                                        <li class="has-children">
                                            <a href="#">More Links</a>
                                            <ul class="dropdown">
                                                <li><a href="#">Menu One</a></li>
                                                <li><a href="#">Menu Two</a></li>
                                                <li><a href="#">Menu Three</a></li>
                                            </ul>
                                        </li> --}}
                                    </ul>
                                </li>
                                {{-- <li><a href="#" class="nav-link">Symptoms</a></li>
                                <li><a href="#" class="nav-link">About</a></li> --}}
                                <li><a href="{{ route('status') }}#" class="nav-link">Status</a></li>
                                <li><a href="#" class="nav-link">Help Line</a></li>
                                <li><a href="#" class="nav-link">Contact</a></li>
                                @if (Route::has('login'))
                                @auth
                                <li><a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Dashboard</a>
                                </li>
                                @else
                                <li><a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a>
                                </li>

                                @if (Route::has('register'))
                                <li><a href="{{ route('register') }}"
                                        class="ml-4 text-sm text-gray-700 underline">Register</a></li>
                                @endif
                                @endauth
                                @endif

                                <li class="btn btn-sm btn-primary" style="padding: 0"><a href="{{ route('apidoc') }}"
                                        class="nav-link" style="color: white !important;    padding: 10px;">Api
                                        doc</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col d-inline-block d-xl-none ml-md-0 py-3" style="position: relative; top: 3px;flex-basis: unset !important;width: auto  !important;"><a
                            href="#" class="site-menu-toggle js-menu-toggle float-right"><span
                                class="icon-menu h3 text-black"></span></a></div>
                </div>
            </div>
        </header>

        @yield('content')
        <div class="site-footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-4">
                        <h2 class="footer-heading mb-4">About</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Modi cumque tenetur inventore
                            veniam, hic vel ipsa necessitatibus ducimus architecto fugiat!</p>
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

    <script src="{{ asset('js/frontend.js') }}"></script>
    <script src="{{ asset('js/owl.carousel.min.js')}}"></script>
    <script src="{{ asset('js/jquery.countdown.min.js')}}"></script>
    <script src="{{ asset('js/jquery.easing.1.3.js')}}"></script>
    <script src="{{ asset('js/aos.js') }}"></script>
    <script src="{{ asset('js/jquery.fancybox.min.js')}}"></script>
    <script src="{{ asset('js/jquery.sticky.js')}}"></script>
    <script src="{{ asset('js/isotope.pkgd.min.js')}}"></script>
    <script src="{{ asset('js/forntend-main.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/loader.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/charts-loader.js') }}"></script>
    <script>
        const googleChartsLoaded = false;

        function loadGoogleCharts() {
            return new Promise((resolve) => {
                if (googleChartsLoaded) {
                    resolve();
                } else {
                    google.charts.load('current', {
                        packages: ['line', 'corechart', 'geochart'],
                        mapsApiKey: 'AIzaSyCRPsE6IF9W1LwKoEPswADfHzQBErW6uCU'
                        // mapsApiKey: 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
                    });
                    // google.charts.load('current', {'packages':['corechart']});
                    google.charts.setOnLoadCallback(resolve);
                }
            });
        }

        async function drawworldometerMap(dataset,type,id,Showtype) {
        await loadGoogleCharts();

        const data = new google.visualization.DataTable();
        // console.log(dataset);
        data.addColumn('string', 'Country');
        data.addColumn('number', 'cases');
        data.addColumn({type: 'string', role:'tooltip', p:{html:true} });

        if(dataset){
        dataset.forEach((val,key) => {

            let cases = Number((val['timeline'][type]['cases']).replaceAll(',',''));
            let deaths = Number((val['timeline'][type]['deaths']).replaceAll(',',''));
            let recovered = Number((val['timeline'][type]['recovered']).replaceAll(',',''));
            let active = Number((val['timeline'][type]['active']).replaceAll(',',''));
            if( isNaN(active)){
                active = 0
            }
            let show = Number((val['timeline'][type][Showtype]).replaceAll(',',''));
            if(isNaN(show)){
                show = 0
            }
            data.addRows([
                [
                    { v:val['iso2'] ,f:`${val['country']} [${val['iso2']}]`},
                    show,
                    `<b>${type.toUpperCase()}</b><br>Active: ${active}<br>Cases: ${cases} <br>Deaths: ${deaths}<br> Recovered: ${recovered}`
                ]
            ]);
        })
    }

        var options = {
            title: "Worldometer's World's Stats",
            tooltip: {
                isHtml: true,
                // trigger: 'selection'
            },
            backgroundColor: "#f8f5fc",
            colors: ['#6f42c1']
        };
        const chart = new google.visualization.GeoChart(document.getElementById(id));

        chart.draw(data, options);
      }

    </script>

    @yield('scripts')
</body>

</html>
