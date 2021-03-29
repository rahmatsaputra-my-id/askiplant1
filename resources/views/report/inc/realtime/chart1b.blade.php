@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart1b(result) {
  var y_data = result.y_data.map(i=>Number(i));
  var x_data = result.x_data;
  Highcharts.chart('chart1b', {
    // chart: {
    //     type: 'column'
    // },
    title: {
        text: 'OK Ratio'
    },
    xAxis: {
        categories: x_data,
        crosshair: true
    },
    yAxis: {
        min: 0,
        max: 100,
        title: {
            text: 'OK Ratio'
        }
    },
    plotOptions: {
        // column: {
        //     pointPadding: 0.2,
        //     borderWidth: 0,
        //     dataLabels: {
        //           enabled: true
        //       }
        // },
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },
    series: [{
        name: 'ok ratio (%)',
        data: y_data
        // dataLabels: {
        //     enabled: true,
        //     rotation: -90,
        //     // color: '#FFFFFF',
        //     align: 'right',
        //     format: '{point.y:.1f}', // one decimal
        //     // y: 10, // 10 pixels down from the top
        //     style: {
        //         fontSize: '9px',
        //         fontFamily: 'Verdana, sans-serif'
        //     }
        // }
    }]
  });
}

</script>
