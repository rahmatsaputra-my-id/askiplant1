@extends('layout/main')

@section('title', 'ASKI PLANT 1')

@section('container')

<div class="contents">
  <div class="row" style="margin-bottom:-20px">
    <div class="col">
      <h4 class="text-center float-right">Realtime Dashboard</h4>
    </div>
  </div>
  @include('report.inc.realtime.graph_panel')

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
  @include('report.inc.realtime.chart1')
  @include('report.inc.realtime.chart1b')
  @include('report.inc.realtime.chart1c')
  {{-- @include('report.inc.realtime.chart2')
  @include('report.inc.realtime.chart3') --}}

  <script type="text/javascript">

    $(document).ready(function() {
      loadChart()
      loadChart2()
      loadChart3()
      setInterval(function(){
        loadChart()
        loadChart2()
        loadChart3()
      }, 4000);
    })

    function loadChart() {
      $.ajax({
        url: '{{url('/getRejectionRealtime')}}',
        type: 'get',
        success: function(result) {
          $('.realtimedashboard').each(function(){
              drawChart1(result);
          })
        }
      });

    }
    function loadChart2() {
      $.ajax({
        url: '{{url('/getRejectionTypeRealtime')}}',
        type: 'get',
        success: function(result) {
          $('.realtimedashboard2').each(function(){
              drawChart1c(result);
          })
        }
      });

    }
    function loadChart3() {
      $.ajax({
        url: '{{url('/getOKRatioRealtime')}}',
        type: 'get',
        success: function(result) {
          $('.realtimedashboard3').each(function(){
              drawChart1b(result);
          })
        }
      });

    }
  </script>
@endsection
