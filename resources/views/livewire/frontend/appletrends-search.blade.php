<div class="search_box text-center" wire:init=search>

    @if (count($country) > 0)
    <input class="form-control" type="search" name="search" id="search" placeholder="Search" onchange="search()" value="{{ (isset($search) && is_string($search)) ? $search : '' }}" aria-label="Search">
    <div class="text-center">
        <h3 class="error text-danger">{{ isset($error) ? $error : '' }}</h3>
    </div>
    <div class="search mt-1" style="height: auto;overflow: hidden; transition: height 1s ease;">
        <ul id="list_country" style="list-style-type: none">

            @foreach ($country['meta'] as $item)
            <li class="float-left pr-1">
                <a href="{{ route('apple_trends.search',$item) }}" search="{{ $item }}" class="btn btn-primary mb-1">{{ $item }}</a>
            </li>
            @endforeach
        </ul>
    </div>
    @else
    <img src="{{ asset('images/Spinner-1s-450px.svg') }}" width="160" alt="Loading" title="click to refresh data" srcset="">
    <p class="toolong text-danger" style="display: none;">Taking too long to load? <button class="btn btn-primary" wire:click=search>click here</button></p>
    @endif
</div>


@push('scripts')
    <script>
        setTimeout(() => {
            $('.toolong').show("slow");
        }, 10000);
    </script>
        <script>
            function search() {
                // Declare variables
                var input, filter, li, a, i, txtValue;
                input = document.getElementById('search');

                filter = input.value.toUpperCase();
                li = $('#list_country > li');
                // console.log(li.next('a'))
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
@endpush

