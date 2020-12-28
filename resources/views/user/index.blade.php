@extends('layout.master')

@section('content')
<div class="box box-info" id="user-app">
  <div class="box-header with-border">
    <h3 class="box-title">User Management</h3>
    <div class="inner-box-title">
      <button class="btn btn-info" @click="openFormAdd">Tambah</button>
      <div class="search-section">
        <input type="text" class="form-control" placeholder="search.." v-model="searchTxt" @keydown.enter="onSearch">
        <i class="fa fa-search" aria-hidden="true"></i>
      </div>
    </div>
  </div>
  <!-- /.box-header -->
  <!-- form start -->
  <div class="box-body table-responsive">
    <table id="table-cabang" class="table table-striped text-nowrap">
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

  <div class="modal fade" id="modal-user">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title">@{{titleModal}}</h4>
        </div>
        <div class="modal-body">
          <form>
            <div class="box-body">
              <table v-if="stateModal == 'edit'">
                <tr>
                  <td style="font-weight: 700; padding-bottom: 15px;">Nomor Kepegawaian</td>
                  <td style="padding-bottom: 15px; padding-left: 40px;">@{{nomorPegawai}}</td>
                </tr>
                <tr>
                  <td style="font-weight: 700; padding-bottom: 15px;">Username</td>
                  <td style="padding-bottom: 15px; padding-left: 40px;">@{{username}}</td>
                </tr>
                <tr>
                  <td style="font-weight: 700; padding-bottom: 15px;">Email</td>
                  <td style="padding-bottom: 15px; padding-left: 40px;">@{{email}}</td>
                </tr>
                <tr>
                  <td style="font-weight: 700; padding-bottom: 15px;">Nomor Ponsel</td>
                  <td style="padding-bottom: 15px; padding-left: 40px;">@{{nomPonsel}}</td>
                </tr>
              </table>
              <div class="form-group" v-if="stateModal == 'add'">
                <label for="username">Username</label>
                <input type="text" class="form-control" @keyup="usernameKeyup" v-model="username" placeholder="Masukan Username">
                <div class="validate-error" v-if="usernameErr1">User name harus di isi</div>
              </div>
              <div class="form-group">
                <label for="namaLengkap">Nama Lengkap</label>
                <input type="text" class="form-control" @keyup="namaLengkapKeyup" v-model="namaLengkap" placeholder="Masukan Nama Lengkap">
                <div class="validate-error" v-if="namaLengkapErr1">Nama lengkap harus di isi</div>
              </div>
              <div class="form-group" v-if="stateModal == 'add'">
                <label for="email">Email</label>
                <input type="text" class="form-control" @keyup="emailKeyup" v-model="email" placeholder="Masukan Alamat Email">
                <div class="validate-error" v-if="emailErr1">Email harus di isi</div>
              </div>
              <div class="form-group" v-if="stateModal == 'add'">
                <label for="password">Password</label>
                <div class="p-relative">
                  <input :type="passwordType1" class="form-control" @keyup="passwordKeyup" v-model="password" placeholder="Masukan Password">
                  <span @click="togglePassword" class="glyphicon icon-password" 
                    :class="{ 'glyphicon-eye-open': showPassword1, 'glyphicon-eye-close': !showPassword1 }"></span>
                </div>
                <div class="validate-error" v-if="passwordErr1">Password harus di isi</div>
                <div class="validate-error" v-if="passwordErr2">Password dan Konfirmasi Password harus sama</div>
              </div>
              <div class="form-group" v-if="stateModal == 'add'">
                <label for="confPassword">Konfirmasi Password</label>
                <div class="p-relative">
                  <input :type="passwordType2" class="form-control" @keyup="confPasswordKeyup" v-model="confPassword" placeholder="Masukan Konfirmasi Password">
                  <span @click="toggleConfPassword" class="glyphicon icon-password" 
                    :class="{ 'glyphicon-eye-open': showPassword2, 'glyphicon-eye-close': !showPassword2 }"></span>
                </div>
                <div class="validate-error" v-if="confPasswordErr1">Konfirmasi Password harus di isi</div>
              </div>
              <div class="form-group" v-if="stateModal == 'add'">
                <label for="email">Nomor Ponsel</label>
                <input type="number" class="form-control" @keyup="nomPonselKeyup" v-model="nomPonsel" placeholder="Masukan Nomor Ponsel">
                <div class="validate-error" v-if="nomPonselErr1">Nomor Ponsel harus di isi</div>
              </div>
              <div class="form-group">
                <label for="role">Role</label>
                <select class="form-control" @change="onSelectRole($event)" v-model="selectRole">
                  <option selected="selected" value="">Select Role</option>
                  <option value="admin">Admin</option>
                  <option value="resepsionis">Resepsionis</option>
                  <option value="dokter">Dokter</option>
                </select>
                <div class="validate-error" v-if="roleErr1">Role harus di isi</div>
              </div>
              <div class="form-group">
                <label for="cabang">Cabang</label>
                <select class="form-control" @change="onSelectCabang($event)" v-model="selectCabang">
                  <option selected="selected" value="">Select Cabang</option>
                  <option v-for="cabang in listCabang" :value="cabang">@{{cabang.branch_name}}</option>
                </select>
                <div class="validate-error" v-if="cabangErr1">Cabang harus di isi</div>
              </div>
              <div class="form-group">
                <label for="cabang">Status</label>
                <select class="form-control" @change="onSelectStatus($event)" v-model="selectStatus">
                  <option selected="selected" value="">Select Status</option>
                  <option value=1>Aktif</option>
                  <option value=0>Non Aktif</option>
                </select>
                <div class="validate-error" v-if="statusErr1">Status harus di isi</div>
              </div>
              <div class="form-group">
                <div class="validate-error" v-if="beErr" v-html="msgBeErr"></div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" :disabled="validateSimpanUser" @click="submitUser">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="modal-confirmation">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">Peringatan!</h4>
        </div>
        <div class="modal-body">
          <div class="box-body">
            @{{confirmContent}}
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" @click="submitConfirm">Ya</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Tidak</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>

  <div class="modal fade" id="msg-box">
    <div class="modal-dialog modal-sm">
      <div class="modal-content">
        <div class="modal-body">
          @{{msgContent}}
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
        </div>
      </div>
    </div>
  </div>

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
