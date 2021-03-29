@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
function drawChart3g(result) {
  var rejection_data = result.data_chart_3g;
  Highcharts.chart('chart3g', {
      chart: {
          plotBackgroundColor: null,
          plotBorderWidth: null,
          plotShadow: false,
          type: 'pie'
      },
      title: {
          text: 'Percentage of Rejection (Top 10) Based on Type of NG'
      },
      tooltip: {
          pointFormat: '{series.name}: <b>{point.percentage:.1f}%</b>'
      },
      accessibility: {
          point: {
              valueSuffix: '%'
          }
      },
      plotOptions: {
          pie: {
              allowPointSelect: true,
              cursor: 'pointer',
              dataLabels: {
                  enabled: true,
                  format: '<b>{point.name}</b>: {point.percentage:.1f} %'
              }
          }
      },
      series: [{
          name: 'Percentage',
          colorByPoint: true,
          data: (function() {
              var graph_data = [];
              rejection_data.forEach((item, i) => {
                graph_data.push({
                  name: item.RejectionType,
                  y: item.rejectbulantahun1
                });
              });
              return graph_data;
          }())
      }]
  });
}

</script>
