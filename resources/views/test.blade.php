<html>
  <head>
    <!--Load the AJAX API-->
    <script src="{{ asset('js/jquery-3.3.1.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/loader.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('css/frontend.css') }}">
    <script type="text/javascript" src="{{ asset('js/forntend-main.js') }}"></script>
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




    <section class="accordion-section clearfix mt-3" aria-label="Question Accordions">
        <div class="container">

            <h2>Frequently Asked Questions </h2>
            <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
              <div class="panel panel-default">
                <div class="panel-heading p-3 mb-3" role="tab" id="heading0">
                  <h3 class="panel-title">
                    <a class="collapsed" role="button" title="" data-toggle="collapse" data-parent="#accordion" href="#collapse0" aria-expanded="true" aria-controls="collapse0">
                      What are the benefits of Solodev CMS?
                    </a>
                  </h3>
                </div>
                <div id="collapse0" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading0">
                  <div class="panel-body px-3 mb-4">
                    <p>With Solodev CMS, you and your visitors will benefit from a finely-tuned technology stack that drives the highest levels of site performance, speed and engagement - and contributes more to your bottom line. Our users fell in love with:</p>
                    <ul>
                      <li>Light speed deployment on the most secure and stable cloud infrastructure available on the market.</li>
                      <li>Scalability – pay for what you need today and add-on options as you grow.</li>
                      <li>All of the bells and whistles of other enterprise CMS options but without the design limitations - this CMS simply lets you realize your creative visions.</li>
                      <li>Amazing support backed by a team of Solodev pros – here when you need them.</li>
                    </ul>
                  </div>
                </div>
              </div>

              <div class="panel panel-default">
                <div class="panel-heading p-3 mb-3" role="tab" id="heading1">
                  <h3 class="panel-title">
                    <a class="collapsed" role="button" title="" data-toggle="collapse" data-parent="#accordion" href="#collapse1" aria-expanded="true" aria-controls="collapse1">
                      How easy is it to build a website with Solodev CMS?
                    </a>
                  </h3>
                </div>
                <div id="collapse1" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading1">
                  <div class="panel-body px-3 mb-4">
                    <p>Building a website is extremely easy. With a working knowledge of HTML and CSS you will be able to have a site up and running in no time.</p>
                  </div>
                </div>
              </div>

              <div class="panel panel-default">
                <div class="panel-heading p-3 mb-3" role="tab" id="heading2">
                  <h3 class="panel-title">
                    <a class="collapsed" role="button" title="" data-toggle="collapse" data-parent="#accordion" href="#collapse2" aria-expanded="true" aria-controls="collapse2">
                      What is the uptime for Solodev CMS?
                    </a>
                  </h3>
                </div>
                <div id="collapse2" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading2">
                  <div class="panel-body px-3 mb-4">
                    <p>Using Amazon AWS technology which is an industry leader for reliability you will be able to experience an uptime in the vicinity of 99.95%.</p>
                  </div>
                </div>
              </div>

              <div class="panel panel-default">
                <div class="panel-heading p-3 mb-3" role="tab" id="heading3">
                  <h3 class="panel-title">
                    <a class="collapsed" role="button" title="" data-toggle="collapse" data-parent="#accordion" href="#collapse3" aria-expanded="true" aria-controls="collapse3">
                      Can Solodev CMS handle multiple websites for my company?
                    </a>
                  </h3>
                </div>
                <div id="collapse3" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading3">
                  <div class="panel-body px-3 mb-4">
                    <p>Yes, Solodev CMS is built to handle the needs of any size company. With our Multi-Site Management, you will be able to easily manage all of your websites.</p>
                  </div>
                </div>
              </div>
            </div>

        </div>
      </section>
  </body>
</html>
