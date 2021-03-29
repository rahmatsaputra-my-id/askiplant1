<script>
function drawChart1(result) {
  var data = result.data;
  if (data.length > 0) {
    var tahun_awal = result.tahun_awal;
    var tahun_akhir = result.tahun_akhir;
    var tahun = parseInt(tahun_awal);
    var oem = [];
    var am = [];
    var average = [];
    var tahun_arr = [];
    var range = parseInt(tahun_akhir) - parseInt(tahun_awal);

    for (var i = 0; i <= range; i++) {
      tahun_arr.push(tahun.toString(10))
      tahun++;
    }

    for (var j = 0; j < data.length; j++) {
      if (data[j].cluster == 'OEM') {
        for (var i = 0; i <= range ; i++) {
          if ((data[j].tahun) == tahun_arr[i]) {
            oem[i] = parseInt(data[j].total_actual)/parseInt(data[j].total_plan)*100;
            continue;
          } else {
            if (oem[i]) {
              continue;
            } else {
              oem[i] = 0;
            }
          }
        }
      }

      if (data[j].cluster == 'AM') {
        for (var i = 0; i <= range ; i++) {
          if ((data[j].tahun) == tahun_arr[i]) {
            am[i] = parseInt(data[j].total_actual)/parseInt(data[j].total_plan)*100;
            continue;
          } else {
            if (am[i]) {
              continue;
            } else {
              am[i] = 0;
            }
          }
        }
      }
    }

    console.log(am);

    for (var i = 0; i <= range; i++) {
      if (!oem[i] && am[i]) {
        var x = (0+am[i])/2;
        average.push(x)
      } else if (!am[i] && oem[i]) {
        var x = (oem[i]+0)/2;
        average.push(x)
      } else {
        average.push(0);
      }
    }

    Highcharts.chart('chart1', {
      type:'line',
      title: {
        text: 'Delivery Performance '+tahun_awal+' - '+tahun_akhir
      },

      legend: {
        layout: 'vertical',
        align: 'right',
        verticalAlign: 'middle'
      },

      xAxis: {
        categories: tahun_arr
      },

      yAxis: {
          labels: {
            format: '{value}%'
          },
          title: {
            enabled: false
          },
          max:100
      },

      series: [{
        name: 'OEM',
        data: oem,
        visible: false
      },{
        name: 'AM',
        data: am,
        visible: false
      },
      {
        name: 'AVERAGE',
        data: Object.values(average),
        color:'#07FB68'
      }],


      responsive: {
        rules: [{
          condition: {
            maxWidth: 100
          },
          chartOptions: {
            legend: {
              layout: 'horizontal',
              align: 'left',
              verticalAlign: 'bottom'
            }
          }
        }]
      }
    });
  } else {
    alert('data not found');
  }

}

function getMonthName(month) {
  var month_name = ['','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  return month_name[month];
}
</script>
