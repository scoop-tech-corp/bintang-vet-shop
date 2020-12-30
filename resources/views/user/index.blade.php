@extends('layout.master')

@section('content')
<div class="box box-info" id="user-app">
  <div class="box-header with-border">
    <h3 class="box-title">User Management</h3>
    <div class="inner-box-title">
      <button class="btn btn-info" @click="openFormAdd">Tambah</button>
      <div class="input-search-section">
        <input type="text" class="form-control" placeholder="search.." v-model="searchTxt" @keydown.enter="onSearch">
        <i class="fa fa-search" aria-hidden="true"></i>
      </div>
    </div>
  </div>
  <!-- /.box-header -->

  <div class="box-body table-responsive">
    <table class="table table-striped text-nowrap">
      <thead>
        <tr>
          <th>No</th>
          <th @click="onOrdering('staffing_number')">No. Kepegawaian
            <span v-if="columnStatus.staffing_number == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.staffing_number == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.staffing_number == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('username')">Username
            <span v-if="columnStatus.username == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.username == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.username == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('fullname')">Nama Lengkap
            <span v-if="columnStatus.fullname == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.fullname == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.fullname == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('email')">Email
            <span v-if="columnStatus.email == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.email == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.email == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('role')">Role User
            <span v-if="columnStatus.role == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.role == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.role == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('branch_name')">Cabang
            <span v-if="columnStatus.branch_name == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.branch_name == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.branch_name == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('status')">status
            <span v-if="columnStatus.status == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.status == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.status == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('created_by')">Dibuat oleh
            <span v-if="columnStatus.created_by == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.created_by == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.created_by == 'none'" class="fa fa-sort"></span>
          </th>
          <th @click="onOrdering('created_at')">Tanggal dibuat
            <span v-if="columnStatus.created_at == 'desc'" class="fa fa-sort-desc"></span>
            <span v-if="columnStatus.created_at == 'asc'" class="fa fa-sort-asc"></span>
            <span v-if="columnStatus.created_at == 'none'" class="fa fa-sort"></span>
          </th>
          <th>Aksi</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(item, index) in listUser">
          <td>@{{ index + 1 }}</td>
          <td>@{{ item.staffing_number }}</td>
          <td>@{{ item.username }}</td>
          <td>@{{ item.fullname }}</td>
          <td>@{{ item.email }}</td>
          <td>@{{ item.role }}</td>
          <td>@{{ item.branch_name }}</td>
          <td>@{{ item.status ? 'Aktif' : 'Non Aktif' }}</td>
          <td>@{{ item.created_by }}</td>
          <td>@{{ item.created_at }}</td>
          <td>
            <button type="button" class="btn btn-warning" @click="openFormUpdate(item)"><i class="fa fa-pencil" aria-hidden="true"></i></button>
          </td>
        </tr>
      </tbody>
    </table>
  </div>
  <!-- /.box-body -->

  @component('user.modal-user') @endcomponent

  @component('layout.modal-confirmation') @endcomponent

  @component('layout.message-box') @endcomponent

</div>
@endsection
@section('script-content')

@endsection
@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/user.css') }}">
@endsection
@section('vue-content')
  <script src="{{ asset('main/js/user/user-vue.js') }}"></script>
@endsection
