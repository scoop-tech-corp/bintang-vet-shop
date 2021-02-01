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
                <td id="nomorRegistrasiDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Nomor Pasien</td>
                <td id="nomorPasienDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Jenis Hewan</td>
                <td id="jenisHewanDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Nama Hewan</td>
                <td id="namaHewanDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Jenis Kelamin</td>
                <td id="jenisKelaminDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Usia Hewan</td>
								<td id="usiaHewanTahunDetailTxt" class="detail-value"></td>
								<td id="usiaHewanBulanDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Nama Pemilik</td>
                <td id="namaPemilikDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Alamat Pemilik</td>
                <td id="alamatPemilikDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Nomor HP Pemilik</td>
                <td id="nomorHpPemilikDetailTxt" class="detail-value"></td>
							</tr>
							<tr>
                <td class="detail-label">Keluhan</td>
                <td id="keluhanDetailTxt" class="detail-value"></td>
							</tr>
							<tr>
                <td class="detail-label">Nama Pendaftar</td>
                <td id="namaPendaftarDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Anamnesa</td>
                <td id="anamnesaDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Sign</td>
                <td id="signDetailTxt" class="detail-value"></td>
              </tr>
              <tr>
                <td class="detail-label">Diagnosa</td>
                <td id="diagnosaDetailTxt" class="detail-value"></td>
              </tr>
            </table>
            <table>
              <tr>
                <td class="detail-label">Jasa</td>
              </tr>
            </table>
            <table class="table table-striped text-nowrap table-detail-jasa" style="margin-bottom: 15px">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Dibuat Oleh</th>
                  <th>Jenis Layanan</th>
                  <th>Kategori Jasa</th>
                  <th>Harga</th>
                </tr>
              </thead>
              <tbody id="detail-list-jasa"></tbody>
            </table>
            <table>
              <tr>
                <td class="detail-label">Barang</td>
              </tr>
            </table>
            <div class="table-responsive" style="margin-bottom: 15px">
              <table class="table table-striped text-nowrap table-detail-barang">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Dibuat Oleh</th>
                    <th>Nama Barang</th>
                    <th>Kategori Barang</th>
                    <th>Satuan Barang</th>
                    <th>Jumlah</th>
                    <th>Harga Satuan</th>
                    <th>Harga Keseluruhan</th>
                  </tr>
                </thead>
                <tbody id="detail-list-barang"></tbody>
              </table>
            </div>
            <table>
              <tr>
                <td class="detail-label">Rawat Inap</td>
                <td id="rawatInapDetailTxt" class="detail-value"></td>
              </tr>
            </table>
            <table class="table table-striped text-nowrap table-list-inpatient" style="margin-bottom: 15px">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody id="detail-list-inpatient"></tbody>
            </table>
            <table>
              <tr>
                <td class="detail-label">Status Pemeriksaan</td>
                <td id="statusPemeriksaanDetailTxt" class="detail-value"></td>
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
