@extends('layout.master')

@section('content')
<div class="box box-info" id="rawat-inap-app">
  <div class="box-header ">
    <h3 class="box-title">Rawat Inap</h3>
    <div class="inner-box-title">
      <button class="btn btn-info openFormAdd">Tambah</button>
      <div class="input-search-section">
        <input type="text" class="form-control" placeholder="cari..">
        <i class="fa fa-search" aria-hidden="true"></i>
      </div>
    </div>
  </div>

  <div class="box-body">
    <div class="table-responsive">
      <table class="table table-striped text-nowrap">
        <thead>
          <tr>
            <th>No</th>
            <th class="onOrdering" data='id_register' orderby="none">No. Registrasi <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='id_member' orderby="none">No. Pasien <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='pet_name' orderby="none">Nama Hewan <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='complaint' orderby="none">Keluhan <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='registrant' orderby="none">Pendaftar <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='branch_name' orderby="none">Dokter yang menangani <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='created_by' orderby="none">Dibuat Oleh <span class="fa fa-sort"></span></th>
            <th class="onOrdering" data='created_at' orderby="none">Tanggal dibuat <span class="fa fa-sort"></span></th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody id="list-rawat-inap"></tbody>
      </table>
    </div>
  </div>
  <!-- /.box-body -->

  @component('pendaftaran.rawat-inap.modal-rawat-inap') @endcomponent
  @component('layout.modal-confirmation') @endcomponent
  @component('layout.message-box') @endcomponent
</div>
@endsection

@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/pendaftaran-rawat-inap.css') }}">
@endsection
@section('script-content')
  <script src="{{ asset('main/js/pendaftaran/rawat-inap/rawat-inap.js') }}"></script>  
@endsection
@section('vue-content')

@endsection