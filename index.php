<?php
session_start();
header("Refresh: 5; url=index.php");
//library
require 'vendor/autoload.php';
use google\appengine\api\users\User;
use google\appengine\api\users\UserService;
use Google\Cloud\Datastore\DatastoreClient;
$projID= "eeet2371-iot-proj";
?>
<!doctype html>
<html>
<head>
    <link type="text/css" rel="stylesheet" href="css/style.css"/>
    <meta charset="utf-8">
</head>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
<script type="text/javascript" src="//thingspeak.com/highcharts-3.0.8.js"></script>
<script type="text/javascript">
  // variables for the first series
  var series_1_channel_id = 878226;
  var series_1_field_number = 1;
  var series_1_read_api_key = '4O9B6V3VFJNQW85H';
  var series_1_results = 10;
  var series_1_color = '#d62020';

  // variables for the second series
  var series_2_channel_id = 878226;
  var series_2_field_number = 2;
  var series_2_read_api_key = '4O9B6V3VFJNQW85H';
  var series_2_results = 10;
  var series_2_color = '#00aaff';

  // chart title
  var chart_title = 'Temperature';
  var chart_title2 = 'Humidity';
  // y axis title
  var y_axis_title_Temp = 'Degrees';
  var y_axis_title_Hum = '%';

  // user's timezone offset
  var my_offset = new Date().getTimezoneOffset();
  // chart variable
  var my_chart_Temp;
  var my_chart_Hum;

  // when the document is ready
  $(document).on('ready', function() {
    // add a blank chart
    my_chart_Temp=addChart(y_axis_title_Temp,chart_title,'chart-container1');
	my_chart_Hum=addChart(y_axis_title_Hum,chart_title2,'chart-container2');
    // add the first series
    addSeries(series_1_channel_id, series_1_field_number, series_1_read_api_key, series_1_results, series_1_color,my_chart_Temp);
    // add the second series
    addSeries(series_2_channel_id, series_2_field_number, series_2_read_api_key, series_2_results, series_2_color,my_chart_Hum);
  });

  // add the base chart
  function addChart(y_axis_title,chart_title, render) {
    // variable for the local date in milliseconds
    var localDate;

    // specify the chart options
    var chartOptions = {
      chart: {
        renderTo: render,
        defaultSeriesType: 'line',
        backgroundColor: '#ffffff',
        events: { }
      },
      title: { text: chart_title },
      plotOptions: {
        series: {
          marker: { radius: 3 },
          animation: true,
          step: false,
          borderWidth: 0,
          turboThreshold: 0
        }
      },
      tooltip: {
        // reformat the tooltips so that local times are displayed
        formatter: function() {
          var d = new Date(this.x + (my_offset*60000));
          var n = (this.point.name === undefined) ? '' : '<br>' + this.point.name;
          return this.series.name + ':<b>' + this.y + '</b>' + n + '<br>' + d.toDateString() + '<br>' + d.toTimeString().replace(/\(.*\)/, "");
        }
      },
      xAxis: {
        type: 'datetime',
        title: { text: 'Date' }
      },
      yAxis: { title: { text: y_axis_title } },
      exporting: { enabled: false },
      legend: { enabled: false },
      credits: {
        text: 'ThingSpeak.com',
        href: 'https://thingspeak.com/',
        style: { color: '#D62020' }
      }
    };

    // draw the chart
    my_chart = new Highcharts.Chart(chartOptions);
	return my_chart;
  }

  // add a series to the chart
  function addSeries(channel_id, field_number, api_key, results, color, my_chart) {
    var field_name = 'field' + field_number;

    // get the data with a webservice call
    $.getJSON('https://api.thingspeak.com/channels/' + channel_id + '/fields/' + field_number + '.json?offset=0&round=2&results=' + results + '&api_key=' + api_key, function(data) {

      // blank array for holding chart data
      var chart_data = [];

      // iterate through each feed
      $.each(data.feeds, function() {
        var point = new Highcharts.Point();
        // set the proper values
        var value = this[field_name];
        point.x = getChartDate(this.created_at);
        point.y = parseFloat(value);
        // add location if possible
        if (this.location) { point.name = this.location; }
        // if a numerical value exists add it
        if (!isNaN(parseInt(value))) { chart_data.push(point); }
      });

      // add the chart data
      my_chart.addSeries({ data: chart_data, name: data.channel[field_name], color: color });
    });
  }

  // converts date format from JSON
  function getChartDate(d) {
    // offset in minutes is converted to milliseconds and subtracted so that chart's x-axis is correct
    return Date.parse(d) - (my_offset * 60000);
  }

</script>

<nav>
 <div>
     <div id="logo"><img src= "image/kanngaru.png" height="60px" width="60px">Temperature & humidity tracker</div>
  <ul><li><a href="index.php">HOME</a></li>
  </ul>
</div>
</nav>
<header class="Head">
  <h1>Temp & Hum sensor data</h1>
  </header>
<body>
    <title>Temperature & humidity tracker</title>
    <main>
	
      <div class="main_box">
	  <div class = "graph_box">
	  <div id="chart-container1">
    <img alt="Ajax loader" src="//thingspeak.com/assets/loader-transparent.gif" style="position: absolute; margin: auto; top: 0; left: 0; right: 0; bottom: 0;" />
  </div>
	  </div>
	  <div class = "graph_box">
	  <div id="chart-container2">
    <img alt="Ajax loader" src="//thingspeak.com/assets/loader-transparent.gif" style="position: absolute; margin: auto; top: 0; left: 0; right: 0; bottom: 0;" />
  </div>
	  </div>
	  
	  <?php
		include('status.php');
		?>
		</div>
	</div>
    </main>
<?php
include('footer.inc');
?>
  </body>  

</html>