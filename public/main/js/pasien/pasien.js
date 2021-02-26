$(document).ready(function() {
	let modalState = '';
	let getId = null
	let optCabang = '';

	let isValidAnimalType = false;
  let isValidAnimalName = false;
  let isValidAnimalSex = false;
	let isValidAnimalAge = false;

	let isValidOwnerName = false;
	let isValidOwnerAddress = false;
	let isValidOwnerTelp = false;

	let isBeErr = false;
	let paramUrlSetup = {
		orderby:'',
		column: '',
    keyword: '',
    branchId: ''
	};

	if (role.toLowerCase() == 'resepsionis') {
		$('.columnAction').hide(); // $('#filterCabang').hide();
	} else if (role.toLowerCase() == 'admin') {
		$('.section-right-box-title').addClass('width-350px');
		$('.section-right-box-title').append(`<select id="filterCabang" style="width: 50%"></select>`);
		$('#filterCabang').select2({ placeholder: 'Cabang', allowClear: true });
		loadCabang();
	}

	loadPasien();

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

		loadPasien();
	});

	$('.openFormAdd').click(function() {
		modalState = 'add';
    $('.modal-title').text('Tambah Pasien');
		$('.detail-register').hide();

    refreshForm();
    formConfigure();
	});
	
	$('#animalSex').on('select2:select', function (e) { validationForm(); });
	$('#animalType').keyup(function () { validationForm(); });
	$('#animalName').keyup(function () { validationForm(); });
	$('#animalAgeYear').keyup(function () { validationForm(); });
	$('#animalAgeMonth').keyup(function () { validationForm(); });
	$('#ownerName').keyup(function () { validationForm(); });
	$('#ownerAddress').keyup(function () { validationForm(); });
	$('#ownerTelp').keyup(function () { validationForm(); });

	$('#filterCabang').on('select2:select', function () { onFilterCabang($(this).val()); });
	$('#filterCabang').on("select2:unselect", function () { onFilterCabang($(this).val()); });
	
	$('#btnSubmitPasien').click(function() {
    if (modalState == 'add') {

			const fd = new FormData();
			fd.append('kategori_hewan', $('#animalType').val());
			fd.append('nama_hewan', $('#animalName').val());
			fd.append('jenis_kelamin_hewan', $('#animalSex').val());
      fd.append('usia_tahun_hewan', $('#animalAgeYear').val());
      fd.append('usia_bulan_hewan', $('#animalAgeMonth').val());
			fd.append('nama_pemilik', $('#ownerName').val());
			fd.append('alamat_pemilik', $('#ownerAddress').val());
			fd.append('nomor_ponsel_pengirim', $('#ownerTelp').val());

			$.ajax({
				url : $('.baseUrl').val() + '/api/pasien',
				type: 'POST',
				dataType: 'JSON',
				headers: { 'Authorization': `Bearer ${token}` },
				data: fd, contentType: false, cache: false,
				processData: false,
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(resp) {

					$("#msg-box .modal-body").text('Berhasil Menambah Pasien');
					$('#msg-box').modal('show');

					setTimeout(() => {
						$('#modal-pasien').modal('toggle');
						refreshForm();
						loadPasien();
					}, 1000);
				}, complete: function() { $('#loading-screen').hide(); }
				, error: function(err) {
					if (err.status === 422) {
						let errText = ''; $('#beErr').empty(); $('#btnSubmitPasien').attr('disabled', true);
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
        kategori_hewan: $('#animalType').val(),
        nama_hewan: $('#animalName').val(),
        jenis_kelamin_hewan: $('#animalSex').val(),
        usia_tahun_hewan: $('#animalAgeYear').val(),
				usia_bulan_hewan: $('#animalAgeMonth').val(),
				nama_pemilik: $('#ownerName').val(),
				alamat_pemilik: $('#ownerAddress').val(),
				nomor_ponsel_pengirim: $('#ownerTelp').val()
			};

      $.ajax({
        url : $('.baseUrl').val() + '/api/pasien',
        type: 'PUT',
        dataType: 'JSON',
        headers: { 'Authorization': `Bearer ${token}` },
        data: datas,
        beforeSend: function() { $('#loading-screen').show(); },
        success: function(data) {
          $('#modal-confirmation .modal-title').text('Peringatan');
          $('#modal-confirmation').modal('toggle');

          $("#msg-box .modal-body").text('Berhasil Mengubah data');
          $('#msg-box').modal('show');

          setTimeout(() => {
            $('#modal-pasien').modal('toggle');
            refreshForm(); loadPasien();
          }, 1000);

        }, complete: function() { $('#loading-screen').hide(); }
        , error: function(err) {
					if (err.status === 422) {
						$('#modal-confirmation').modal('toggle');
						let errText = ''; $('#beErr').empty(); $('#btnSubmitPasien').attr('disabled', true);
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
      // process delete
      $.ajax({
        url     : $('.baseUrl').val() + '/api/pasien',
        headers : { 'Authorization': `Bearer ${token}` },
        type    : 'DELETE',
        data	  : { id: getId },
        beforeSend: function() { $('#loading-screen').show(); },
        success: function(data) {
          $('#modal-confirmation .modal-title').text('Peringatan');
          $('#modal-confirmation').modal('toggle');

          $("#msg-box .modal-body").text('Berhasil menghapus data');
          $('#msg-box').modal('show');

          loadPasien();

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

  function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadPasien();
  }

  function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadPasien();
	}

	function loadPasien() {
		getId = null; modalState = '';
    $.ajax({
			url     : $('.baseUrl').val() + '/api/pasien',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword, branch_id: paramUrlSetup.branchId },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listPasien = '';
				$('#list-pasien tr').remove();

				$.each(data, function(idx, v) {
					listPasien += `<tr>`
						+ `<td>${++idx}</td>`
						+ `<td>${v.id_member}</td>`
						+ `<td>${v.pet_category}</td>`
						+ `<td>${v.pet_name}</td>`
						+ `<td>${v.pet_gender}</td>`
						+ `<td>${v.pet_year_age} Tahun</td>`
						+ `<td>${v.branch_name}</td>`
						+ `<td>${v.created_by}</td>`
						+ `<td>${v.created_at}</td>`
						+ ((role.toLowerCase() == 'resepsionis') ? `` : `<td>
								<button type="button" class="btn btn-info openDetail" title="Riwayat Pasien" value=${v.id}><i class="fa fa-eye" aria-hidden="true"></i></button>
								<button type="button" class="btn btn-warning openFormEdit" title="Edit" value=${v.id}><i class="fa fa-pencil" aria-hidden="true"></i></button>
								<button type="button" class="btn btn-danger openFormDelete" title="Delete" value=${v.id}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
							</td>`)
						+ `</tr>`;
				});
				$('#list-pasien').append(listPasien);

				$('.openFormEdit').click(function() {
					const getObj = data.find(x => x.id == $(this).val());
					modalState = 'edit';

					$('.modal-title').text('Edit Pasien');
          refreshForm(); formConfigure();
					$('.detail-register').show();

					getId = getObj.id;
					$('#animalSex').val(getObj.pet_gender);
					$('#animalSex').trigger('change');
					$('#animalType').val(getObj.pet_category);
					$('#animalName').val(getObj.pet_name);
					$('#animalAgeYear').val(getObj.pet_year_age);
					$('#animalAgeMonth').val(getObj.pet_month_age);
					$('#ownerName').val(getObj.owner_name);
					$('#ownerAddress').val(getObj.owner_address);
					$('#ownerTelp').val(getObj.owner_phone_number);
				});
			
				$('.openFormDelete').click(function() {
					getId = $(this).val();
					modalState = 'delete';

					$('#modal-confirmation .modal-title').text('Peringatan');
					$('#modal-confirmation .box-body').text('Anda yakin ingin menghapus data ini?');
					$('#modal-confirmation').modal('show');
				});

				$('.openDetail').click(function() {
					window.location.href = $('.baseUrl').val() + `/riwayat-pameriksaan/${$(this).val()}`;	
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

	function loadCabang() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/cabang',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				optCabang += `<option value=''>Cabang</option>`;
				if (data.length) {
					for (let i = 0 ; i < data.length ; i++) {
						optCabang += `<option value=${data[i].id}>${data[i].branch_name}</option>`;
					}
				}
        $('#filterCabang').append(optCabang);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
	}

	function validationForm() {
		if (!$('#animalType').val()) {
			$('#animalTypeErr1').text('Jenis hewan harus di isi'); isValidAnimalType = false;
		} else { 
			$('#animalTypeErr1').text(''); isValidAnimalType = true;
		}

		if (!$('#animalName').val()) {
			$('#animalNameErr1').text('Nama Hewan harus di isi'); isValidAnimalName = false;
		} else {
			$('#animalNameErr1').text(''); isValidAnimalName = true;
		}

		if (!$('#animalSex').val()) {
			$('#animalSexErr1').text('Jenis Kelamin Hewan harus di isi'); isValidAnimalSex = false;
		} else {
			$('#animalSexErr1').text(''); isValidAnimalSex = true;
    }

    if (!$('#animalAgeYear').val() || !$('#animalAgeMonth').val()) {
			$('#animalAgeErr1').text('Usia Hewan harus di isi'); isValidAnimalAge = false;
		} else {
			$('#animalAgeErr1').text(''); isValidAnimalAge = true;
    }

    if ($('#ownerName').val() == '') {
			$('#ownerNameErr1').text('Nama Pemilik harus di isi'); isValidOwnerName = false;
		} else {
			$('#ownerNameErr1').text(''); isValidOwnerName = true;
    }

    if ($('#ownerAddress').val() == '') {
			$('#ownerAddressErr1').text('Alamat Pemilik harus di isi'); isValidOwnerAddress = false;
		} else {
			$('#ownerAddressErr1').text(''); isValidOwnerAddress = true;
		}
		
		if ($('#ownerTelp').val() == '') {
			$('#ownerTelpErr1').text('Telpon Pemilik harus di isi'); isValidOwnerTelp = false;
		} else {
			$('#ownerTelpErr1').text(''); isValidOwnerTelp = true;
    }
    
		$('#beErr').empty(); isBeErr = false;

		if (!isValidAnimalType || !isValidAnimalName || !isValidAnimalSex  || !isValidAnimalAge 
			|| !isValidOwnerName || !isValidOwnerAddress || !isValidOwnerTelp || isBeErr) {
			$('#btnSubmitPasien').attr('disabled', true);
		} else {
			$('#btnSubmitPasien').attr('disabled', false);
		}
	}
	
	function refreshForm() {
		$('#animalType').val(null); isValidAnimalType = true;
		$('#animalName').val(null); isValidAnimalName = true;
    $('#animalSex').val(null); isValidAnimalSex = true;

		$('#animalAgeYear').val(null); isValidAnimalAgeYear = true;
		$('#animalAgeMonth').val(null); isValidAnimalAgeMonth = true;
		$('#ownerName').val(null); isValidOwnerName = true;
		$('#ownerAddress').val(null); isValidOwnerAddress = true;
		$('#ownerTelp').val(null); isValidOwnerTelp = true;
    
    $('#beErr').empty(); isBeErr = false;

    $('#noRegisTxt').text('');
	}
	
	function formConfigure() {
		$('#animalSex').select2();
		$('#modal-pasien').modal('show');
		$('#btnSubmitPasien').attr('disabled', true);
	}

});