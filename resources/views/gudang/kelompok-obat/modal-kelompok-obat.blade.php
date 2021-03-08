<div class="modal fade" id="modal-kelompok-obat">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form class="form-kelompok-obat">
					<div class="box-body">
						<div class="form-group">
							<label for="namaKelompok">Nama Kelompok</label>
							<input id="namaKelompok" type="text" class="form-control" placeholder="Masukan Nama Kelompok">
							<div id="namaKelompokErr1" class="validate-error"></div>
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
				<button type="button" class="btn btn-primary" id="btnSubmitKelompokObat">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
