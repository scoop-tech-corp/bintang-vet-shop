<div class="modal fade" id="modal-rawat-inap">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form class="form-rawat-inap">
					<div class="box-body">
            <div class="form-group">
							<label for="selectedPasien">Cari Pasien</label>
							<select id="selectedPasien" class="form-control" style="width: 100%">
							</select>
							<div id="pasienErr1" class="validate-error"></div>
						</div>
            <table>
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
            </table>
            <div class="form-group">
							<label for="keluhan">Keluhan</label>
							<input id="keluhan" type="text" class="form-control" placeholder="Masukan Keluhan">
							<div id="keluhanErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<label for="pendaftar">Pendaftar</label>
							<input id="pendaftar" type="text" class="form-control" placeholder="Masukan Nama Pendaftar">
							<div id="pendaftarErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<label for="selectedDokter">Dokter yang menangani</label>
							<select id="selectedDokter" class="form-control" style="width: 100%">
							</select>
							<div id="dokterErr1" class="validate-error"></div>
						</div>

            <div class="form-group d-flex">
							<label for="estimasiRawatInap" class="p-top-10px">Estimasi waktu rawat inap</label>
							<div>
								<div class="d-flex form-horizontal-container-c">
									<input id="estimasiRawatInap" type="number" class="form-control m-l-10px" min="0">
									&nbsp;<span>Hari</span>
								</div>
								<div id="estimasiRawatInapErr1" class="validate-error m-l-10px"></div>
							</div>
						</div>

						<div class="form-group d-flex">
							<label for="realitaRawatInap" class="p-top-10px">Realita waktu rawat inap</label>
							<div>
								<div class="d-flex form-horizontal-container-c">
									<input id="realitaRawatInap" type="number" class="form-control m-l-20px" min="0" style="width: 38%;">
									&nbsp;<span>Hari</span>
								</div>
								<div id="realitaRawatInapErr1" class="validate-error m-l-20px"></div>
							</div>
						</div>

						<div class="form-group">
							<div id="beErr" class="validate-error"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btnSubmitRawatInap">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
