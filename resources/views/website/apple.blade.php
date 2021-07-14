@extends('layouts.frontend')

@section('content')
    <div class="site-section bg-primary-light" style="overflow: auto;">
        <div class="container" style="">
            <h1 class="text-center">Apple Trends in {{ $search }}</h1>
            <div class="stats">
                <div class="row justify-content-center mb-2">
                    <div class="col">
                        <div id="chart" style="width: 80%; display:block; margin: 0 auto;"></div>
                    </div>
                </div>
                <div class="row justify-content-center">
                    {{-- <div class="col-sm-3">
                    </div> --}}
                    <div class="col text-center">
                        <select class="custom-select mb-4 mt-1 w-50" id="region">
                            @php $count = 0 @endphp
                            @foreach ($data1 as $key => $value)
                            <option @if($count == 0) selected @endif value="{{ $key }}">{{ $key }}</option>
                            @php $count ++ @endphp
                            @endforeach
                          </select>
                        <div id="stat" style="width: 80%; display:block; margin: 0 auto;"></div>
                    </div>
                </div>
                {{-- {{ dd($data) }} --}}

            </div>
            <div class="mt-3">
                @livewire('frontend.appletrends-search')
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const Data = {!! $data !!};
        const region = {!! json_encode($data1) !!}
        async function drawChart(dataset) {
        await loadGoogleCharts();
        // Create the data table.

	    const data = new google.visualization.DataTable();

        data.addColumn('datetime', 'Date');
        data.addColumn('number', 'Driving');
        data.addColumn('number', 'walking');
        if(dataset){
            Object.keys(dataset[0].timeline)
            .filter((_, index) => index !== '')
            .forEach((date) => {
                if(Number(dataset[0].timeline[date]) != 0) {
                data.addRows([
                    [
                        new Date(date),
                        Number(dataset[0].timeline[date]),
                        Number(dataset[1].timeline[date]),
                    ]
                ]);
                }
            });
        }

        const options = {
            title: "Apple Trends in " + "{{ $search }}",
            curveType: 'function',
            legend: { position: 'bottom' },
            height: document.body.clientWidth < 768 ? 150 : 400,
            animation: {
                duration: 1000,
                easing: 'out',
                "startup": true
            },
        };

        const chart = new google.charts.Line(document.getElementById('chart'));

        chart.draw(data, google.charts.Line.convertOptions(options));
      }
        async function drawRegion(dataset,selected) {
        await loadGoogleCharts();
        // Create the data table.

	    const data = new google.visualization.DataTable();

        data.addColumn('datetime', 'Date');
        data.addColumn('number', 'Driving');
        data.addColumn('number', 'walking');
        if(dataset){
            dataset.forEach((val,key) => {
                data.addRows([
                    [
                        new Date(val['date']),
                        Number(val['driving']),
                        Number(val['walking']),
                    ]
                ]);
            })
        }

        const options = {
            title: "Apple Trends in region " + selected,
            curveType: 'function',
            legend: { position: 'bottom' },
            height: document.body.clientWidth < 768 ? 150 : 400,
            animation: {
                duration: 1000,
                easing: 'out',
                "startup": true
            },
        };

        const chart = new google.charts.Line(document.getElementById('stat'));

        chart.draw(data, google.charts.Line.convertOptions(options));
      }


    </script>
    <script>
        $(document).ready(function () {
            drawChart(Data);
            let selected = $('#region').find(":selected").text();
            drawRegion(region[selected],selected);
        })
        $('#region').on('change', function() {
            let selected = $(this).find(":selected").text();
            drawRegion(region[selected],selected);
        });
    </script>
@endsection
