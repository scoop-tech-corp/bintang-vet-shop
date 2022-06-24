@extends('layout.master')

@section('content')

<div class="box box-info" id="barang-dog-food-app">
  <div class="box-header ">
    <h3 class="box-title">Barang Dog Food</h3>
    <div class="inner-box-title">
      <div class="section-left-box-title"></div>
      <div class="section-right-box-title">
        <div class="input-search-section m-r-10px">
          <input type="text" class="form-control" placeholder="cari..">
          <i class="fa fa-search" aria-hidden="true"></i>
        </div>
      </div>
    </div>
  </div>

  <div class="box-body">
    <div class="table-responsive">
      <table class="table table-striped text-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th class="onOrdering" data='item_name' orderby="none">Nama Barang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='total_item' orderby="none">Jumlah Barang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='limit_item' orderby="none">Limit Barang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='expired_date' orderby="none">Tanggal Kedaluwarsa <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='selling_price' orderby="none">Harga Jual <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='capital_price' orderby="none">Harga Modal <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='profit' orderby="none">Keuntungan <span class="fa fa-sort"></span></th>
            <th data='image'>Foto</th>
            <th class="onOrdering" data='branch_name' orderby="none">Cabang <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='created_by' orderby="none">Dibuat Oleh <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='created_at' orderby="none">Tanggal dibuat <span class="fa fa-sort"></span></th>
            <th class="columnAction">Aksi</th>
          </tr>
        </thead>
        <tbody id="list-dog-food">
          <tr class="text-center"><td colspan="11">Tidak ada data.</td></tr>
        </tbody>
      </table>
    </div>
  </div>

  @component('gudang.dog-food.modal-dog-food') @endcomponent
  @component('gudang.dog-food.upload-dog-food') @endcomponent
  @component('layout.modal-confirmation') @endcomponent
  @component('layout.message-box') @endcomponent
@endsection
@section('script-content')
  <script src="{{ asset('plugins/magnific-popup/jquery.magnific-popup.js') }}"></script>
  <script src="{{ asset('plugins/jquery.ui.widget.js') }}"></script>
  <script src="{{ asset('plugins/jquery.iframe-transport.js') }}"></script>
  <script src="{{ asset('plugins/jquery.fileupload.js') }}"></script>
  <script src="{{ asset('plugins/jquery.fileupload-ui.js') }}"></script>
  <script src="{{ asset('plugins/jquery.fileupload-process.js') }}"></script>
  <script src="{{ asset('plugins/jquery.fileupload-validate.js') }}"></script>
  <script src="{{ asset('plugins/jquery.mask.js') }}"></script>
  <script src="{{ asset('main/js/gudang/dog-food/dog-food.js') }}"></script>
  <script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
@endsection
@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/dog-food.css') }}">
  <link rel="stylesheet" type='text/css' href="{{ asset('plugins/magnific-popup/magnific-popup.css') }}">
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
@endsection
@section('vue-content') @endsection