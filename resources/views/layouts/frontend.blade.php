<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css2?family=Mulish:wght@400;700;900&amp;display=swap" rel="stylesheet">
    <title>CRADA</title>
    <livewire:styles />
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <link rel="stylesheet" href="css/icomoon-style.css">
    {{-- <link rel="stylesheet" href="css/css-bootstrap.min.css">
    <link rel="stylesheet" href="css/css-jquery-ui.css"> --}}
    <link rel="stylesheet" href="css/css-owl.carousel.min.css">
    <link rel="stylesheet" href="css/css-owl.theme.default.min.css">
    <link rel="stylesheet" href="css/css-owl.theme.default.min.css">
    <link rel="stylesheet" href="css/css-jquery.fancybox.min.css">
    <link rel="stylesheet" href="css/css-bootstrap-datepicker.css">
    <link rel="stylesheet" href="css/font-flaticon.css">
    <link rel="stylesheet" href="css/css-aos.css">
    <link rel="stylesheet" href="css/css-style.css">
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
                <div class="row align-items-center">
                    <div class="col-6 col-xl-2">
                        <div class="mb-0 site-logo"><a href="{{ route('homepage') }}" class="mb-0">CRADA<span
                                    class="text-primary">.</span> </a></div>
                    </div>
                    <div class="col-12 col-md-10 d-none d-xl-block">
                        <nav class="site-navigation position-relative text-right" role="navigation">
                            <ul class="site-menu main-menu js-clone-nav mr-auto d-none d-lg-block">
                                <li class="active"><a href="{{ route('homepage') }}#" class="nav-link">Home</a></li>
                                {{-- <li class="has-children">
                                    <a href="#" class="nav-link">Prevention</a>
                                    <ul class="dropdown">
                                        <li><a href="#" class="nav-link">Stay at home</a></li>
                                        <li><a href="#" class="nav-link">Keep social distancing</a></li>
                                        <li><a href="#" class="nav-link">Wear facemasl</a></li>
                                        <li><a href="#" class="nav-link">Wash your hands</a></li>
                                        <li class="has-children">
                                            <a href="#">More Links</a>
                                            <ul class="dropdown">
                                                <li><a href="#">Menu One</a></li>
                                                <li><a href="#">Menu Two</a></li>
                                                <li><a href="#">Menu Three</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a href="#" class="nav-link">Symptoms</a></li>
                                <li><a href="#" class="nav-link">About</a></li> --}}
                                <li><a href="#" class="nav-link">Status</a></li>
                                <li><a href="#" class="nav-link">Help Line</a></li>
                                <li><a href="#" class="nav-link">Contact</a></li>
                                @if (Route::has('login'))
                                    @auth
                                    <li><a href="{{ url('/home') }}" class="text-sm text-gray-700 underline">Dashboard</a></li>
                                    @else
                                    <li><a href="{{ route('login') }}" class="text-sm text-gray-700 underline">Log in</a></li>

                                        @if (Route::has('register'))
                                        <li><a href="{{ route('register') }}" class="ml-4 text-sm text-gray-700 underline">Register</a></li>
                                        @endif
                                    @endauth
                                @endif

                                <li class="btn btn-sm btn-primary" style="padding: 0"><a href="{{ route('apidoc') }}" class="nav-link" style="color: white !important;    padding: 10px;">Api documentation</a></li>
                            </ul>
                        </nav>
                    </div>
                    <div class="col-6 d-inline-block d-xl-none ml-md-0 py-3" style="position: relative; top: 3px;"><a
                            href="#" class="site-menu-toggle js-menu-toggle float-right"><span
                                class="icon-menu h3 text-black"></span></a></div>
                </div>
            </div>
        </header>
        <div class="hero-v1">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 mr-auto text-center text-lg-left">
                        <span class="d-block subheading">Covid-19 Awareness</span>
                        <h1 class="heading mb-3">Stay Safe. Stay Home.</h1>
                        <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Vel a, nulla incidunt
                            eaque sit praesentium porro consectetur optio!</p>
                        <p class="mb-4"><a href="#" class="btn btn-primary" style="z-index: 10">How to prevent</a></p>
                    </div>
                    <div class="col-lg-6">
                        <figure class="illustration">
                            <img src="images/images-illustration.png" alt="Image" class="img-fluid">
                        </figure>
                    </div>
                    <div class="col-lg-6"></div>
                </div>
            </div>
        </div>

        <div class="site-section stats">
            <div class="container">
                <div class="row mb-3">
                    <div class="col-lg-7 text-center mx-auto">
                        <h2 class="section-heading">Coronavirus Statistics</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Amet, voluptate!</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="data">
                            <span class="icon text-primary">
                                <span class="flaticon-virus"></span>
                            </span>
                            <strong class="d-block number" id="active_cases">0</strong>
                            <span class="label">Active Cases</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="data">
                            <span class="icon text-primary">
                                <span class="flaticon-virus"></span>
                            </span>
                            <strong class="d-block number" id="deaths">0</strong>
                            <span class="label">Deaths</span>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="data">
                            <span class="icon text-primary">
                                <span class="flaticon-virus"></span>
                            </span>
                            <strong class="d-block number" id="recovered">0</strong>
                            <span class="label">Recovered Cases</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-section">
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 mb-4 mb-lg-0">
                        <figure class="img-play-vid">
                            <img src="images/images-hero_1.jpg" alt="Image" class="img-fluid">
                            <div class="absolute-block d-flex">
                                <span class="text">Watch the Video</span>
                                <a href="https://www.youtube.com/watch?v=9pVy8sRC440" data-fancybox class="btn-play">
                                    <span class="icon-play"></span>
                                </a>
                            </div>
                        </figure>
                    </div>
                    <div class="col-lg-5 ml-auto">
                        <h2 class="mb-4 section-heading">What is Coronavirus?</h2>
                        <p>Coronaviruses are a type of virus. There are many different kinds, and some cause disease. A coronavirus identified in 2019, SARS-CoV-2, has caused a pandemic of respiratory illness, called COVID-19.</p>
                        <ul class="list-check list-unstyled mb-5">
                            <li>COVID-19 is the disease caused by SARS-CoV-2, the coronavirus that emerged in December 2019.</li>
                            <li>COVID-19 can be severe, and has caused millions of deaths around the world as well as lasting health problems in some who have survived the illness.</li>
                            <li>The coronavirus can be spread from person to person. It is diagnosed with a laboratory test.</li>
                        </ul>
                        <p><a href="https://www.who.int/health-topics/coronavirus#tab=tab_1" target="_blank" class="btn btn-primary">Learn more</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container pb-5">
            <div class="row">
                <div class="col-lg-3">
                    <div class="feature-v1 d-flex align-items-center">
                        <div class="icon-wrap mr-3">
                            <span class="flaticon-protection"></span>
                        </div>
                        <div>
                            <h3>Protection</h3>
                            <span class="d-block">Lorem ipsum dolor sit.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="feature-v1 d-flex align-items-center">
                        <div class="icon-wrap mr-3">
                            <span class="flaticon-patient"></span>
                        </div>
                        <div>
                            <h3>Prevention</h3>
                            <span class="d-block">Lorem ipsum dolor sit.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="feature-v1 d-flex align-items-center">
                        <div class="icon-wrap mr-3">
                            <span class="flaticon-hand-sanitizer"></span>
                        </div>
                        <div>
                            <h3>Treatments</h3>
                            <span class="d-block">Lorem ipsum dolor sit.</span>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3">
                    <div class="feature-v1 d-flex align-items-center">
                        <div class="icon-wrap mr-3">
                            <span class="flaticon-virus"></span>
                        </div>
                        <div>
                            <h3>Symptoms</h3>
                            <span class="d-block">Lorem ipsum dolor sit.</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-section bg-primary-light">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="row">
                            <div class="col-6 col-lg-6 mt-lg-5">
                                <div class="media-v1 bg-1">
                                    <div class="icon-wrap">
                                        <span class="flaticon-stay-at-home"></span>
                                    </div>
                                    <div class="body">
                                        <h3>Stay at home</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio, debitis!</p>
                                    </div>
                                </div>
                                <div class="media-v1 bg-1">
                                    <div class="icon-wrap">
                                        <span class="flaticon-patient"></span>
                                    </div>
                                    <div class="body">
                                        <h3>Wear facemask</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio, debitis!</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-6 col-lg-6">
                                <div class="media-v1 bg-1">
                                    <div class="icon-wrap">
                                        <span class="flaticon-social-distancing"></span>
                                    </div>
                                    <div class="body">
                                        <h3>Keep social distancing</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio, debitis!</p>
                                    </div>
                                </div>
                                <div class="media-v1 bg-1">
                                    <div class="icon-wrap">
                                        <span class="flaticon-hand-washing"></span>
                                    </div>
                                    <div class="body">
                                        <h3>Wash your hands</h3>
                                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Odio, debitis!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-5 ml-auto">
                        <h2 class="section-heading mb-4">How to Prevent Coronavirus?</h2>
                        <p>Protect yourself and others around you by knowing the facts and taking appropriate precautions. Follow advice provided by your local health authority.</p>
                        <p class="mb-4">To prevent the spread of COVID-19:</p>
                        <ul class="list-check list-unstyled mb-5">
                            <li>Clean your hands often. Use soap and water, or an alcohol-based hand rub.</li>
                            <li>Maintain a safe distance from anyone who is coughing or sneezing.</li>
                            <li>Wear a mask when physical distancing is not possible.</li>
                            <li>Don’t touch your eyes, nose or mouth.</li>
                            <li>Cover your nose and mouth with your bent elbow or a tissue when you cough or sneeze.</li>
                            <li>Stay home if you feel unwell.</li>
                            <li>If you have a fever, cough and difficulty breathing, seek medical attention.</li>
                        </ul>
                        <p><a href="https://www.who.int/emergencies/diseases/novel-coronavirus-2019/advice-for-public" target="_blank" class="btn btn-primary">Read more about prevention</a></p>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-7 mx-auto text-center">
                        <span class="subheading">What you need to do</span>
                        <h2 class="mb-4 section-heading">How To Protect Yourself</h2>
                        {{-- <p>If COVID-19 is spreading in your community, stay safe by taking some simple precautions, such as physical distancing, wearing a mask, keeping rooms well ventilated, avoiding crowds, cleaning your hands, and coughing into a bent elbow or tissue. Check local advice where you live and work. Do it all!</p> --}}
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 ">
                        <div class="row mt-5 pt-5">
                            <div class="col-lg-6 do ">
                                <h3>You should do</h3>
                                <ul class="list-unstyled check">
                                    <li>Stay at home</li>
                                    <li>Wear mask</li>
                                    <li>Use Sanitizer</li>
                                    <li>Disinfect your home</li>
                                    <li>Wash your hands</li>
                                    <li>Frequent self-isolation</li>
                                </ul>
                            </div>
                            <div class="col-lg-6 dont ">
                                <h3>You should avoid</h3>
                                <ul class="list-unstyled cross">
                                    <li>Avoid infected people</li>
                                    <li>Avoid animals</li>
                                    <li>Avoid handshaking</li>
                                    <li>Aviod infected surfaces</li>
                                    <li>Don't touch your face</li>
                                    <li>Avoid droplets</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <img src="images/images-protect.png" alt="Image" class="img-fluid">
                    </div>
                </div>
            </div>
        </div>
        <div class="site-section bg-primary-light">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-7 mx-auto text-center">
                        <h2 class="mb-4 section-heading">Symptoms of Coronavirus</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex officia quas, modi sit eligendi
                            numquam!</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-6 mb-4">
                        <div class="symptom d-flex">
                            <div class="img">
                                <img src="images/images-symptom_high-fever.png" alt="Image" class="img-fluid">
                            </div>
                            <div class="text">
                                <h3>High Fever</h3>
                                <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nostrum ipsum repellendus
                                    animi modi iure provident, cupiditate perferendis voluptatem!</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="symptom d-flex">
                            <div class="img">
                                <img src="images/images-symptom_cough.png" alt="Image" class="img-fluid">
                            </div>
                            <div class="text">
                                <h3>Cough</h3>
                                <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Nulla ullam illo laborum
                                    repellendus vel esse dolor, sunt exercitationem.</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="symptom d-flex">
                            <div class="img">
                                <img src="images/images-symptom_sore-troath.png" alt="Image" class="img-fluid">
                            </div>
                            <div class="text">
                                <h3>Sore Troath</h3>
                                <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Illum esse voluptatum, vel
                                    inventore at! Ullam, libero reiciendis amet?</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6 mb-4">
                        <div class="symptom d-flex">
                            <div class="img">
                                <img src="images/images-symptom_headache.png" alt="Image" class="img-fluid">
                            </div>
                            <div class="text">
                                <h3>Headache</h3>
                                <p> Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusamus autem voluptatem
                                    ratione veniam rerum qui quibusdam reprehenderit quis.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row justify-content-md-center">
                    <div class="col-lg-10">
                        <div class="note row">
                            <div class="col-lg-8 mb-4 mb-lg-0"><strong>Stay at home and call your doctor:</strong> Lorem
                                ipsum dolor sit amet, consectetur adipisicing elit. Accusantium, eaque.</div>
                            <div class="col-lg-4 text-lg-right">
                                <a href="#" class="btn btn-primary"><span class="icon-phone mr-2 mt-3"></span>Help
                                    line</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="site-section">
            <div class="container">
                <div class="row mb-5">
                    <div class="col-lg-7 mx-auto text-center">
                        <h2 class="mb-4 section-heading">News &amp; Articles</h2>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Ex officia quas, modi sit eligendi
                            numquam!</p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="post-entry">
                            <a href="#" class="thumb">
                                <span class="date">30 Jul, 2020</span>
                                <img src="images/images-hero_1.jpg" alt="Image" class="img-fluid">
                            </a>
                            <div class="post-meta text-center">
                                <a href="covid.html">
                                    <span class="icon-user"></span>
                                    <span>Admin</span>
                                </a>
                                <a href="#">
                                    <span class="icon-comment"></span>
                                    <span>3 Comments</span>
                                </a>
                            </div>
                            <h3><a href="#">How Coronavirus Very Contigous</a></h3>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="post-entry">
                            <a href="#" class="thumb">
                                <span class="date">30 Jul, 2020</span>
                                <img src="images/images-hero_2.jpg" alt="Image" class="img-fluid">
                            </a>
                            <div class="post-meta text-center">
                                <a href="covid.html">
                                    <span class="icon-user"></span>
                                    <span>Admin</span>
                                </a>
                                <a href="#">
                                    <span class="icon-comment"></span>
                                    <span>3 Comments</span>
                                </a>
                            </div>
                            <h3><a href="#">How Coronavirus Very Contigous</a></h3>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="post-entry">
                            <a href="#" class="thumb">
                                <span class="date">30 Jul, 2020</span>
                                <img src="images/images-hero_1.jpg" alt="Image" class="img-fluid">
                            </a>
                            <div class="post-meta text-center">
                                <a href="covid.html">
                                    <span class="icon-user"></span>
                                    <span>Admin</span>
                                </a>
                                <a href="#">
                                    <span class="icon-comment"></span>
                                    <span>3 Comments</span>
                                </a>
                            </div>
                            <h3><a href="#">How Coronavirus Very Contigous</a></h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
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

    <livewire:scripts />
    <script src="{{ asset('js/frontend.js') }}"></script>
    {{-- <script src="js/760-js-jquery-3.3.1.min.js"></script>
    <script src="js/4947-js-jquery-ui.js"></script>
    <script src="js/6920-js-popper.min.js"></script>
    <script src="js/9905-js-bootstrap.min.js"></script> --}}
    <script src="js/owl.carousel.min.js"></script>
    <script src="js/jquery.countdown.min.js"></script>
    <script src="js/jquery.easing.1.3.js"></script>
    <script src="{{ asset('js/aos.js') }}"></script>
    <script src="js/jquery.fancybox.min.js"></script>
    <script src="js/jquery.sticky.js"></script>
    <script src="js/isotope.pkgd.min.js"></script>
    {{-- <script src="js/7591-js-main.js"></script> --}}
    <script src="{{ asset('js/forntend-main.js') }}"></script>
    <script>
        $.get("{{ route('api.covid-19.all') }}",function (data) {
            if(data){
                let resp = data.meta.find(o => o.country === 'World');
                resp = resp.timeline.today;
                $('#active_cases').html(resp.active)
                $('#deaths').html(resp.deaths)
                $('#recovered').html(resp.recovered)
            }else{
                $('#active_cases').html(0)
                $('#deaths').html(0)
                $('#recovered').html(0)

            }
        })
    </script>
    @yield('scripts')
</body>

</html>
