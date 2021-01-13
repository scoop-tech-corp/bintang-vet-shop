$(document).ready(function() {
  let optPasien = '';
	let optDokter = '';
	let listPasien = [];

	let getId = null;
	let modalState = '';
	let isValidKeluhan = false;
	let isValidPendaftar = false;
	let isValidSelectedPasien = false;
  let isValidSelectedDokter = false;
  let isValidEstimasi = false;
  let isValidRealita = false;
  let isBeErr = false;
  let paramUrlSetup = {
		orderby:'',
		column: '',
		keyword: ''
	};

  loadRawatInap();

  loadPasien();

  loadDokter();

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

		loadRawatInap();
  });
  
  $('.openFormAdd').click(function() {
		modalState = 'add';
		$('.modal-title').text('Tambah Rawat Inap');
		refreshText(); refreshForm();
		formConfigure();
	});

	$('#btnSubmitRawatInap').click(function() {
    if (modalState == 'add') {

			const fd = new FormData();
			fd.append('patient_id', $('#selectedPasien').val());
			fd.append('doctor_user_id', $('#selectedDokter').val());
      fd.append('keluhan', $('#keluhan').val());
      fd.append('nama_pendaftar', $('#pendaftar').val());
      fd.append('estimasi_waktu', $('#estimasiRawatInap').val());
      fd.append('realita_waktu', $('#realitaRawatInap').val());

			$.ajax({
				url : $('.baseUrl').val() + '/api/rawat-inap',
				type: 'POST',
				dataType: 'JSON',
				headers: { 'Authorization': `Bearer ${token}` },
				data: fd, contentType: false, cache: false,
				processData: false,
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(resp) {

					$("#msg-box .modal-body").text('Berhasil Menambah Rawat Inap');
					$('#msg-box').modal('show');

					setTimeout(() => {
						$('#modal-rawat-inap').modal('toggle');
						refreshForm(); loadRawatInap();
					}, 1000);
				}, complete: function() { $('#loading-screen').hide(); }
				, error: function(err) {
					if (err.status === 422) {
						let errText = ''; $('#beErr').empty(); $('#btnSubmitRawatInap').attr('disabled', true);
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
			$('#modal-confirmation .modal-title').text('Peringatan');
			$('#modal-confirmation .box-body').text('Anda yakin untuk mengubah data ini ?');
			$('#modal-confirmation').modal('show');
    }
	});
	
	$('#submitConfirm').click(function() {
    if (modalState == 'edit') {
			// process edit
			const datas = {
				id: getId,
				patient_id: $('#selectedPasien').val(),
				doctor_user_id: $('#selectedDokter').val(),
				keluhan: $('#keluhan').val(),
        nama_pendaftar: $('#pendaftar').val(),
        estimasi_waktu: $('#estimasiRawatInap').val(),
        realita_waktu: $('#realitaRawatInap').val()
			};

			$.ajax({
				url : $('.baseUrl').val() + '/api/rawat-inap',
				type: 'PUT',
				dataType: 'JSON',
				headers: { 'Authorization': `Bearer ${token}` },
				data: datas,
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(data) {
					$('#modal-confirmation .modal-title').text('Peringatan');
					$('#modal-confirmation').modal('toggle');

					$("#msg-box .modal-body").text('Berhasil Mengubah rawat inap');
					$('#msg-box').modal('show');

					setTimeout(() => {
						$('#modal-rawat-inap').modal('toggle');
						refreshForm();
						loadRawatInap();
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
				url     : $('.baseUrl').val() + '/api/rawat-inap',
				headers : { 'Authorization': `Bearer ${token}` },
				type    : 'DELETE',
				data	  : { id: getId },
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(data) {
					$('#modal-confirmation .modal-title').text('Peringatan');
					$('#modal-confirmation').modal('toggle');

					$("#msg-box .modal-body").text('Berhasil menghapus data');
					$('#msg-box').modal('show');

					loadRawatInap();

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

	$('#selectedPasien').on('select2:select', function (e) {
		const getObj = listPasien.find(x => x.id == $(this).val());
		if (getObj) {
			$('#namaHewanTxt').text(getObj.pet_name); $('#jenisKelaminTxt').text(getObj.pet_gender);
			$('#usiaHewanTahunTxt').text(`${getObj.pet_year_age} Tahun`); $('#usiaHewanBulanTxt').text(`${getObj.pet_month_age} Bulan`);
			$('#namaPemilikTxt').text(getObj.owner_name); $('#alamatPemilikTxt').text(getObj.owner_address);
			$('#nomorHpPemilikTxt').text(getObj.owner_phone_number);
		} else { refreshText(); }

		validationForm();
	});

	$('#keluhan').keyup(function () { validationForm(); });
	$('#pendaftar').keyup(function () { validationForm(); });
	$('#selectedDokter').on('select2:select', function (e) { validationForm(); });
	$('#estimasiRawatInap').keyup(function () { validationForm(); });
	$('#realitaRawatInap').keyup(function () { validationForm(); });	

	function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadRawatInap();
	}

  function loadRawatInap() {
    getId = null;
		modalState = '';
		$.ajax({
			url     : $('.baseUrl').val() + '/api/rawat-inap',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword},
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listRawatInap = '';
				$('#list-rawat-inap tr').remove();

				$.each(data, function(idx, v) {
					listRawatInap += `<tr>`
						+ `<td>${++idx}</td>`
						+ `<td>${v.id_number}</td>`
						+ `<td>${v.id_number_patient}</td>`
            + `<td>${v.pet_name}</td>`
            + `<td>${v.complaint}</td>`
            + `<td>${v.registrant}</td>`
            + `<td>${v.username_doctor}</td>`            
						+ `<td>${v.created_by}</td>`
						+ `<td>${v.created_at}</td>`
						+ `<td>
								<button type="button" class="btn btn-warning openFormEdit" value=${v.id}><i class="fa fa-pencil" aria-hidden="true"></i></button>
								<button type="button" class="btn btn-danger openFormDelete" value=${v.id}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
							</td>`
						+ `</tr>`;
				});
				$('#list-rawat-inap').append(listRawatInap);

				$('.openFormEdit').click(function() {
					const getObj = data.find(x => x.id == $(this).val());
					modalState = 'edit';

					$('.modal-title').text('Edit Rawat Inap');
					refreshForm();

					formConfigure();
					getId = getObj.id;
					$('#pendaftar').val(getObj.registrant); $('#keluhan').val(getObj.complaint);
					$('#estimasiRawatInap').val(getObj.estimate_day); $('#realitaRawatInap').val(getObj.reality_day);
					$('#selectedPasien').val(getObj.patient_id); $('#selectedPasien').trigger('change');
					$('#selectedDokter').val(getObj.user_doctor_id); $('#selectedDokter').trigger('change');
					$('#namaHewanTxt').text(getObj.pet_name); $('#jenisKelaminTxt').text(getObj.pet_gender);
					$('#usiaHewanTahunTxt').text(`${getObj.pet_year_age} Tahun`); $('#usiaHewanBulanTxt').text(`${getObj.pet_month_age} Bulan`);
					$('#namaPemilikTxt').text(getObj.owner_name); $('#alamatPemilikTxt').text(getObj.owner_address);
					$('#nomorHpPemilikTxt').text(getObj.owner_phone_number);
				});
			
				$('.openFormDelete').click(function() {
					getId = $(this).val();
					modalState = 'delete';

					$('#modal-confirmation .modal-title').text('Peringatan');
					$('#modal-confirmation .box-body').text('Anda yakin ingin menghapus data ini?');
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

  function loadPasien() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/pasien',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				optPasien += `<option value=''>Pilih Pasien</option>`
				listPasien = data;
				if (listPasien.length) {
					for (let i = 0 ; i < listPasien.length ; i++) {
						optPasien += `<option value=${data[i].id}>${data[i].pet_name} - ${data[i].branch_name}</option>`;
					}
				}
				$('#selectedPasien').append(optPasien);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
  }

  function loadDokter() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/user/dokter',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				optDokter += `<option value=''>Pilih Dokter</option>`
	
				if (data.length) {
					for (let i = 0 ; i < data.length ; i++) {
						optDokter += `<option value=${data[i].id}>${data[i].username} - ${data[i].branch_name}</option>`;
					}
				}
				$('#selectedDokter').append(optDokter);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
  }

  function refreshForm() {
    $('#selectedPasien').val(null); $('#keluhan').val(null);
		$('#pendaftar').val(null); $('#selectedDokter').val(null);
		$('#estimasiRawatInap').val(null); $('#realitaRawatInap').val(null);
    $('#pasienErr1').text(''); isValidSelectedPasien = true;
    $('#keluhanErr1').text(''); isValidKeluhan = true;
    $('#pendaftarErr1').text(''); isValidPendaftar = true;
    $('#dokterErr1').text(''); isValidSelectedDokter = true;
    $('#estimasiRawatInapErr1').text(''); isValidEstimasi = true;
    $('#realitaRawatInapErr1').text(''); isValidRealita = true;
    $('#beErr').empty(); isBeErr = false;
	}

	function refreshText() {
		$('#namaHewanTxt').text('-'); $('#jenisKelaminTxt').text('-');
		$('#usiaHewanTahunTxt').text('- Tahun'); $('#usiaHewanBulanTxt').text('- Bulan');
		$('#namaPemilikTxt').text('-'); $('#alamatPemilikTxt').text('-');
		$('#nomorHpPemilikTxt').text('-');
	}
	
	function validationForm() {
		if (!$('#selectedPasien').val()) {
			$('#pasienErr1').text('Pasien harus di isi'); isValidSelectedPasien = false;
		} else { 
			$('#pasienErr1').text(''); isValidSelectedPasien = true;
		}

		if (!$('#selectedDokter').val()) {
			$('#dokterErr1').text('Dokter harus di isi'); isValidSelectedDokter = false;
		} else {
			$('#dokterErr1').text(''); isValidSelectedDokter = true;
		}

		if (!$('#keluhan').val()) {
			$('#keluhanErr1').text('Keluhan harus di isi'); isValidKeluhan = false;
		} else {
			$('#keluhanErr1').text(''); isValidKeluhan = true;
		}

		if (!$('#pendaftar').val()) {
			$('#pendaftarErr1').text('Pendaftar harus di isi'); isValidPendaftar = false;
		} else {
			$('#pendaftarErr1').text(''); isValidPendaftar = true;
    }
    
    if (!$('#estimasiRawatInap').val()) {
			$('#estimasiRawatInapErr1').text('Estimasi waktu rawat inap harus di isi'); isValidEstimasi = false;
		} else {
			$('#estimasiRawatInapErr1').text(''); isValidEstimasi = true;
    }
    
    if (!$('#realitaRawatInap').val()) {
			$('#realitaRawatInapErr1').text('Realita waktu rawat inap harus di isi'); isValidRealita = false;
		} else {
			$('#realitaRawatInapErr1').text(''); isValidRealita = true;
		}

		$('#beErr').empty(); isBeErr = false;

		if (!isValidSelectedPasien || !isValidSelectedDokter || !isValidKeluhan 
				|| !isValidPendaftar || !isValidEstimasi || !isValidRealita ||  isBeErr) {
			$('#btnSubmitRawatInap').attr('disabled', true);
		} else {
			$('#btnSubmitRawatInap').attr('disabled', false);
		}
	}

  function formConfigure() {
    $('#selectedPasien').select2();
		$('#selectedDokter').select2();

		$('#modal-rawat-inap').modal('show');
		$('#btnSubmitRawatInap').attr('disabled', true);
  }

});