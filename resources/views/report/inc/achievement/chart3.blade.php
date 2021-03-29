@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart3(result, daysInMonth) {
  var achievement_data = result.data_chart_3
  var date_arr = dateArray(daysInMonth);
  var value_arr = valueArray(daysInMonth, achievement_data);
  Highcharts.chart('chart3', {

    title: {
        text: 'OK Ratio -- Chart in percent'
    },

    yAxis: {
        title: {
            text: 'OK Ratio (%)'
        },
        plotLines: [{
            color: '#FF0000',
            width: 2,
            dashStyle: 'LongDash',
            value: 95
        }]
    },

    xAxis: {
      categories: date_arr
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },

    series: [{
        name: '% OK',
        data: value_arr,
        dataLabels: {
            enabled: true,
            rotation: -90,
            // color: '#FFFFFF',
            align: 'top',
            format: '{point.y:.1f}', // one decimal
            // y: 10, // 10 pixels down from the top
            style: {
                fontSize: '10px'
            }
        }
    }],

    responsive: {
        rules: [{
            condition: {
                maxWidth: 500
            },
            chartOptions: {
                legend: {
                    layout: 'horizontal',
                    align: 'center',
                    verticalAlign: 'bottom'
                }
            }
        }]
    }

});
}

function dateArray(daysInMonth) {
  var date_arr = [];
  for (var i = 1; i <= daysInMonth; i++) {
    date_arr.push(i)
  }
  return date_arr;
}

function valueArray(daysInMonth, achievement_data) {
  var val_arr = [];
  for (var i = 1; i <= daysInMonth; i++) {
    for (var j = 0; j < achievement_data.length; j++) {
      if (j == achievement_data.length-1) {
        val_arr.push(0)
      } else {
        if (i == achievement_data[j].tgl) {
          val_arr.push(parseFloat(achievement_data[j].okratio))
          break;
        }
      }
    }
  }
  return val_arr;
}
</script>
