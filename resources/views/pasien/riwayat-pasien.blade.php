@extends('layout.master')

@section('content')
<div class="box box-info" id="riwayat-pasien-app">
  <div class="box-header ">
    <h3 class="box-title">Riwayat Pemeriksaan</h3>
    <div class="inner-box-title">
      <div class="section-left-box-title">
        {{-- <button class="btn btn-info openFormAdd">Tambah</button> --}}
      </div>
      <div class="section-right-box-title">
        {{-- <div class="input-search-section m-r-10px">
          <input type="text" class="form-control" placeholder="cari..">
          <i class="fa fa-search" aria-hidden="true"></i>
        </div> --}}
      </div>
    </div>
  </div>

  <div class="box-body">
    <div class="table-responsive">
      <table class="table table-striped text-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th>No. Registrasi</th>
            <th>Keluhan</th>
            <th>Nama Pendaftar Pasien</th>
            <th>Dibuat Oleh</th>
            <th>Tanggal dibuat</th>
            <th class="columnAction">Aksi</th>
          </tr>
        </thead>
        <tbody id="list-riwayat-pasien">
          <tr class="text-center"><td colspan="7">Tidak ada data.</td></tr>
        </tbody>
      </table>
    </div>
    <div class="m-t-10px">
      <button id="btnKembali" type="button" class="btn btn-default pull-right">Kembali</button>
    </div>
  </div>
  <!-- /.box-body -->

  @component('pasien.modal-detail-riwayat-pasien') @endcomponent
</div>
@endsection
@section('script-content')
  <script src="{{ asset('main/js/pasien/riwayat-pasien.js') }}"></script>
@endsection
@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/pasien.css') }}">
@endsection
@section('vue-content')@endsection
