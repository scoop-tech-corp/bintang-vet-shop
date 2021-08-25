<div class="modal fade" id="modal-barang-cat-food">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<form class="form-barang-cat-food">
					<div class="box-body">

            <div class="form-group">
							<label for="cabang">Cabang</label>
							<select id="selectedCabang" class="form-control" style="width: 100%">
							</select>
							<div id="cabangErr1" class="validate-error"></div>
            </div>

            <div class="form-group">
							<label for="namaBarang">Nama Barang</label>
              <input id="namaBarang" type="text" class="form-control" placeholder="Masukan Nama Barang">
							<div id="namaBarangErr1" class="validate-error"></div>
            </div>

            <div class="form-group">
							<label for="jumlahBarang">Jumlah Barang</label>
              <input id="jumlahBarang" type="number" class="form-control" min="0" placeholder="Masukan Jumlah Barang">
							<div id="jumlahBarangErr1" class="validate-error"></div>
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
							<label for="keuntungan">Keuntungan</label>
							<div class="d-flex">Rp.&nbsp;<div id="label-keuntungan"></div></div>
							<div id="keuntunganErr1" class="validate-error"></div>
						</div>

            <div class="form-group">
              <label for="keuntungan">Upload Foto</label>
              <div id="section-upload-image">
                <div class="notes-upload-image">Format gambar .jpg .jpeg .png dan ukuran maksimal 5mb</div>
                <div class="box-image-upload" id="box-1">
                  <a class="img-preview-1"><img class="img-preview-1"></a>
                  <span class="icon-plus-upload" id="icon-plus-upload-1">+</span> 
                  <input type="file" class="input-file" id="upload-image-1" accept=".png, .jpg, .jpeg">
                  <div class="btn-icon btn-trash-upload-image" noUploadImage='1'><i class="fa fa-trash-o" aria-hidden="true"></i></div>
                </div>
              </div>
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
				<button type="button" class="btn btn-primary" id="btnSubmitBarangCatFood">Simpan</button>
			</div>
		</div>
		<!-- /.modal-content -->
	</div>
	<!-- /.modal-dialog -->
</div>
