@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart2c(result) {
  var rejection_data = result.data_chart_2c
  var rejection_type = Object.values(result.rejection_type)
  var rejection_data_arr = Object.keys(rejection_data).map((key) => rejection_data[key]);
  var part_name = Object.keys(result.data_chart_2c)
  Highcharts.chart('chart2c', {
      chart: {
          type: 'column'
      },
      title: {
          text: 'Pareto (TOP 10) Rejection Based on Type of NG'
      },
      xAxis: {
          categories: rejection_type
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
              dataLabels: {
                  enabled: false
              }
          }
      },
      series: (function() {
          var series = [];
          rejection_data_arr.forEach((item, i) => {
            var graph_data = Object.values(item)
            series.push({
              showInLegend: false,
              name: part_name[i],
              data: graph_data
            });
          });
          return series.sort();
      }())
  });
}

</script>
