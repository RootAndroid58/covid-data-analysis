@extends('layouts.frontend')

@section('content')
    <div class="site-section bg-primary-light" style="overflow: auto;">
        <div class="container" >
            <div class="stats">
                <div id="chart" style="width: 80%; display:block; margin: 0 auto;"></div>

            </div>
            <div class="search_box text-center">
                <h1>Covid Data on Apple Trends World Wide</h1>
                <input class="form-control" type="search" name="search" id="search" placeholder="Search" onkeyup="search()" value="{{ isset($search) ? $search : '' }}" aria-label="Search">
                <div class="text-center">
                    <h3 class="error text-danger">{{ isset($error) ? $error : '' }}</h3>
                </div>
                @php
                    $country = App\Http\Helpers\ApiHelper::apple_mobility_country('prod.mobility.apple.country');
                @endphp
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
        async function drawRegionsMap(dataset,type,id) {
            await loadGoogleCharts();

            const data = new google.visualization.DataTable();

            data.addColumn('string', 'Country');
            data.addColumn('number', 'cases');
            data.addColumn({type: 'string', role:'tooltip', p:{html:true} });

            if(dataset){
            dataset.forEach((val,key) => {
                // if(val['iso2'] == "RU"){

                //     console.log((val['timeline'][type]['cases']).replaceAll(',',''));
                //     }
                let cases = Number((val['timeline'][type]['cases']).replaceAll(',',''));
                let deaths = Number((val['timeline'][type]['deaths']).replaceAll(',',''));
                let recovered = Number((val['timeline'][type]['recovered']).replaceAll(',',''));
                let active = Number((val['timeline'][type]['active']).replaceAll(',',''));
                if( isNaN(active)){
                    active = 0
                }
                // console.log(cases)
                data.addRows([
                    [
                        { v:val['iso2'] ,f:`${val['country']} [${val['iso2']}]`},
                        active,
                        `Active: ${active}<br>Cases: ${cases} <br>Deaths: ${deaths}<br> Recovered: ${recovered}`
                    ]
                ]);
            })
        }

        var options = {
            title: "Worldometer's World's Stats",
            tooltip: {
                isHtml: true
            },
            backgroundColor: "#f8f5fc",
            colors: ['#6f42c1']
        };
        const chart = new google.visualization.GeoChart(document.getElementById('chart'));

        chart.draw(data, options);
      }
    </script>
    <script>

        var data = {!! json_encode($data) !!};
        console.log(data);
        drawRegionsMap(data,'today','chart');

    </script>
@endsection
