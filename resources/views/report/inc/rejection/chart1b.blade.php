@section('style')
<link href="{{ asset('css/highchart.css') }}" rel="stylesheet">
@endsection

<script>
   function drawChart1b(result) {
      console.log("===>result_chart_1b", result);
      var rejection_data = result.rejection_per_type_part_data;
      var rejection_type = Object.values(result.rejection_type);
      var part_name = Object.keys(result.rejection_per_type_part_data);

      var rejection_data_shift = result.data_chart_1b;
      var rejection_data_shift_arr = Object.keys(rejection_data_shift).map((key) => rejection_data_shift[key]);
      var shift_name = result.shift_name;
      var get_rejection_type_by_shift = Object.values(result.get_rejection_type_by_shift);
      
      Highcharts.chart('chart1b', {
         chart: {
            type: 'column'
         },
         title: {
            text: 'Pareto (TOP 10) Rejection Based on Type of NG Shift Daily'
         },
         xAxis: {
            categories: get_rejection_type_by_shift
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
         tooltip: {
            headerFormat: '<b>{point.x}</b><br/>'
            // pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
         },
         plotOptions: {
            column: {
               pointPadding: 0.2,
               borderWidth: 0
            }
         },
         series: (function() {
            var series = [];
            rejection_data_shift_arr.forEach((item, i) => {
               var graph_data = Object.values(item);
               series.push({
                  showInLegend: false,
                  name: shift_name[i],
                  data: graph_data
               });
            });
            return series.sort();
         }())
      });
   }
</script>