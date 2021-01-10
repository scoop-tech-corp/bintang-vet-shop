$(document).ready(function() {
	let optSatuanBarang = '';
	let optKategoriBarang = '';
	let optCabang = '';

	let getId = null;
	let modalState = '';
	let isValidNamaBarang = false;
	let isValidJumlahBarang = false;
	let isValidSelectedSatuanBarang = false;
	let isValidSelectedKategori = false;
	let isValidSelectedCabang = false;
	let isBeErr = false;
	let paramUrlSetup = {
		orderby:'',
		column: '',
		keyword: ''
	};

	// load daftar barang
	loadDaftarBarang();

	// load satuan barang
	loadSatuanBarang();

	// load kategori barang
	loadKategoriBarang();

	// load cabang
	loadCabang();

	$('.input-search-section .fa').click(function() {
		onSearch($('.input-search-section input').val());
	});

	$('.input-search-section input').keypress(function(e) {
		if (e.which == 13) { onSearch($(this).val()); }
	});
	
	$('.onOrdering').click(function() {
		const column = $(this).attr('data');
		const orderBy = $(this).attr('orderby');
		$('.onOrdering[data="'+column+'"]').children().remove();

		if (orderBy == 'none' || orderBy == 'asc') {
			$(this).attr('orderby', 'desc');
			$(this).append('<span class="fa fa-sort-desc"></span>');

		} else if(orderBy == 'desc') {
			$(this).attr('orderby', 'asc');
			$(this).append('<span class="fa fa-sort-asc"></span>');
		}

		paramUrlSetup.orderby = $(this).attr('orderby');
		paramUrlSetup.column = column;

		loadDaftarBarang();
	});
	
	$('.openFormAdd').click(function() {
		modalState = 'add';
		$('.modal-title').text('Tambah Daftar Barang');
		refreshForm();
		formConfigure();
	});

	$('.openFormUpload').click(function() {
		$('#modal-upload-daftar-barang .modal-title').text('Upload Barang Sekaligus');
		$('#modal-upload-daftar-barang').modal('show');
	});	

	$('#btnSubmitDaftarBarang').click(function() {

		if (modalState == 'add') {

			const fd = new FormData();
			fd.append('nama_barang', $('#namaBarang').val());
			fd.append('jumlah_barang', $('#jumlahBarang').val());
			fd.append('satuan_barang', $('#selectedSatuanBarang').val());
			fd.append('kategori_barang', $('#selectedKategoriBarang').val());
			fd.append('cabang', $('#selectedCabang').val());

			$.ajax({
				url : $('.baseUrl').val() + '/api/daftar-barang',
				type: 'POST',
				dataType: 'JSON',
				headers: { 'Authorization': `Bearer ${token}` },
				data: fd, contentType: false, cache: false,
				processData: false,
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(resp) {

					$("#msg-box .modal-body").text('Berhasil Menambah Daftar Barang');
					$('#msg-box').modal('show');

					setTimeout(() => {
						$('#modal-daftar-barang').modal('toggle');
						refreshForm();
						loadDaftarBarang();
					}, 1000);
				}, complete: function() { $('#loading-screen').hide(); }
				, error: function(err) {
					if (err.status === 422) {
						let errText = ''; $('#beErr').empty(); $('#btnSubmitDaftarBarang').attr('disabled', true);
						$.each(err.responseJSON.errors, function(idx, v) {
							errText += v + ((idx !== err.responseJSON.errors.length - 1) ? '<br/>' : '');
						});
						$('#beErr').append(errText); isBeErr = true;
					} else if (err.status == 401) {
						localStorage.removeItem('vet-clinic');
						location.href = $('.baseUrl').val() + '/masuk';
					}
				}
			});

		} else {
			// edit
			$('#modal-confirmation .box-body').text('Anda yakin untuk mengubah daftar barang ?');
			$('#modal-confirmation').modal('show');
		}
	});

	$('#submitConfirm').click(function() {
		if (modalState == 'edit') {
			// process edit
			const datas = {
				id: getId,
				nama_barang: $('#namaBarang').val(),
				jumlah_barang: $('#jumlahBarang').val(),
				satuan_barang: 	$('#selectedSatuanBarang').val(),
				kategori_barang: $('#selectedKategoriBarang').val(),
				cabang: $('#selectedCabang').val()
			};

			$.ajax({
				url : $('.baseUrl').val() + '/api/daftar-barang',
				type: 'PUT',
				dataType: 'JSON',
				headers: { 'Authorization': `Bearer ${token}` },
				data: datas,
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(data) {
					$('#modal-confirmation').modal('toggle');

					$("#msg-box .modal-body").text('Berhasil Mengubah Daftar Barang');
					$('#msg-box').modal('show');

					setTimeout(() => {
						$('#modal-daftar-barang').modal('toggle');
						refreshForm();
						loadDaftarBarang();
					}, 1000);

				}, complete: function() { $('#loading-screen').hide(); }
				, error: function(err) {
					if (err.status == 401) {
						localStorage.removeItem('vet-clinic');
						location.href = $('.baseUrl').val() + '/masuk';
					}
				}
			});
		} else {
			// process delete
			$.ajax({
				url     : $('.baseUrl').val() + '/api/daftar-barang',
				headers : { 'Authorization': `Bearer ${token}` },
				type    : 'DELETE',
				data	  : { id: getId },
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(data) {
					$('#modal-confirmation').modal('toggle');

					$("#msg-box .modal-body").text('Berhasil menghapus daftar barang');
					$('#msg-box').modal('show');
					loadDaftarBarang();

				}, complete: function() { $('#loading-screen').hide(); }
				, error: function(err) {
					if (err.status == 401) {
						localStorage.removeItem('vet-clinic');
						location.href = $('.baseUrl').val() + '/masuk';
					}
				}
			});
		}
	});

	function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadDaftarBarang();
	}

	function loadDaftarBarang() {
		getId = null;
		modalState = '';

		$.ajax({
			url     : $('.baseUrl').val() + '/api/daftar-barang',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword},
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listDaftarBarang = '';
				$('#list-daftar-barang tr').remove();

				$.each(data, function(idx, v) {
					listDaftarBarang += `<tr>`
						+ `<td>${++idx}</td>`
						+ `<td>${v.item_name}</td>`
						+ `<td>${v.total_item}</td>`
						+ `<td>${v.unit_name}</td>`
						+ `<td>${v.category_name}</td>`
						+ `<td>${v.branch_name}</td>`
						+ `<td>${v.created_by}</td>`
						+ `<td>${v.created_at}</td>`
						+ `<td>
								<button type="button" class="btn btn-warning openFormEdit" value=${v.id}><i class="fa fa-pencil" aria-hidden="true"></i></button>
								<button type="button" class="btn btn-danger openFormDelete" value=${v.id}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
							</td>`
						+ `</tr>`;
				});
				$('#list-daftar-barang').append(listDaftarBarang);

				$('.openFormEdit').click(function() {
					const getObj = data.find(x => x.id == $(this).val());
					modalState = 'edit';
					refreshForm();
					$('.modal-title').text('Edit Daftar Barang');

					formConfigure();
					getId = getObj.id;
					$('#namaBarang').val(getObj.item_name);
					$('#jumlahBarang').val(getObj.total_item);
					$('#selectedSatuanBarang').val(getObj.unit_goods_id); $('#selectedSatuanBarang').trigger('change');
					$('#selectedKategoriBarang').val(getObj.category_goods_id); $('#selectedKategoriBarang').trigger('change');
					$('#selectedCabang').val(getObj.branch_id); $('#selectedCabang').trigger('change');
				});
			
				$('.openFormDelete').click(function() {
					getId = $(this).val();
					modalState = 'delete';
					$('#modal-confirmation .box-body').text('Anda yakin ingin menghapus Daftar Barang ini?');
					$('#modal-confirmation').modal('show');
				});

			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
	}

	function formConfigure() {
		$('#selectedSatuanBarang').select2();
		$('#selectedKategoriBarang').select2();
		$('#selectedCabang').select2();
		$('#modal-daftar-barang').modal('show');
		$('#btnSubmitDaftarBarang').attr('disabled', true);
		
		$('#namaBarang').keyup(function () { validationForm(); });
		$('#jumlahBarang').keyup(function () { validationForm(); });
		$('#selectedSatuanBarang').change(function() { validationForm(); });
		$('#hargaSatuanBarang').keyup(function () { validationForm(); });
		$('#selectedKategoriBarang').change(function () { validationForm(); });
		$('#selectedCabang').change(function () { validationForm(); });
	}

	function refreshForm() {
		$('#namaBarang').val(null);
		$('#jumlahBarang').val(null);
		$('#selectedSatuanBarang').val(null);
		$('#selectedKategoriBarang').val(null);
		$('#selectedCabang').val(null);
		$('#beErr').empty(); isBeErr = false;
	}

	function validationForm() {
		if (!$('#namaBarang').val()) {
			$('#namaBarangErr1').text('Nama barang harus di isi'); isValidNamaBarang = false;
		} else { 
			$('#namaBarangErr1').text(''); isValidNamaBarang = true;
		}

		if (!$('#jumlahBarang').val()) {
			$('#jumlahBarangErr1').text('Jumlah barang harus di isi'); isValidJumlahBarang = false;
		} else { 
			$('#jumlahBarangErr1').text(''); isValidJumlahBarang = true;
		}

		if (!$('#selectedSatuanBarang').val()) {
			$('#satuanBarangErr1').text('Satuan barang harus di isi'); isValidSelectedSatuanBarang = false;
		} else {
			$('#satuanBarangErr1').text(''); isValidSelectedSatuanBarang = true;
		}

		if (!$('#selectedKategoriBarang').val()) {
			$('#kategoriBarangErr1').text('Kategori barang harus di isi'); isValidSelectedKategori = false;
		} else {
			$('#kategoriBarangErr1').text(''); isValidSelectedKategori = true;
		}

		if (!$('#selectedCabang').val()) {
			$('#cabangErr1').text('Cabang harus di isi'); isValidSelectedCabang = false;
		} else {
			$('#cabangErr1').text(''); isValidSelectedCabang = true;
		}

		$('#beErr').empty(); isBeErr = false;

		if (!isValidNamaBarang || !isValidJumlahBarang || !isValidSelectedSatuanBarang || !isValidSelectedKategori 
			|| !isValidSelectedCabang || isBeErr) {
			$('#btnSubmitDaftarBarang').attr('disabled', true);
		} else {
			$('#btnSubmitDaftarBarang').attr('disabled', false);
		}
	}

	function loadSatuanBarang() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/satuan-barang',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				optSatuanBarang += `<option value=''>Pilih Satuan Barang</option>`
	
				if (data.length) {
					for (var i = 0 ; i < data.length ; i++){
						optSatuanBarang += `<option value=${data[i].id}>${data[i].unit_name}</option>`;
					}
				}
				$('#selectedSatuanBarang').append(optSatuanBarang);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
	}

	function loadKategoriBarang() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/kategori-barang',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				optKategoriBarang += `<option value=''>Pilih Kategori Barang</option>`
	
				if (data.length) {
					for (let i = 0 ; i < data.length ; i++) {
						optKategoriBarang += `<option value=${data[i].id}>${data[i].category_name}</option>`;
					}
				}
				$('#selectedKategoriBarang').append(optKategoriBarang);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
	}

	function loadCabang() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/cabang',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				optCabang += `<option value=''>Pilih Cabang</option>`
	
				if (data.length) {
					for (let i = 0 ; i < data.length ; i++) {
						optCabang += `<option value=${data[i].id}>${data[i].branch_name}</option>`;
					}
				}
				$('#selectedCabang').append(optCabang);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
	}

});