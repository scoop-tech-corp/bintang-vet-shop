@extends('layout.master')

@section('content')
  {{-- <div class="row">
    <div class="col-md-6">
      <div class="box box-info">
        <div class="box-header with-border">
          <h3 class="box-title">Jumlah pasien per cabang per bulan</h3>
          <div class="box-tools pull-right">
            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
          </div>
        </div>
        <div class="box-body">
          <div class="pull-right">

          </div>
          <div id="container" style="width:100%; height:100%"></div>
        </div>
      </div>
    </div>
  </div> --}}
@endsection

@section('script-content')
  <script src="{{ asset('plugins/highcharts/highstock.js') }}"></script>
  <script src="{{ asset('main/js/index.js') }}"></script>
@endsection