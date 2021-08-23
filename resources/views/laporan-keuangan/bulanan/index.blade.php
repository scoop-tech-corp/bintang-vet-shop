@extends('layout.master')

@section('content')
<div class="box box-info" id="laporan-keuangan-bulanan-app">
  <div class="box-header">
    <h3 class="box-title">Laporan Keuangan Bulanan</h3>
    <div class="inner-box-title">
      <div class="section-left-box-title">
        <label class="label-date">Pilih Tanggal</label>
        <div class="input-group date">
          <div class="input-group-addon">
            <i class="fa fa-calendar"></i>
          </div>
          <input type="text" class="form-control" id="datepicker" placeholder="mm-yyyy" autocomplete="off">
        </div>

      </div>
      <div class="section-right-box-title">
        <button type="button" class="btn btn-success btn-download-laporan" title="Download Laporan">
          <i class="fa fa-file-excel-o" aria-hidden="true"></i>&nbsp;&nbsp;Download Laporan
        </button>
        <select id="filterCabang" style="width: 100%"></select>
      </div>
    </div>
  </div>

  <div class="box-body">
    <div class="table-responsive">
      <table class="table table-striped text-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th class="onOrdering" data='created_at' orderby="none">Tanggal dibuat <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='branch_name' orderby="none">Nama Cabang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='payment_number' orderby="none">No. Pembayaran <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='item_name' orderby="none">Nama Barang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='category' orderby="none">Kategori Barang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='total_item' orderby="none">Jumlah <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='capital_price' orderby="none">Harga Modal <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='selling_price' orderby="none">Harga Jual <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='profit' orderby="none">Keuntungan <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='overall_price' orderby="none">Harga Keseluruhan <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='created_by' orderby="none">Dibuat Oleh <span class="fa fa-sort"></span></th>
          </tr>
        </thead>
        <tbody id="list-laporan-keuangan-bulanan"></tbody>
      </table>
    </div>
    <div class="m-b-10px m-t-10px">
      <label class="label-support-laporan">Harga Modal</label>
      <span id="harga-modal-txt">Rp. -</span>
    </div>
    <div class="m-b-10px m-t-10px">
      <label class="label-support-laporan">Harga Jual</label>
      <span id="harga-jual-txt">Rp. -</span>
    </div>
    <div class="m-b-10px m-t-10px">
      <label class="label-support-laporan">Keuntungan</label>
      <span id="keuntungan-txt">Rp. -</span>
    </div>
  </div>
</div>

@endsection
@section('script-content')
  <script src="{{ asset('main/js/laporan-keuangan/bulanan/bulanan.js') }}"></script>
  <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/laporan-keuangan.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('vue-content')@endsection
