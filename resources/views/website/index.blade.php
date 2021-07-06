@extends('layouts.frontend')


@section('content')
<div class="hero-v1">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 mr-auto text-center text-lg-left">
                <span class="d-block subheading">Covid-19 Awareness</span>
                <h1 class="heading mb-3">Stay Safe. Stay Home.</h1>
                <p class="mb-5">
                    Prevention is better than cure!
                </p>
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
                <p>Stats From Worldometers</p>
            </div>
        </div>
        {{-- <livewire:frontend.covid-stats /> --}}
        @livewire('frontend.covid-stats')
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
                    <span class="d-block">leave at least 6 feet of space</span>
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
                    <span class="d-block">Wear Masks.</span>
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
                    <span class="d-block">Vaccinate yourself on time.</span>
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
                    <span class="d-block">Trouble breathing, <br> Persistent pain in the chest.</span>
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
                                <p>Wash your hands well and often</p>
                            </div>
                        </div>
                        <div class="media-v1 bg-1">
                            <div class="icon-wrap">
                                <span class="flaticon-patient"></span>
                            </div>
                            <div class="body">
                                <h3>Wear facemask</h3>
                                <p>Cover your mouth with your elbow when you cough or sneeze, or use a tissue.</p>
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
                                <p>When you do go out in public, leave at least 6 feet of space between you and others.</p>
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
                <p>COVID-19 affects different people in different ways. Most infected people will develop mild to moderate illness and recover without hospitalization.</p>
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
                        <p> Normal body temperature is typically around 98.6°F (37°C). According to some estimates, 99% of people who develop COVID-19 symptoms experiences high and long lasting fever </p>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="symptom d-flex">
                    <div class="img">
                        <img src="images/images-symptom_cough.png" alt="Image" class="img-fluid">
                    </div>
                    <div class="text">
                        <h3>Dry Cough</h3>
                        <p>A dry cough is a common early symptom of COVID-19. According to some estimates, 60–70% of people who develop COVID-19 symptoms experience a dry cough as an initial symptom.</p>
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
                        <p> The raw, scratchy, burning feeling at the back of your throat is often the first warning sign that you have a cold.This symptoms usually begin 2 to 14 days after you come into contact with the virus.</p>
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
                        <p> Headache is also one of the symptoms where you may want to take a covid-19 test and this symptom is one of the much less common than symptoms but better be carefull ! </p>
                    </div>
                </div>
            </div>
            <a href="#" class="btn btn-primary w-100">Check All Symptoms !</a>
        </div>
        <div class="row justify-content-md-center">
            <div class="col-lg-10">
                <div class="note row">
                    <div class="col-lg-8 mb-4 mb-lg-0"><strong>Stay at home and call your doctor</strong> If you feel any of the above symptoms mentioned.</div>
                    <div class="col-lg-4 text-lg-right">
                        <a href="#" class="btn btn-primary"><span class="icon-phone mr-2 mt-3"></span>Help
                            line</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- <div class="site-section">
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
</div> --}}
@endsection

@section('scripts')
<script>


    // Livewire.on('counter' , data => {
    //     var countUp = new CountUp('.counter', data.active_cases);
    //     countUp.start();
    // })
</script>
@endsection
