<div class="modal fade" id="modal-pasien">
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
                  <td class="detail-label">No Registrasi</td>
                  <td class="detail-value">@{{nomorRegis}}</td>
                </tr>
              </table>
              <div class="form-group">
                <label for="animalType">Jenis Hewan</label>
                <input type="text" class="form-control" @keyup="animalTypeKeyup" v-model="animalType" placeholder="Masukan Jenis Hewan">
                <div class="validate-error" v-if="animalTypeErr1">Jenis hewan harus di isi</div>
              </div>
              <div class="form-group">
                <label for="namaLengkap">Nama Hewan</label>
                <input type="text" class="form-control" @keyup="animalNameKeyup" v-model="animalName" placeholder="Masukan Nama Hewan">
                <div class="validate-error" v-if="animalNameErr1">Nama Hewan harus di isi</div>
              </div>
              <div class="form-group">
                <label for="animalSex">Jenis Kelamin</label>
                <select class="form-control" @change="onSelectAnimalSex($event)" v-model="animalSex">
                  <option selected="selected" value="">Pilih jenis kelamin</option>
                  <option value="jantan">Jantan</option>
                  <option value="betina">Betina</option>
                  <option value="tidak diketahui">Tidak Diketahui</option>
                </select>
                <div class="validate-error" v-if="animalSexErr1">Jenis Kelamin Hewan harus di isi</div>
              </div>
              <div class="form-group">
                <label for="animalAge">Usia Hewan</label>
                <div class="section-age-animal">
                	<input type="number" class="form-control" @keyup="animalAgeKeyup" v-model="animalYear" placeholder="Masukan tahun hewan">
                	<input type="number" class="form-control" @keyup="animalAgeKeyup" v-model="animalMonth" placeholder="Masukan bulan hewan">
                </div>
                <div class="validate-error" v-if="animalAgeErr1">Usia Hewan harus di isi</div>
              </div>
              <div class="form-group">
                <label for="ownerName">Nama Pemilik</label>
                <input type="text" class="form-control" @keyup="ownerNameKeyup" v-model="ownerName" placeholder="Masukan Nama Pemilik">
                <div class="validate-error" v-if="ownerNameErr1">Nama Pemilik harus di isi</div>
							</div>
							<div class="form-group">
                <label for="ownerName">Alamat Pemilik</label>
                <input type="text" class="form-control" @keyup="ownerAddressKeyup" v-model="ownerAddress" placeholder="Masukan Alamat Pemilik">
                <div class="validate-error" v-if="ownerAddressErr1">Alamat Pemilik harus di isi</div>
							</div>
							<div class="form-group">
                <label for="ownerTelp">Nomor HP Pemilik</label>
                <input type="number" class="form-control" @keyup="ownerTelpKeyup" v-model="ownerTelp" placeholder="Masukan Nomor HP Pemilik">
                <div class="validate-error" v-if="ownerTelpErr1">Alamat Pemilik harus di isi</div>
              </div>
              <div class="form-group">
                <div class="validate-error" v-if="beErr" v-html="msgBeErr"></div>
              </div>
            </div>
          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary" :disabled="validateSimpanPasien" @click="submitPasien">Simpan</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
  </div>