@php
$year = date("Y");
$year_start = $year-5;
@endphp
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">

<nav>
  <div class="nav nav-tabs" id="nav-tab" role="tablist">
    <a class="nav-item nav-link active" id="nav-inayear-tab" data-toggle="tab" href="#nav-inayear" role="tab" aria-controls="nav-inayear" aria-selected="true">In a Year</a>
    <a class="nav-item nav-link" id="nav-inamonth-tab" data-toggle="tab" href="#nav-inamonth" role="tab" aria-controls="nav-inamonth" aria-selected="false">In a Month</a>
    <a class="nav-item nav-link" id="nav-inaday-tab" data-toggle="tab" href="#nav-inaday" role="tab" aria-controls="nav-inaday" aria-selected="false">In a Day</a>
  </div>
</nav>
<div class="tab-content" id="nav-tabContent">
  <div class="tab-pane fade show active" id="nav-inayear" role="tabpanel" aria-labelledby="nav-inayear-tab">
    <div class="card">
      <div class="card-header" id="headingTwo">
        <div class="d-flex justify-content-between">
          <div class="col-4">
            <h2 class="mb-0">
              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="true" aria-controls="#collapseOne">
                Annual Graph
              </button>
            </h2>
          </div>
          <div class="col-8">
            <div class="form-row float-right">
              <div class="col-8">
                <div class="form-group">
                  <select class="form-control" id="filter2_tahun" name="filter" required>
                    <option value="">-- Tahun --</option>
                    @for ($i=$year_start; $i <= $year; $i++) <option value="{{$i}}">{{$i}}</option>
                      @endfor
                  </select>
                </div>
              </div>
              <div class="col-4">
                <button type="button" data-toggle="tooltip" data-placement="top" title="Search" type="button" id="btn-chart2" class="btn btn-primary mr-2"><i class="fa fa-search" aria-hidden="true"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo">
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart2"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart2a"></div>
                  </figure>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart2b"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart2d"></div>
                  </figure>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart2c"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart2e"></div>
                  </figure>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane fade" id="nav-inamonth" role="tabpanel" aria-labelledby="nav-inamonth-tab">
    <div class="card">
      <div class="card-header" id="headingThree">
        <div class="d-flex justify-content-between">
          <div class="col-4">
            <h2 class="mb-0">
              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseThree" aria-expanded="true" aria-controls="collapseOne">
                Monthly Graph
              </button>
            </h2>
          </div>
          <div class="col-8">
            <div class="form-row float-right">
              <div class="col-5">
                <div class="form-group">
                  <select class="form-control" id="filter3_tahun" name="filter" required>
                    <option value="">-- Tahun --</option>
                    @for ($i=$year_start; $i <= $year; $i++) <option value="{{$i}}">{{$i}}</option>
                      @endfor
                  </select>
                </div>
              </div>
              <div class="col-5">
                <div class="form-group">
                  <select class="form-control" id="filter3_bulan" name="filter" required>
                    <option value="">-- Bulan --</option>
                    @php
                    $bulan = ['','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp
                    @for ($i=1; $i <= 12; $i++) <option value="{{sprintf("%02d", $i)}}">{{$bulan[$i]}}</option>
                      @endfor
                  </select>
                </div>
              </div>
              <div class="col-2">
                <button type="button" data-toggle="tooltip" data-placement="top" title="Search" type="button" id="btn-chart3" class="btn btn-primary mr-2"><i class="fa fa-search" aria-hidden="true"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="collapseThree" class="collapse show" aria-labelledby="headingThree">
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure" style="width: 100%">
                    <div id="chart3"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure" style="width: 100%">
                    <div id="chart3c"></div>
                  </figure>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart3a"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card mt-3 mb-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart3f"></div>
                  </figure>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="card mt-3 mb-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart3e"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card mt-3 mb-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart3g"></div>
                  </figure>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-6">
              <div class="card mt-3 mb-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure">
                    <div id="chart3b"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6 mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
              @include('report.inc.rejection.chart3d')
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="tab-pane fade" id="nav-inaday" role="tabpanel" aria-labelledby="nav-inaday-tab">
    <div class="card">
      <div class="card-header" id="headingOne">
        <div class="d-flex justify-content-between">
          <div class="col-4">
            <h2 class="mb-0">
              <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                Daily Graph
              </button>
            </h2>
          </div>
          <div class="col-8">
            <div class="form-row float-right">
              <div class="col-3">
                <div class="form-group">
                  <select class="form-control" id="filter1_tahun" name="filter" required>
                    <option value="">Tahun</option>
                    @for ($i=$year_start; $i <= $year; $i++) <option value="{{$i}}">{{$i}}</option>
                      @endfor
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <select class="form-control" id="filter1_bulan" name="filter" required>
                    <option value="">Bulan</option>
                    @php
                    $bulan = ['','Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
                    @endphp
                    @for ($i=1; $i <= 12; $i++) <option value="{{sprintf("%02d", $i)}}">{{$bulan[$i]}}</option>
                      @endfor
                  </select>
                </div>
              </div>
              <div class="col-3">
                <div class="form-group">
                  <select class="form-control" id="filter1_tanggal" name="filter" required>
                    <option value="">Tanggal</option>
                    @php
                    $tanggal = ['','1', '2', '3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16', '17', '18', '19', '20','21', '22', '23', '24', '25', '26', '27', '28', '29', '30','31'];
                    @endphp
                    @for ($i=1; $i <= 31; $i++) <option value="{{sprintf("%02d", $i)}}">{{$tanggal[$i]}}</option>
                      @endfor
                  </select>
                </div>
              </div>
              <div class="col-3">
                <button type="button" data-toggle="tooltip" data-placement="top" title="Search" type="button" id="btn-chart1" class="btn btn-primary mr-2"><i class="fa fa-search" aria-hidden="true"></i></button>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div id="collapseOne" class="collapse show" aria-labelledby="headingOne">
        <div class="card-body">
          <div class="row">
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure" style="width: 100%">
                    <div id="chart1"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure" style="width: 100%">
                    <div id="chart1a"></div>
                  </figure>
                </div>
              </div>
            </div>
            <div class="col-6">
              <div class="card mt-3 mb-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                <div class="card-body">
                  <figure class="highcharts-figure" style="width: 100%">
                    <div id="chart1b"></div>
                  </figure>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>