@extends('layout.master')

@section('content')
<div class="box box-info" id="daftar-jasa-app">
  <div class="box-header with-border">
    <h3 class="box-title">Daftar Jasa</h3>
    <div class="inner-box-title">
      <button class="btn btn-info openFormAdd">Tambah</button>
      <div class="d-flex width-350px">
        <div class="input-search-section m-r-10px">
          <input type="text" class="form-control" placeholder="cari..">
          <i class="fa fa-search" aria-hidden="true"></i>
        </div>
        <select id="filterCabang" style="width: 50%"></select>
      </div>
    </div>
  </div>

  <div class="box-body table-responsive">
    <table class="table table-striped text-nowrap">
      <thead>
        <tr>
          <th>No</th>
          <th class="onOrdering" data='service_name' orderby="none">Jenis Pelayanan <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='category_name' orderby="none">Kategori <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='branch_name' orderby="none">Cabang <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='created_by' orderby="none">Dibuat Oleh <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='created_at' orderby="none">Tanggal dibuat <span class="fa fa-sort"></span></th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="list-daftar-jasa"></tbody>
    </table>
  </div>
  <!-- /.box-body -->

  @component('layanan.daftar-jasa.modal-daftar-jasa') @endcomponent
  @component('layout.modal-confirmation') @endcomponent
  @component('layout.message-box') @endcomponent
</div>
@endsection
@section('script-content')
  <script src="{{ asset('main/js/layanan/daftar-jasa/daftar-jasa.js') }}"></script>  
@endsection
@section('vue-content')

@endsection
