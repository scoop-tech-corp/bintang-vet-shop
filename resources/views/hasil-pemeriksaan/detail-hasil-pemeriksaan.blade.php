<div class="modal fade" id="detail-hasil-pemeriksaan">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form class="detail-penerimaan-pasien">
					<div class="box-body">
            <table>
							<tr>
                <td class="detail-label">Nomor Registrasi</td>
                <td id="nomorRegistrasiTxt" class="detail-value"></td>
              </tr>
              <tr>
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
              <tr>
                <td class="detail-label">Anamnesa</td>
                <td id="anamnesaTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Sign</td>
                <td id="signTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Diagnosa</td>
                <td id="diagnosaTxt" class="detail-value"></td>
              </tr>
            </table>
            <table>
              <tr>
                <td class="detail-label">Rawat Inap</td>
                <td id="rawatInapTxt" class="detail-value"></td>
              </tr>
            </table>

            <table class="table table-striped text-nowrap detail-list-jasa-rawat-inap">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Jenis Layanan</th>
                  <th>Kategori Jasa</th>
                  <th>Harga</th>
                </tr>
              </thead>
              <tbody id="detail-selected-jasa-rawat-inap"></tbody>
            </table>
            <br>
            <div class="table-responsive">
              <table class="table table-striped text-nowrap detail-list-barang-rawat-inap">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Nama Barang</th>
                    <th>Kategori Barang</th>
                    <th>Satuan Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Harga Keseluruhan</th>
                  </tr>
                </thead>
                <tbody id="detail-selected-barang-rawat-inap"></tbody>
              </table>
            </div>
            <br>
            <table>
              <tr>
                <td class="detail-label">Status Pemeriksaan</td>
                <td id="statusPemeriksaanTxt" class="detail-value"></td>
              </tr>
            </table>
					</div>
				</form>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
