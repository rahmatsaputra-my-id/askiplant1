@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart3(result) {
  var rejection_data = result.data_chart_3
  var part_name = Object.values(result.part_name)
  var rejection_data_arr = Object.keys(rejection_data).map((key) => rejection_data[key]);
  var rejection_type = Object.keys(result.data_chart_3)
  Highcharts.chart('chart3', {
      chart: {
          type: 'column'
      },
      title: {
          text: 'Rejection -- Daily Chart in Pcs'
      },
      xAxis: {
          categories: (function() {
              var categories = [];
              for (var i = 1; i <= 31; i++) {
                categories.push(i)
              }
              return categories;
          }())
      },
      yAxis: {
          min: 0,
          title: {
              text: 'in pcs'
          },
          stackLabels: {
              enabled: true,
              style: {
                  fontWeight: 'bold',
                  color: ( // theme
                      Highcharts.defaultOptions.title.style &&
                      Highcharts.defaultOptions.title.style.color
                  ) || 'gray'
              }
          }
      },
      legend: {
          align: 'right',
          x: -30,
          verticalAlign: 'top',
          y: 25,
          floating: true,
          backgroundColor:
              Highcharts.defaultOptions.legend.backgroundColor || 'white',
          borderColor: '#CCC',
          borderWidth: 1,
          shadow: false
      },
      tooltip: {
          headerFormat: '<b>{point.x}</b><br/>',
          pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
      },
      plotOptions: {
          column: {
              stacking: 'normal',
            //   dataLabels: {
            //       enabled: true
            //   }
          }
      },
      series: (function() {
          var series = [];
          rejection_data_arr.forEach((item, i) => {
            var graph_data = Object.values(item)
            series.push({
              showInLegend: false,
              name: rejection_type[i],
              data: graph_data
            });
          });
          return series;
      }())
  });
}

// function getMonthName(month) {
//   var month_name = ['','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
//   return month_name[month];
// }
//
// function dateArray(daysInMonth) {
//   var date_arr = [];
//   for (var i = 1; i <= daysInMonth; i++) {
//     date_arr.push(i)
//   }
//   return date_arr;
// }
</script>
