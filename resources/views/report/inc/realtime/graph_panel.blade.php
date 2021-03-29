@php
  $year = date("Y");
  $year_start = $year-5;
@endphp
<input type="hidden" name="_token" id="_token" value="{{csrf_token()}}">

<nav>
    <div class="nav nav-tabs" id="nav-tab" role="tablist">
      <a class="nav-item nav-link active" id="nav-allarea-tab" data-toggle="tab" href="#nav-allarea" role="tab" aria-controls="nav-allarea" aria-selected="true">All Area</a>
      <a class="nav-item nav-link" id="nav-areaAplus-tab" data-toggle="tab" href="#nav-areaAplus" role="tab" aria-controls="nav-areaAplus" aria-selected="false">Area A+</a>
      <a class="nav-item nav-link" id="nav-areaA-tab" data-toggle="tab" href="#nav-areaA" role="tab" aria-controls="nav-areaA" aria-selected="false">Area A</a>
      <a class="nav-item nav-link" id="nav-areaB-tab" data-toggle="tab" href="#nav-areaB" role="tab" aria-controls="nav-areaB" aria-selected="false">Area B</a>
      <a class="nav-item nav-link" id="nav-areaC-tab" data-toggle="tab" href="#nav-areaC" role="tab" aria-controls="nav-areaC" aria-selected="false">Area C</a>
      <a class="nav-item nav-link" id="nav-areaD-tab" data-toggle="tab" href="#nav-areaD" role="tab" aria-controls="nav-areaD" aria-selected="false">Area D</a>
      <a class="nav-item nav-link" id="nav-areaE-tab" data-toggle="tab" href="#nav-areaE" role="tab" aria-controls="nav-areaE" aria-selected="false">Area E</a>
      <a class="nav-item nav-link" id="nav-areaF-tab" data-toggle="tab" href="#nav-areaF" role="tab" aria-controls="nav-areaF" aria-selected="false">Area F</a>
    </div>
</nav>
<div class="tab-content" id="nav-tabContent">
    <div class="tab-pane fade" id="nav-allarea" role="tabpanel" aria-labelledby="nav-allarea-tab">

    </div>
    <div class="tab-pane fade show active" id="nav-areaAplus" role="tabpanel" aria-labelledby="nav-areaAplus-tab">
        <div class="card">
            <div id="collapseThree" class="collapse show" aria-labelledby="headingThree">
              <div class="card-body">
                  <div class="row">
                      <div class="col-4">
                          <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                              <div class="card-body realtimedashboard">
                                  <figure class="highcharts-figure" style="width: 100%">
                                      <div id="chart1"></div>
                                  </figure>
                              </div>
                          </div>
                      </div>
                      <div class="col-4">
                        <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                            <div class="card-body realtimedashboard2">
                                <figure class="highcharts-figure" style="width: 100%">
                                    <div id="chart1c"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                            <div class="card-body realtimedashboard3">
                                <figure class="highcharts-figure" style="width: 100%">
                                    <div id="chart1b"></div>
                                </figure>
                            </div>
                        </div>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-12">
                        <div class="card mt-3 shadow p-3 mb-5 bg-white rounded card-primary card-outline">
                            <div class="card-body">
                                @include('report.inc.realtime.chart1a',['planachiev'=>$planachiev])
                            </div>
                        </div>
                    </div>
                  </div>
              </div>
            </div>
        </div>
    </div>
    <div class="tab-pane fade" id="nav-areaA" role="tabpanel" aria-labelledby="nav-areaA-tab">

    </div>
    <div class="tab-pane fade" id="nav-areaB" role="tabpanel" aria-labelledby="nav-areaB-tab">

    </div>
    <div class="tab-pane fade" id="nav-areaC" role="tabpanel" aria-labelledby="nav-areaC-tab">

    </div>
    <div class="tab-pane fade" id="nav-areaD" role="tabpanel" aria-labelledby="nav-areaD-tab">

    </div>
    <div class="tab-pane fade" id="nav-areaE" role="tabpanel" aria-labelledby="nav-areaE-tab">

    </div>
    <div class="tab-pane fade" id="nav-areaF" role="tabpanel" aria-labelledby="nav-areaF-tab">

    </div>
</div>


