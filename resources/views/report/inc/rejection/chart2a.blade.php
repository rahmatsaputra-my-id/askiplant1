@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart2a(result, monthsInYear) {
  var rejection_data = result.data_chart_2a
  var value_arr = valueArray(monthsInYear, rejection_data);

  for (var i = 0; i < 12; i++) {
    for (var j = 0; j <= rejection_data.length; j++) {
      if (j == rejection_data.length) {
        value_arr.push(0)
      } else {
        if (rejection_data[j].bulan == i+1) {
          value_arr.push(parseFloat(rejection_data[j].rejectpersen))
          break;
        }
      }
    }
  }

  console.log(value_arr);

  Highcharts.chart('chart2a', {

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
      categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
    },

    legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
    },

    series: [{
        name: '% Rejection',
        data: value_arr
    }],

    plotOptions: {
        line: {
            dataLabels: {
                enabled: true
            },
            enableMouseTracking: false
        }
    },

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
  var month_name = ['','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  return month_name[month];
}
</script>
