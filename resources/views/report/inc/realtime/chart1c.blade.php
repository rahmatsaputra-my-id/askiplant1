@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart1c(result) {
  var y_data = result.y_data.map(i=>Number(i));
  var x_data = result.x_data;
  Highcharts.chart('chart1c', {
    chart: {
        type: 'column'
    },
    title: {
        text: 'Top 10 Type of NG'
    },
    xAxis: {
        categories: x_data,
        crosshair: true
    },
    yAxis: {
        min: 0,
        title: {
            text: 'in pcs'
        }
    },
    plotOptions: {
        column: {
            pointPadding: 0.2,
            borderWidth: 0,
            dataLabels: {
                  enabled: true
              }
        }
    },
    series: [{
        name: 'NG',
        data: y_data

    }]
  });
}

</script>
