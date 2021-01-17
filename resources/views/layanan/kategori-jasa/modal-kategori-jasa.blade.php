<div class="modal fade" id="modal-kategori-jasa">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title">@{{ titleModal }}</h4>
			</div>
			<div class="modal-body">
				<form>
					<div class="box-body">
						<div class="form-group">
							<label for="kategoriJasa">Nama Kategori Jasa</label>
							<input type="text" class="form-control" @keyup="kategoriJasaKeyup" v-model="kategoriJasa" placeholder="Masukan kategori jasa">
							<div class="validate-error" v-if="kategoriErr1">Kategori jasa harus di isi</div>
						</div>
						<div class="form-group">
							<div class="validate-error" v-if="beErr" v-html="msgBeErr"></div>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-primary" :disabled="validateSimpanKateJasa" 
					@click="submitKateJasa">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
