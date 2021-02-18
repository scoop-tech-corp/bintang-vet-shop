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
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Nomor Registrasi</div>
              <div id="nomorRegistrasiDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Nomor Pasien</div>
              <div id="nomorPasienDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Jenis Hewan</div>
              <div id="jenisHewanDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Nama Hewan</div>
              <div id="namaHewanDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Jenis Kelamin</div>
              <div id="jenisKelaminDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Usia Hewan</div>
              <div>
                <span id="usiaHewanTahunDetailTxt"></span>&nbsp;&nbsp;
                <span id="usiaHewanBulanDetailTxt"></span>
              </div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Nama Pemilik</div>
              <div id="namaPemilikDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Alamat Pemilik</div>
              <div id="alamatPemilikDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Nomor HP Pemilik</div>
              <div id="nomorHpPemilikDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Keluhan</div>
              <div id="keluhanDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Nama Pendaftar</div>
              <div id="namaPendaftarDetailTxt"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Anamnesa</div>
              <div id="anamnesaDetailTxt" class="value-detail-div"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Sign</div>
              <div id="signDetailTxt" class="value-detail-div"></div>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Diagnosa</div>
              <div id="diagnosaDetailTxt" class="value-detail-div"></div>
            </div>
            <div class="m-b-10px">
              <div class="label-detail-div">Jasa</div>
            </div>
            <table id="table-detail-jasa" class="table table-striped text-nowrap" style="margin-bottom: 15px">
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
            <div class="m-b-10px">
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
                    <th>Harga Satuan</th>
                    <th>Harga Keseluruhan</th>
                  </tr>
                </thead>
                <tbody id="detail-list-barang"></tbody>
              </table>
            </div>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Rawat Inap</div>
              <div id="rawatInapDetailTxt" class="value-detail-div"></div>
            </div>
            <table id="table-list-inpatient" class="table table-striped text-nowrap" style="margin-bottom: 15px">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Dibuat Oleh</th>
                  <th>Description</th>
                </tr>
              </thead>
              <tbody id="detail-list-inpatient"></tbody>
            </table>
            <div class="d-flex m-b-10px">
              <div class="label-detail-div">Status Pemeriksaan</div>
              <div id="statusPemeriksaanDetailTxt" class="value-detail-div"></div>
            </div>
					</div>
				</form>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
