@extends('layouts.frontend')

@section('content')
    <div class="site-section bg-primary-light" style="overflow: auto;">
        <div class="container" >
            {{-- @livewire('frontend.global-world-map') --}}
            <div class="search_box text-center">
                <h1>Covid Data on Apple Trends World Wide</h1>
                <input class="form-control" type="search" name="search" id="search" placeholder="Search" onchange="search()" value="{{ isset($search) ? $search : '' }}" aria-label="Search">
                <div class="text-center">
                    <h3 class="error text-danger">{{ isset($error) ? $error : '' }}</h3>
                </div>
                <div class="search mt-1" style="height: 350px;overflow: hidden; transition: height 1s ease;">
                    <ul id="list_country" style="list-style-type: none">

                        @foreach ($country['meta'] as $item)
                        <li class="float-left pr-1">
                            <a href="{{ route('apple_trends.search',$item) }}" search="{{ $item }}" class="btn btn-primary mb-1">{{ $item }}</a>
                        </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        function search() {
            // Declare variables
            var input, filter, ul, li, a, i, txtValue;
            input = document.getElementById('search');

            filter = input.value.toUpperCase();
            ul = document.getElementById("list_country");
            li = ul.getElementsByTagName('li');

            // Loop through all list items, and hide those who don't match the search query
            for (i = 0; i < li.length; i++) {
                a = li[i].getElementsByTagName("a")[0];
                txtValue = a.textContent || a.innerText;
                if (txtValue.toUpperCase().indexOf(filter) > -1) {
                li[i].style.display = "";
                } else {
                li[i].style.display = "none";
                }
            }
            show()
        }
        function show(){
            //Get Current Height
            var currentHeight = $(".search").css("height");

            //Set height to auto
            $(".search").css("height","auto");

            //Store auto height
            var animateHeight = $(".search").css("height");

            //Put height back
            $(".search").css("height", currentHeight);

            //Do animation with animateHeight
            $(".search").animate({
                height: animateHeight
                }, 500);

            $('#show_all').css({'display':'none'})

        }
    </script>
@endsection
