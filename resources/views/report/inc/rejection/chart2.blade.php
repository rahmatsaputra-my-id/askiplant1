<script>
function drawChart2(result) {
  var rejection_data = result.data_chart_2
  var part_name = Object.values(result.part_name)
  var rejection_data_arr = Object.keys(rejection_data).map((key) => rejection_data[key]);
  var rejection_type = Object.keys(result.data_chart_2)
  var data = result.data;

//   Highcharts.setOptions({
//     colors: ['#058DC7', '#50B432', '#ED561B', '#b3b507', '#24CBE5','#ad1372','#8f4203','#b30404', '#64E572', '#FF9655', '#FFF263', '#6AF9C4','#cf63b7','#d1a060','#de5b5b']
// });
  Highcharts.chart('chart2', {
      chart: {
          type: 'column'
      },
      title: {
          text: 'Rejection -- Monthly Chart in Pcs'
      },
      xAxis: {
        categories: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des']
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
</script>
