<div class="modal fade" id="modal-hasil-pemeriksaan">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form class="form-hasil-pemeriksaan">
					<div class="box-body">
            <div class="form-group form-cari-pasien">
							<label for="selectedPasien">Cari Pasien</label>
							<select id="selectedPasien" class="form-control" style="width: 100%">
							</select>
							<div id="pasienErr1" class="validate-error"></div>
						</div>
            <table style="width: 100%">
              <tr>
                <td class="detail-label">Nomor Registrasi</td>
                <td id="nomorRegistrasiTxt" class="detail-value"></td>
                <td class="detail-label">Nomor Pasien</td>
                <td id="nomorPasienTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Jenis Hewan</td>
                <td id="jenisHewanTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Nama Hewan</td>
                <td id="namaHewanTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Jenis Kelamin</td>
                <td id="jenisKelaminTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Usia Hewan</td>
								<td id="usiaHewanTahunTxt" class="detail-value"></td>
                <td id="usiaHewanBulanTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Nama Pemilik</td>
                <td id="namaPemilikTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Alamat Pemilik</td>
                <td id="alamatPemilikTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Nomor HP Pemilik</td>
                <td id="nomorHpPemilikTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Keluhan</td>
                <td id="keluhanTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Nama Pendaftar</td>
                <td id="namaPendaftarTxt" class="detail-value"></td>
              </tr>
            </table>
            <div class="form-group">
							<label for="anamnesa">Anamnesa</label>
              <textarea id="anamnesa" class="form-control" placeholder="Masukan Anamnesa"></textarea>
							<div id="anamnesaErr1" class="validate-error"></div>
            </div>
            <div class="form-group">
							<label for="sign">Sign</label>
              <textarea id="sign" class="form-control" placeholder="Masukan Sign"></textarea>
							<div id="signErr1" class="validate-error"></div>
						</div>
            <div class="form-group">
							<label for="diagnosa">Diagnosa</label>
              <textarea id="diagnosa" class="form-control" placeholder="Masukan Diagnosa"></textarea>
							<div id="diagnosaErr1" class="validate-error"></div>
            </div>
            
            <div class="form-group"> {{-- Jasa Rawat Jalan --}}
              <label for="jasa">Jasa</label>
              <select id="selectedJasa" class="form-control" style="width: 100%" multiple="multiple"></select>
              <div class="table-responsive">
                <table class="table table-striped text-nowrap table-list-jasa">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th class="tgl-edit">Tanggal</th>
                      <th class="dibuat-edit">Dibuat Oleh</th>
                      <th>Jenis Layanan</th>
                      <th>Kategori Jasa</th>
                      <th>Jumlah</th>
                      <th>Harga Satuan</th>
                      <th>Harga Keseluruhan</th>
                      <th>Hapus</th>
                    </tr>
                  </thead>
                  <tbody id="list-selected-jasa"></tbody>
                </table>
              </div>
            </div>

            <div class="form-group">{{-- Barang Rawat Jalan --}}
              <label for="barang">Barang</label>
              <select id="selectedBarang" class="form-control" style="width: 100%" multiple="multiple"></select>
              <div class="table-responsive">
                <table class="table table-striped text-nowrap table-list-barang">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th class="tgl-edit">Tanggal</th>
                      <th class="dibuat-edit">Dibuat Oleh</th>
                      <th>Nama Barang</th>
                      <th>Kategori Barang</th>
                      <th>Satuan Barang</th>
                      <th>Jumlah</th>
                      <th>Harga Satuan</th>
                      <th>Harga Keseluruhan</th>
                      <th>Hapus</th>
                    </tr>
                  </thead>
                  <tbody id="list-selected-barang"></tbody>
                </table>
              </div>
            </div>

            <div class="form-group">
              <label for="barang" style="margin-right: 93px;">Rawat Inap</label>
              <span style="padding-right: 51px;"><input type="radio" name="radioRawatInap" value=1 /> Ya</span>
              <span><input type="radio" name="radioRawatInap" value=0 /> Tidak</span>
              <div id="rawatInapErr1" class="validate-error"></div>
            </div>

            <div class="form-group form-deskripsi-kondisi-pasien">
              <label for="deskripsiKondisiPasien">Deskripsi Kondisi Pasien</label>
              <textarea id="descriptionCondPasien" class="form-control" placeholder="Masukan Deskripsi Kondisi Pasien"></textarea>
							<div id="descriptionCondPasienErr1" class="validate-error"></div>
            </div>
            <table class="table table-striped text-nowrap table-deskripsi-kondisi-pasien" style="margin-bottom: 15px">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Dibuat Oleh</th>
                  <th>Deskripsi</th>
                </tr>
              </thead>
              <tbody id="list-deskripsi-kondisi-pasien"></tbody>
            </table>
            <div class="form-group">
              <label for="barang" style="margin-right: 40px;">Status Pemeriksaan</label>
              <span style="padding-right: 23px;"><input type="radio" name="radioStatusPemeriksa" value=1 /> Selesai</span>
              <span><input type="radio" name="radioStatusPemeriksa" value=0 /> Belum</span>
              <div id="statusPemeriksaErr1" class="validate-error"></div>
            </div>

						<div class="form-group">
							<div id="beErr" class="validate-error"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btnSubmitHasilPemeriksaan">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
