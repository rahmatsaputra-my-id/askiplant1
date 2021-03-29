@extends('layout/main')

@section('title', 'ASKI PLANT 1')

@section('container')

<div class="contents">
  <div class="row" style="margin-bottom:-20px">
    <div class="col">
      <h4 class="text-center float-right">Achievement Chart</h4>
    </div>
  </div>
  @include('report.inc.achievement.graph_panel')

  <div class="row justify-content-between">
    <h4 class="mt-3 order-date"></h4>
    <h6 class="pull-right mt-3 last-load-data"></h6>
  </div>

</div>
@endsection

@section('js')
  <link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}">
  {{-- <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script> --}}
  <script src="{{asset('js/highcharts.js')}}"></script>
  <script src="{{asset('js/highcharts-exporting.js')}}"></script>
  <script src="{{asset('js/highcharts-export-data.js')}}"></script>
  <script src="{{asset('js/highcharts-accessibility.js')}}"></script>
  <script src="{{asset('js/jquery.dataTables.min.js')}}"></script>
  {{-- @include('report.inc.rejection.chart1') --}}
  @include('report.inc.achievement.chart2')
  @include('report.inc.achievement.chart2a')
  @include('report.inc.achievement.chart3a')
  @include('report.inc.achievement.chart3')
  {{-- @include('report.inc.achievement.chart3a') --}}

  <script type="text/javascript">
    var url = "{{url('/getDataTable')}}";
    var url2 = "{{url('/getDataTable2')}}";
    var _token = $('#_token').val();
    var d = new Date();
    var tahun_akhir = d.getFullYear();
    var tahun_awal = tahun_akhir - 5;
    var bulan = d.getMonth()+1;
    bulan = bulan.toString();
    while (bulan.length < 2) bulan = "0" + bulan;
    $('#filter3_tahun').val(tahun_akhir);
    $('#filter3_bulan').val(bulan);
    var no = 0;

    var dtable = $('#dataTable').DataTable({
       "responsive": true,
       "paging": true,
       "ordering": true,
       "info": true,
       "bFilter": false,
       "searching": true,
       "processing": true,
     columns: [
       {
         data: "sum",
         render: function (data, type, row, meta) {
           no++
           return no;
         },
       },
       {
         data: "NamaPart"
       },
       {
         data: "RejectionType"
       },
       {
         data: "sum"
       }
     ],
     ajax: {
       url: "{{url('/getTableData')}}",
       method: 'POST',
       dataType: 'JSON',
       "data": function(d) {
         d._token = "{{ csrf_token() }}",
         d.tahun = $('#filter3_tahun').val();
         d.bulan = $('#filter3_bulan').val();
       },
       dataSrc:""
     },
     pageLenght: 10,
    });

     var dtable2 = $('#dataTable2').DataTable({
       "responsive": true,
       "paging": true,
       "ordering": true,
       "info": true,
       "bFilter": false,
       "searching": true,
       "processing": true,
        columns: [
        {
            data: "tanggal"
        },
        {
            data: "NamaPart"
        },
        {
            data: "RejectionType"
        },
        {
            data: "sum"
        }
        ],
        ajax: {
        url: "{{url('/getTableData2')}}",
        method: 'POST',
        dataType: 'JSON',
        "data": function(d) {
            d._token = "{{ csrf_token() }}",
            d.tahun = $('#filter3_tahun').val();
            d.bulan = $('#filter3_bulan').val();
        },
        dataSrc:""
        },
        pageLenght: 10,
     });

     $('#tahun')

    $(document).ready(function() {
      chart_annual(tahun_akhir, _token)
      chart_monthly(tahun_akhir, bulan, _token)
    //   chart3b(tahun_akhir, bulan, _token)
    })

    $('#btn-chart2').click(function() {
      var tahun = $('#filter2_tahun').val();
      chart_annual(tahun, _token)
      no = 0;
      dtable.ajax.reload();
    })

    $('#btn-chart3').click(function() {
      var tahun = $('#filter3_tahun').val();
      var bulan = $('#filter3_bulan').val();
      console.log(tahun);
      chart_monthly(tahun_akhir, bulan, _token)
    //   chart3b(tahun_akhir, bulan, _token)
      no = 0;
      dtable.ajax.reload();
    })

    function chart_annual(tahun, _token) {
      $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': _token
        }
      });
      $.ajax({
         type: 'POST',
         url: 'getAchievement',
         data: {
           filter: 'annual',
           tahun: tahun,
         },
         success:function(result) {
           if (result.data_chart_2 == 'false') {
             alert('data tidak ditemukan')
           } else {
             drawChart2(result);
             drawChart2a(result);
           }
         }
      });
    }

    function chart_monthly(tahun, bulan, _token) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': _token
          }
        });
        $.ajax({
           type: 'POST',
           url: 'getAchievement',
           data: {
             filter: 'monthly',
             tahun: tahun,
             bulan: bulan
           },
           success:function(result) {
             var daysInMonth = getDaysInMonth(bulan, tahun)
             drawChart3(result, daysInMonth);
             drawChart3a(result, daysInMonth);
           }
        });
      }

    function chart3b(tahun, bulan, _token) {
        $.ajaxSetup({
          headers: {
            'X-CSRF-TOKEN': _token
          }
        });
        $.ajax({
           type: 'POST',
           url: 'getTableData',
           data: {
             filter: 'monthly',
             tahun: tahun,
             bulan: bulan
           },
           success:function(result) {
             drawChart3b(result);
           }
        });
    }

    function getDaysInMonth(month,year) {
       return new Date(year, month, 0).getDate();
    };

    function getMonthName(month) {
      var month_name = ['','Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
      return month_name[month];
    }
  </script>
@endsection
