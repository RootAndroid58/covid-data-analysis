<html>
  <head>
    <!--Load the AJAX API-->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/loader.js') }}"></script>
    <script type="text/javascript">
    const googleChartsLoaded = false;
    function loadGoogleCharts() {
        return new Promise((resolve) => {
            if (googleChartsLoaded) {
                resolve();
            } else {
                google.charts.load('current', {
                    packages: ['line', 'corechart', 'geochart'],
                    // mapsApiKey: 'AIzaSyD-9tSrke72PouQMnMX-a7eZSW0jkFMBWY'
                });
                // google.charts.load('current', {'packages':['corechart']});
                google.charts.setOnLoadCallback(resolve);
            }
        });
    }

      async function drawChart(dataset) {
        await loadGoogleCharts();
        // Create the data table.

	    const data = new google.visualization.DataTable();

        data.addColumn('datetime', 'Date');
        data.addColumn('number', 'New Cases');
        data.addColumn('number', 'Deaths');
        data.addColumn('number', 'Recovered');
        // console.log(dataset.cases)
        if(dataset){
            Object.keys(dataset.recovered)
                // .filter((_, index) => index % 3 === 0)
                .forEach((date) => {
                    data.addRows([
                        [
                            new Date(date),
                            Number(dataset.cases[date]),
                            Number(dataset.deaths[date]),
                            Number(dataset.recovered[date]),
                        ]
                    ]);
                });
        }else{
            data.addRows([
                        [
                            ,
                            Number(0),
                            Number(0),
                            Number(0),
                        ]
                    ]);


        }

        const options = {
            title: "Historical data of",
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
        google.visualization.events.addListener(chart, 'ready', Title_center);
      }

    function Title_center(){

        var title_chart=$("#chart svg g").find('text').html();

        $("#chart svg").find('g:first').html('<text text-anchor="start" x="450" y="20" font-family="Arial" font-size="18" font-weight="bold" stroke="none" stroke-width="0" fill="#000000">'+title_chart+'</text>');
    }

    </script>
    <script src="{{ asset('js/apexcharts.js') }}"></script>
  </head>

  <body>
      <script>
          async function start(){
            drawChart()
            let url = "https://crada.dev/api/v1/covid-19/historical";
            const response = await fetch(url);
            if (response.ok) { // if HTTP-status is 200-299
            // get the response body (the method explained below)
                var json = await response.json();
            } else {
                alert("HTTP-Error: " + response.status);
            }
            var key = json.meta.filter((item) => item.country == 'India');
            key = key[0]['timeline'];
            drawChart(key);
          }
          start();
      </script>
    <!--Div that will hold the pie chart-->
    <div id="chart_div"></div>
    <div id="chart"></div>
  </body>
</html>
