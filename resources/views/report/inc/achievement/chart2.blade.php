@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart2(result, monthsInYear) {
  var achievement_data = result.data_chart_2
  var value_arr = valueArray(monthsInYear, achievement_data);

  for (var i = 0; i < 12; i++) {
    for (var j = 0; j <= achievement_data.length; j++) {
      if (j == achievement_data.length) {
        value_arr.push(0)
      } else {
        if (achievement_data[j].bulan == i+1) {
          value_arr.push(parseFloat(achievement_data[j].okratio))
          break;
        }
      }
    }
  }

  console.log(value_arr);

  Highcharts.chart('chart2', {

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
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
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

function getMonthName(month) {
  var month_name = ['','Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  return month_name[month];
}
</script>
