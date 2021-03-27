<div class="modal fade" id="modal-harga-kelompok-obat">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form class="form-harga-kelompok-obat">
					<div class="box-body">

            <div class="form-group">
							<label for="cabang">Cabang</label>
							<select id="selectedCabang" class="form-control" style="width: 100%">
							</select>
							<div id="cabangErr1" class="validate-error"></div>
            </div>

						<div class="form-group">
							<label for="kelompokObat">Kelompok Obat</label>
              <select id="selectedKelompokObat" class="form-control" style="width: 100%">
							</select>
							<div id="kelompokObatErr1" class="validate-error"></div>
            </div>

            <div class="form-group">
							<label for="hargaJual">Harga Jual</label>
							<input id="hargaJual" type="text" class="form-control" min="0" placeholder="Masukan Harga Jual">
							<div id="hargaJualErr1" class="validate-error"></div>
						</div>

            <div class="form-group">
							<label for="hargaModal">Harga Modal</label>
							<input id="hargaModal" type="text" class="form-control" min="0" placeholder="Masukan Harga Modal">
							<div id="hargaModalErr1" class="validate-error"></div>
						</div>

            <div class="form-group">
							<label for="feeDokter">Fee Dokter</label>
							<input id="feeDokter" type="text" class="form-control" min="0" placeholder="Masukan Fee Dokter">
							<div id="feeDokterErr1" class="validate-error"></div>
						</div>

            <div class="form-group">
							<label for="feePetshop">Fee Petshop</label>
							<input id="feePetshop" type="text" class="form-control" min="0" placeholder="Masukan Fee Petshop">
							<div id="feePetshopErr1" class="validate-error"></div>
						</div>
            <div class="form-group">
							<div id="customErr1" class="validate-error"></div>
						</div>
						<div class="form-group">
							<div id="beErr" class="validate-error"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" id="btnSubmitHargaKelompokObat">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
