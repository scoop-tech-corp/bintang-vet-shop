@extends('layout.master')

@section('content')
<div class="box box-info" id="pasien-app">
  <div class="box-header with-border">
    <h3 class="box-title">Pasien</h3>
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
          <th class="onOrdering" data='id_member' orderby="none">No. Registrasi <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='pet_category' orderby="none">Jenis Hewan <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='pet_name' orderby="none">Nama Hewan <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='pet_gender' orderby="none">Jenis Kelamin <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='pet_year_age' orderby="none">Usia Hewan <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='branch_name' orderby="none">Cabang <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='created_by' orderby="none">Didaftarkan Oleh <span class="fa fa-sort"></span></th>
          <th class="onOrdering" data='created_at' orderby="none">Tanggal Daftar <span class="fa fa-sort"></span></th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody id="list-pasien">
        {{-- <tr v-for="(item, index) in listPasien">
          <td>@{{ index + 1 }}</td>
          <td>@{{ item.id_member }}</td>
          <td>@{{ item.pet_category }}</td>
          <td>@{{ item.pet_name }}</td>
          <td>@{{ item.pet_gender }}</td>
          <td>@{{ item.pet_year_age + ' Tahun' }}</td>
          <td>@{{ item.branch_name }}</td>
          <td>@{{ item.created_by }}</td>
          <td>@{{ item.created_at }}</td>
          <td>
            <button type="button" class="btn btn-warning" @click="openFormUpdate(item)"><i class="fa fa-pencil" aria-hidden="true"></i></button>
            <button type="button" class="btn btn-danger" @click="openFormDelete(item)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>
        </tr> --}}
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->

  @component('pasien.modal-pasien') @endcomponent

  @component('layout.modal-confirmation') @endcomponent

  @component('layout.message-box') @endcomponent

</div>
@endsection
@section('script-content')
  <script src="{{ asset('main/js/pasien/pasien.js') }}"></script>
@endsection
@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/pasien.css') }}">
@endsection
@section('vue-content')@endsection
