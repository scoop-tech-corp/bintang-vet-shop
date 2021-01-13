@extends('layout.master')

@section('content')
<div class="box box-info" id="pasien-app">
  <div class="box-header with-border">
    <h3 class="box-title">Pasien</h3>
    <div class="inner-box-title">
      <button class="btn btn-info" @click="openFormAdd">Tambah</button>
      <div class="input-search-section">
        <input type="text" class="form-control" placeholder="cari.." v-model="searchTxt" @keydown.enter="onSearch">
        <i class="fa fa-search" aria-hidden="true" @click="onSearch"></i>
      </div>
    </div>
  </div>

  <div class="box-body table-responsive">
    <table class="table table-striped text-nowrap">
      <thead>
        <tr>
          <th>No</th>
          <th @click="onOrdering('id_member')">No. Registrasi
            <span v-if="columnStatus.id_member == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.id_member == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.id_member == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('pet_category')">Jenis Hewan
            <span v-if="columnStatus.pet_category == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.pet_category == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.pet_category == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('pet_name')">Nama Hewan
            <span v-if="columnStatus.pet_name == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.pet_name == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.pet_name == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('pet_gender')">Jenis Kelamin
            <span v-if="columnStatus.pet_gender == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.pet_gender == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.pet_gender == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('pet_year_age')">Usia Hewan
            <span v-if="columnStatus.pet_year_age == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.pet_year_age == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.pet_year_age == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('branch_name')">Cabang
            <span v-if="columnStatus.branch_name == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.branch_name == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.branch_name == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('created_by')">Didaftarkan Oleh
            <span v-if="columnStatus.created_by == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.created_by == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.created_by == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('created_at')">Tanggal Daftar
            <span v-if="columnStatus.created_at == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.created_at == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.created_at == 'none'" class="fa fa-sort"></span>
          </th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in listPasien">
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
            {{-- <button type="button" class="btn btn-info" @click="openFormDetail(item)"><i class="fa fa-eye" aria-hidden="true"></i></button> --}}
            <button type="button" class="btn btn-warning" @click="openFormUpdate(item)"><i class="fa fa-pencil" aria-hidden="true"></i></button>
            <button type="button" class="btn btn-danger" @click="openFormDelete(item)"><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>
        </tr>
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
  
@endsection
@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/pasien.css') }}">
@endsection
@section('vue-content')
  <script src="{{ asset('main/js/pasien/pasien-vue.js') }}"></script>
@endsection
