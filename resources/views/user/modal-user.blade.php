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
                  <td class="detail-label">Nomor Kepegawaian</td>
                  <td class="detail-value">@{{nomorPegawai}}</td>
                </tr>
                <tr>
                  <td class="detail-label">Username</td>
                  <td class="detail-value">@{{username}}</td>
                </tr>
                <tr>
                  <td class="detail-label">Email</td>
                  <td class="detail-value">@{{email}}</td>
                </tr>
                <tr>
                  <td class="detail-label">Nomor Ponsel</td>
                  <td class="detail-value">@{{nomPonsel}}</td>
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
                  <input :type="passwordType1" class="form-control p-right-42px" @keyup="passwordKeyup" v-model="password" placeholder="Masukan Password">
                  <span @click="togglePassword" class="glyphicon icon-password" 
                    :class="{ 'glyphicon-eye-open': showPassword1, 'glyphicon-eye-close': !showPassword1 }"></span>
                </div>
                <div class="validate-error" v-if="passwordErr1">Password harus di isi</div>
                <div class="validate-error" v-if="passwordErr2">Password dan Konfirmasi Password harus sama</div>
              </div>
              <div class="form-group" v-if="stateModal == 'add'">
                <label for="confPassword">Konfirmasi Password</label>
                <div class="p-relative">
                  <input :type="passwordType2" class="form-control p-right-42px" @keyup="confPasswordKeyup" v-model="confPassword" placeholder="Masukan Konfirmasi Password">
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