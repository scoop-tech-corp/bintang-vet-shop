<div class="modal fade" id="modal-daftar-jasa">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form class="form-daftar-jasa">
					<div class="box-body">
						<div class="form-group">
							<label for="namaJasa">Jenis Pelayanan</label>
							<input id="namaJasa" type="text" class="form-control" placeholder="Masukan Nama Jasa">
							<div id="namaJasaErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<label for="kategoriJasa">Kategori Jasa</label>
							<select id="selectedKategoriJasa" class="form-control" style="width: 100%">
							</select>
							<div id="kategoriJasaErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<label for="cabang">Cabang</label>
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
				<button type="button" class="btn btn-primary" id="btnSubmitDaftarJasa">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
