@extends('layout.master')

@section('content')
<div class="box box-info" id="pembayaran-app">
  <div class="box-header">
    <h3 class="box-title">Pembayaran</h3>
    <div class="inner-box-title">
      <div class="section-left-box-title">
        <button class="btn btn-primary openFormAdd m-r-10px">Tambah</button>
        <button class="btn btn-primary downloadRekap">Download Rekap</button>
      </div>
      <div class="section-right-box-title">
        <div class="input-search-section m-r-10px">
          <input type="text" class="form-control" placeholder="cari..">
          <i class="fa fa-search" aria-hidden="true"></i>
        </div>
        <select id="filterCabang" style="width: 50%"></select>
      </div>
    </div>
  </div>

  <div class="box-body">
    <div class="table-responsive">
      <table class="table table-striped text-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th class="onOrdering" data='created_at' orderby="none">Tanggal Dibuat <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='item_name' orderby="none">Nama Barang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='category' orderby="none">Kategori Barang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='total_item' orderby="none">Jumlah <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='each_price' orderby="none">Harga Satuan <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='overall_price' orderby="none">Harga Keseluruhan <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='created_by' orderby="none">Dibuat Oleh <span class="fa fa-sort"></span></th>
            <th class="columnAction">Aksi</th>
          </tr>
        </thead>
        <tbody id="list-pembayaran">
          <tr class="text-center"><td colspan="9">Tidak ada data.</td></tr>
        </tbody>
      </table>
    </div>
  </div>
</div>

@component('pembayaran.pembayaran-tambah') @endcomponent
@component('layout.modal-confirmation') @endcomponent
@component('layout.message-box') @endcomponent

@endsection
@section('script-content')
  <script src="{{ asset('main/js/pembayaran/pembayaran.js') }}"></script>  
@endsection
@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/pembayaran.css') }}">
@endsection
@section('vue-content')@endsection
