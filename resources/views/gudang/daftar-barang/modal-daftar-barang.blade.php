<div class="modal fade" id="modal-daftar-barang">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form class="form-daftar-barang">
					<div class="box-body">
						<div class="form-group">
							<label for="namaBarang">Nama Barang</label>
							<input id="namaBarang" type="text" class="form-control" placeholder="Masukan Nama Barang">
							<div id="namaBarangErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<label for="jumlahBarang">Jumlah Barang</label>
							<input id="jumlahBarang" type="number" class="form-control" placeholder="Masukan Jumlah Barang">
							<div id="jumlahBarangErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<label for="satuanBarang">Satuan Barang</label>
							<select id="selectedSatuanBarang" class="form-control" style="width: 100%">
							</select>
							<div id="satuanBarangErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<label for="kategoriBarang">Kategori Barang</label>
							<select id="selectedKategoriBarang" class="form-control" style="width: 100%">
							</select>
							<div id="kategoriBarangErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<label for="kategoriBarang">Cabang Barang</label>
							<select id="selectedCabang" class="form-control" style="width: 100%">
							</select>
							<div id="cabangErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<div id="beErr" class="validate-error"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btnSubmitDaftarBarang">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
