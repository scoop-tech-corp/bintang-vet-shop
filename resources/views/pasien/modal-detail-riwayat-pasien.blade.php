<div class="modal fade" id="modal-detail-riwayat-pasien" style="overflow: auto">
	<div class="modal-dialog modal-lg">
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
              <tbody id="detail-list-jasa"></tbody>
            </table>
            <div class="m-b-10px m-t-10px">
              <div class="label-detail-div">Barang</div>
            </div>
            <div class="table-responsive" style="margin-bottom: 15px">
              <table id="table-detail-barang" class="table table-striped text-nowrap">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Dibuat Oleh</th>
                    <th>Nama Barang</th>
                    <th>Kategori Barang</th>
                    <th>Satuan Barang</th>
                    <th>Jumlah</th>
                  </tr>
                </thead>
                <tbody id="detail-list-barang"></tbody>
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
                <tbody id="list-deskripsi-kondisi-pasien"></tbody>
              </table>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Status Pemeriksaan</div>
              <div id="statusPemeriksaanTxt" class="value-detail-div"></div>
            </div>
					</div>
				</form>
			</div>
			<div class="modal-footer"></div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
