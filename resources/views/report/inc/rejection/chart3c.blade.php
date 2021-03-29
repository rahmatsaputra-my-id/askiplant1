@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart3c(result, daysInMonth) {
  var rejection_data = result.data_chart_3c
  var date_arr = dateArray(daysInMonth);
  var value_arr = valueArray(daysInMonth, rejection_data);
  Highcharts.chart('chart3c', {

    title: {
        text: 'Rejection Ratio -- Chart in percent'
    },

    yAxis: {
        title: {
            text: 'Percent Rejection'
        },
        plotLines: [{
            color: '#FF0000',
            width: 2,
            dashStyle: 'LongDash',
            value: 1
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
        name: '% Rejection',
        data: value_arr
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

function valueArray(daysInMonth, rejection_data) {
  var val_arr = [];
  for (var i = 1; i <= daysInMonth; i++) {
    for (var j = 0; j < rejection_data.length; j++) {
      if (j == rejection_data.length-1) {
        val_arr.push(0)
      } else {
        if (i == rejection_data[j].tgl) {
          val_arr.push(parseFloat(rejection_data[j].rejectpersen))
          break;
        }
      }
    }
  }
  return val_arr;
}
</script>
