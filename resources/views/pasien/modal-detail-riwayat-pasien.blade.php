<div class="modal fade" id="modal-detail-riwayat-pasien" style="overflow: auto">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close btn-kembali-modal-riwayat-pemeriksaan" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
        <div class="nav-tabs-custom m-t-25px">
          <ul class="nav nav-tabs">
            <li class="active"><a href="#general" data-toggle="tab">Utama</a></li>
            <li><a href="#kelompok_obat" data-toggle="tab">Obat</a></li>
          </ul>
          <div id="tab-content" class="tab-content">
            <div class="tab-pane fade in active" id="general">
              <div class="row">
                <form class="form-hasil-pemeriksaan">
                  <div class="box-body">
                    <div class="d-flex m-b-10px">
                      <div class="label-detail-div">Nomor Registrasi</div>
                      <div id="nomorRegistrasiTxt"></div>
                    </div>
                    <div class="d-flex m-b-10px">
                      <div class="label-detail-div">Anamnesa</div>
                      <div id="anamnesaTxt" class="value-detail-div"></div>
                    </div>
                    <div class="d-flex m-b-10px">
                      <div class="label-detail-div">Sign</div>
                      <div id="signTxt" class="value-detail-div"></div>
                    </div>
                    <div class="d-flex m-b-10px">
                      <div class="label-detail-div">Diagnosa</div>
                      <div id="diagnosaTxt" class="value-detail-div"></div>
                    </div>
                    <div class="m-b-10px">
                      <div class="label-detail-div">Jasa</div>
                    </div>
                    <div class="table-responsive" style="margin-bottom: 15px">
                      <table id="table-detail-jasa" class="table table-striped text-nowrap">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Dibuat Oleh</th>
                            <th>Jenis Layanan</th>
                            <th>Kategori Jasa</th>
                            <th>Jumlah</th>
                          </tr>
                        </thead>
                        <tbody id="detail-list-jasa">
                          <tr class="text-center"><td colspan="6">Tidak ada data.</td></tr>
                        </tbody>
                      </table>
                    </div>

                    <div class="d-flex m-b-10px">
                      <div class="label-detail-div">Rawat Inap</div>
                      <div id="rawatInapTxt" class="value-detail-div"></div>
                    </div>
      
                    <div class="form-group">
                      <label for="deskripsiKondisiPasien">Deskripsi Kondisi Pasien</label>
                    </div>
                    <div class="table-responsive form-group">
                      <table class="table table-striped">
                        <thead>
                          <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Dibuat Oleh</th>
                            <th>Deskripsi</th>
                          </tr>
                        </thead>
                        <tbody id="list-deskripsi-kondisi-pasien">
                          <tr class="text-center"><td colspan="4">Tidak ada data.</td></tr>
                        </tbody>
                      </table>
                    </div>
                    <div class="d-flex m-b-10px">
                      <div class="label-detail-div">Status Pemeriksaan</div>
                      <div id="statusPemeriksaanTxt" class="value-detail-div"></div>
                    </div>
                  </div>
                </form>
              </div>
            </div>
            <div class="tab-pane fade" id="kelompok_obat">
              <div class="row">
                <div class="col-md-12 m-b-10px" id="locateDrawKelompokBarang">
                  <div class="target">
                    Tidak ada kelompok obat.
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
			</div>
			<div class="modal-footer">
        <button data-dismiss="modal" aria-label="Close" type="button"
          class="btn btn-default pull-right btn-kembali-modal-riwayat-pemeriksaan">Kembali</button>
      </div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
