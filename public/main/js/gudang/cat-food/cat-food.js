$(document).ready(function() {
  let getId = null;
  let modalState = '';
  let optCabang= '';

  let isValidSelectedCabang = false;
  let isValidNamaBarang = false;
  let isValidJumlahBarang = false
  let isValidHargaJual = false;
  let isValidHargaModal = false;

  let customErr1 = false;
  let isBeErr = false;
	let paramUrlSetup = {
		orderby:'',
		column: '',
    keyword: '',
    branchId: ''
  };

  if (role.toLowerCase() != 'admin') {
    $('.columnAction').hide(); $('#filterCabang').hide();
    $('[data="capital_price"]').hide(); $('[data="profit"]').hide();
  } else {
    $('#selectedCabang').append(`<option value=''>Pilih Cabang</option>`);
    $('#selectedKategoriBarang').append(`<option value=''>Pilih Kategori Barang</option>`);
    $('#selectedNamaBarang').append(`<option value=''>Pilih Nama Barang</option>`);

    $('.section-left-box-title').append(`
      <button class="btn btn-primary openFormAdd m-r-10px">Tambah</button>
      <button class="btn btn-primary openFormUpload m-r-10px">Upload Sekaligus</button>
      <button class="btn btn-primary downloadExcel">Download Excel</button>
      `);
		$('.section-right-box-title').append(`<select id="filterCabang" style="width: 50%"></select>`);

    $('#filterCabang').select2({ placeholder: 'Cabang', allowClear: true });
    $('#filterCabang').append(`<option value=''>Cabang</option>`);
    loadCabang();
  }

  loadCatFood();

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

		loadCatFood();
  });

  $('.openFormAdd').click(function() {
		modalState = 'add';
    $('.modal-title').text('Tambah Barang Cat Food');

    refreshForm();
    formConfigure();
  });

  $('.openFormUpload').click(function() {
		$('#modal-upload-cat-food .modal-title').text('Upload Barang Cat Food Sekaligus');
		$('#modal-upload-cat-food').modal('show');
		$('.validate-error').html('');
	});

  $('.downloadExcel').click(function() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/gudang/generate',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
      data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword, branch_id: paramUrlSetup.branchId, category: 'Cat Food' },
			xhrFields: { responseType: 'blob' },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data, status, xhr) {
				let disposition = xhr.getResponseHeader('content-disposition');
				let matches = /"([^"]*)"/.exec(disposition);
				let filename = (matches != null && matches[1] ? matches[1] : 'file.xlsx');
				let blob = new Blob([data],{type:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
				let downloadUrl = URL.createObjectURL(blob);
				let a = document.createElement("a");

				a.href = downloadUrl;
				a.download = filename
				document.body.appendChild(a);
				a.click();

			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-shop');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
  });

  $('.btn-download-template').click(function() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/gudang/template',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
      data	  : { filename: 'Template Upload Barang Cat Food.xlsx' },
			xhrFields: { responseType: 'blob' },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data, status, xhr) {
				let disposition = xhr.getResponseHeader('content-disposition');
				let matches = /"([^"]*)"/.exec(disposition);
				let filename = (matches != null && matches[1] ? matches[1] : 'file.xlsx');
				let blob = new Blob([data],{type:'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'});
				let downloadUrl = URL.createObjectURL(blob);
				let a = document.createElement("a");

				a.href = downloadUrl;
				a.download = filename
				document.body.appendChild(a);
				a.click();

			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-shop');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});

	});

  $("#fileupload").fileupload({
		url: $('.baseUrl').val() + '/api/gudang/upload',
		headers : { 'Authorization': `Bearer ${token}` },
		dropZone: '#dropZone',
		dataType: 'json',
    formData: { category: 'Cat Food' },
		autoUpload: false,
	}).on('fileuploadadd', function (e, data) {
		let fileTypeAllowed = /.\.(xlsx|xls)$/i;
		let fileName = data.originalFiles[0]['name'];
		let fileSize = data.originalFiles[0]['size'];

		if (!fileTypeAllowed.test(fileName)) {
			$('.validate-error').html('File harus berformat .xlsx atau .xls');
		} else {
			$('.validate-error').html('');
			data.submit();
		}
	}).on('fileuploaddone', function(e, data) {
		$('#modal-confirmation').hide();

		$("#msg-box .modal-body").text('Berhasil Upload Cat Food');
		$('#msg-box').modal('show');
		setTimeout(() => {
			$('#modal-upload-cat-food').modal('toggle');
			loadCatFood();
		}, 1000);
	}).on('fileuploadfail', function(e, data) {
		const getResponsError = data._response.jqXHR.responseJSON.errors.hasOwnProperty('file') ? data._response.jqXHR.responseJSON.errors.file
			: data._response.jqXHR.responseJSON.errors;

		let errText = '';
		$.each(getResponsError, function(idx, v) {
			errText += v + ((idx !== getResponsError.length - 1) ? '<br/>' : '');
		});
		$('.validate-error').append(errText)
	}).on('fileuploadprogressall', function(e,data) {
	});

  $('#btnSubmitBarangCatFood').click(function() {
    if (modalState == 'add') {

			const fd = new FormData();
      fd.append('branch_id', $('#selectedCabang').val());
			fd.append('item_name', $('#namaBarang').val());
      fd.append('total_item', $('#jumlahBarang').val());
			fd.append('selling_price', $('#hargaJual').val().replaceAll('.', ''));
      fd.append('capital_price', $('#hargaModal').val().replaceAll('.', ''));
      fd.append('profit', $('#label-keuntungan').text().replaceAll('.', ''));
      fd.append('category', 'Cat Food');

      if ($(`#upload-image-1`)[0].files[0]) {
        fd.append('image', $(`#upload-image-1`)[0].files[0]);
      }

			$.ajax({
				url : $('.baseUrl').val() + '/api/gudang',
				type: 'POST',
				dataType: 'JSON',
				headers: { 'Authorization': `Bearer ${token}` },
				data: fd, contentType: false, cache: false,
				processData: false,
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(resp) {

					$("#msg-box .modal-body").text('Berhasil Menambah Cat Food');
					$('#msg-box').modal('show');

					setTimeout(() => {
						$('#modal-barang-cat-food').modal('toggle');
						refreshForm(); loadCatFood();
					}, 1000);
				}, complete: function() { $('#loading-screen').hide(); }
				, error: function(err) {
					if (err.status === 422) {
						let errText = ''; $('#beErr').empty(); $('#btnSubmitBarangCatFood').attr('disabled', true);
						$.each(err.responseJSON.errors, function(idx, v) {
							errText += v + ((idx !== err.responseJSON.errors.length - 1) ? '<br/>' : '');
						});
						$('#beErr').append(errText); isBeErr = true;
					} else if (err.status == 401) {
						localStorage.removeItem('vet-shop');
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

      const fd = new FormData();
      fd.append('id', getId);
      fd.append('branch_id', $('#selectedCabang').val());
			fd.append('item_name', $('#namaBarang').val());
      fd.append('total_item', $('#jumlahBarang').val());
			fd.append('selling_price', $('#hargaJual').val().replaceAll('.', ''));
      fd.append('capital_price', $('#hargaModal').val().replaceAll('.', ''));
      fd.append('profit', $('#label-keuntungan').text().replaceAll('.', ''));
      fd.append('category', 'Cat Food');

      if ($(`#upload-image-1`)[0].files[0]) {
        fd.append('image', $(`#upload-image-1`)[0].files[0]);
      }

      $.ajax({
        url : $('.baseUrl').val() + '/api/gudang/update',
        type: 'POST',
        dataType: 'JSON',
        headers: { 'Authorization': `Bearer ${token}` },
        data: fd, contentType: false, cache: false,
				processData: false,
        beforeSend: function() { $('#loading-screen').show(); },
        success: function(data) {
          $('#modal-confirmation .modal-title').text('Peringatan');
          $('#modal-confirmation').modal('toggle');

          $("#msg-box .modal-body").text('Berhasil Mengubah Cat Food');
          $('#msg-box').modal('show');

          setTimeout(() => {
            $('#modal-barang-cat-food').modal('toggle');
            refreshForm(); loadCatFood();
          }, 1000);

        }, complete: function() { $('#loading-screen').hide(); }
        , error: function(err) {
          if (err.status == 401) {
            localStorage.removeItem('vet-shop');
            location.href = $('.baseUrl').val() + '/masuk';
          }
        }
      });
    } else {
      // process delete
      $.ajax({
        url     : $('.baseUrl').val() + '/api/gudang',
        headers : { 'Authorization': `Bearer ${token}` },
        type    : 'DELETE',
        data	  : { id: getId },
        beforeSend: function() { $('#loading-screen').show(); },
        success: function(data) {
          $('#modal-confirmation .modal-title').text('Peringatan');
          $('#modal-confirmation').modal('toggle');

          $("#msg-box .modal-body").text('Berhasil menghapus data');
          $('#msg-box').modal('show');

          loadCatFood();

        }, complete: function() { $('#loading-screen').hide(); }
        , error: function(err) {
          if (err.status == 401) {
            localStorage.removeItem('vet-shop');
            location.href = $('.baseUrl').val() + '/masuk';
          }
        }
      });
    }
  });

  $('#selectedCabang').on('select2:select', function (e) {
    validationHargaJual(); validationForm();
  });

  $('#hargaJual').mask("#.##0", {reverse: true, maxlength: false});
  $('#hargaModal').mask("#.##0", {reverse: true, maxlength: false});

  $('#namaBarang').keyup(function () { validationHargaJual(); validationForm(); });
  $('#jumlahBarang').keyup(function () { validationHargaJual(); validationForm(); });
  $('#hargaJual').keyup(function () { validationHargaJual(); validationForm(); });
  $('#hargaModal').keyup(function () { validationHargaJual(); validationForm(); });

  $('#filterCabang').on('select2:select', function () { onFilterCabang($(this).val()); });
  $('#filterCabang').on("select2:unselect", function () { onFilterCabang($(this).val()); });

  $('#upload-image-1').change(function() {
    const file = this.files[0];
    validationForm();

    if(file) {
      $('.img-preview-1').show(); $('#icon-plus-upload-1').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload img.img-preview-1').attr("src", this.result);
        $('.box-image-upload a.img-preview-1').attr('href', this.result);
      };
      $('[noUploadImage="1"]').show();
      reader.readAsDataURL(file);
    } else {
      $('.img-preview-1').hide(); $('#icon-plus-upload-1').show();
      $('#upload-image-1').val(''); $('[noUploadImage="1"]').hide();
    }
  });

  $('.btn-trash-upload-image').click(function(event) {
    validationForm();
    resetImageUpload();
  });

  function resetImageUpload() {
    $(`#upload-image-1`).val('');
    $(`#upload-image-1`).show();
    $(`#icon-plus-upload-1`).show();
    $(`.box-image-upload img.img-preview-1`).attr('src', null);
    $(`.box-image-upload a.img-preview-1`).removeAttr('href');
    $(`.box-image-upload .img-preview-1`).hide();
    $(`#btn-trash-upload-image-1`).hide();
    $(`[noUploadImage="1"]`).hide();
  }

  function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadCatFood();
  }

  function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadCatFood();
	}

  function loadCatFood() {

    getId = null; modalState = '';
    $.ajax({
			url     : $('.baseUrl').val() + '/api/gudang',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword, branch_id: paramUrlSetup.branchId, category: 'Cat Food' },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listCatFood = '';
				$('#list-cat-food tr').remove();

        if(data.length) {
          $.each(data, function(idx, v) {
            listCatFood += `<tr>`
              + `<td>${++idx}</td>`
              + `<td>${v.item_name}</td>`
              + `<td>${v.total_item}</td>`
              + `<td>Rp ${typeof(v.selling_price) == 'number' ? v.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
              + `<td>
                  ${
                    (role.toLowerCase() == 'admin') ?
                      `Rp ${typeof(v.capital_price) == 'number' ? v.capital_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}`
                    : `${(v.image)
                        ? `<a class="image-list-cat-food" href="${v.image}"><img src="${v.image}" width="50" height="50"></a>`
                        : `<div style="padding-left: 15px">-</div>`}`
                  }
                </td>`
              + `<td>
                  ${
                    (role.toLowerCase() == 'admin') ?
                      `Rp ${(typeof(v.profit) == 'number') ? v.profit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}`
                    : `${v.branch_name}`
                  }
                </td>`
              + `<td>
                  ${
                    (role.toLowerCase() == 'admin') ?
                      (v.image)
                        ? `<a class="image-list-cat-food" href="${v.image}"><img src="${v.image}" width="50" height="50"></a>`
                        : `<div style="padding-left: 15px">-</div>`
                    : `${v.created_by}`
                  }
                </td>`
              + ((role.toLowerCase() == 'admin') ? `<td>${v.branch_name}</td>` : ``)
              + ((role.toLowerCase() == 'admin') ? `<td>${v.created_by}</td>` : ``)
              + `<td>${v.created_at}</td>`
              + ((role.toLowerCase() == 'admin') ? `<td>
                    <button type="button" class="btn btn-warning openFormEdit" value=${v.id}><i class="fa fa-pencil" aria-hidden="true"></i></button>
                    <button type="button" class="btn btn-danger openFormDelete" value=${v.id}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
                  </td>` : ``)
              + `</tr>`;
          });
        } else {
          listCatFood += `<tr class="text-center"><td colspan="11">Tidak ada data.</td></tr>`;
        }

				$('#list-cat-food').append(listCatFood);

				$('.openFormEdit').click(function() {
					const getObj = data.find(x => x.id == $(this).val());
					modalState = 'edit';

					$('.modal-title').text('Edit Barang Cat Food');
          refreshForm(); formConfigure();

					getId = getObj.id;
          $('#selectedCabang').val(getObj.branch_id); $('#selectedCabang').trigger('change');
          $('#namaBarang').val(getObj.item_name);
          $('#jumlahBarang').val(getObj.total_item);
          $('#hargaJual').val(getObj.selling_price);
          $('#hargaModal').val(getObj.capital_price);

          validationHargaJual();
          if (getObj.image) {
            $(`.box-image-upload img.img-preview-1`).attr('src', $('.baseUrl').val() + getObj.image);
            // $(`.box-image-upload a.img-preview-1`).attr('href', $('.baseUrl').val() + getObj.image);
            $(`.box-image-upload img.img-preview-1, .box-image-upload a.img-preview-1`).show();
            $(`[noUploadImage="1"]`).show(); $(`#icon-plus-upload-1`).hide();  $(`#upload-image-1`).hide();
          }
				});

				$('.openFormDelete').click(function() {
					getId = $(this).val();
					modalState = 'delete';

					$('#modal-confirmation .modal-title').text('Peringatan');
					$('#modal-confirmation .box-body').text('Anda yakin ingin menghapus data ini?');
					$('#modal-confirmation').modal('show');
				});

        $('.image-list-cat-food').magnificPopup({type:'image'});

			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-shop');
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
				if (data.length) {
					for (let i = 0 ; i < data.length ; i++) {
						optCabang += `<option value=${data[i].id}>${data[i].branch_name}</option>`;
					}
				}
        $('#selectedCabang').append(optCabang); $('#filterCabang').append(optCabang);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-shop');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
  }

  function validationForm() {
    if (!$('#selectedCabang').val()) {
			$('#cabangErr1').text('Cabang barang harus di isi'); isValidSelectedCabang = false;
		} else {
			$('#cabangErr1').text(''); isValidSelectedCabang = true;
		}

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

    if ($('#hargaJual').val() == '') {
			$('#hargaJualErr1').text('Harga jual harus di isi'); isValidHargaJual = false;
		} else {
			$('#hargaJualErr1').text(''); isValidHargaJual = true;
    }

    if ($('#hargaModal').val() == '') {
			$('#hargaModalErr1').text('Harga modal harus di isi'); isValidHargaModal = false;
		} else {
			$('#hargaModalErr1').text(''); isValidHargaModal = true;
    }

    let hargaJual  = $('#hargaJual').val().replaceAll('.', '');
    let hargaModal = $('#hargaModal').val().replaceAll('.', '');

    if (parseInt(hargaJual) <= parseInt(hargaModal)) {
      $('#customErr1').text('Nominal Harga Jual harus lebih besar dari harga modal');
      customErr1 = false;
		} else {
			$('#customErr1').text(''); customErr1 = true;
		}

    $('#beErr').empty(); isBeErr = false;
    validationBtnSubmitBarangCatFood();
  }

  function refreshForm() {
		$('#selectedCabang').val(null);
    $('#namaBarang').val(null);
    $('#jumlahBarang').val(null);
    $('#hargaJual').val(null);
    $('#hargaModal').val(null);
    resetImageUpload();

    $('#customErr1').empty(); customErr1 = false;
    $('#beErr').empty(); isBeErr = false;

    $('#cabangErr1').text(''); isValidSelectedCabang = true;
    $('#namaBarangErr1').text(''); isValidNamaBarang = true;
    $('#jumlahBarangErr1').text(''); isValidJumlahBarang = true;

    $('#hargaJualErr1').text(''); isValidHargaJual = true;
    $('#hargaModalErr1').text(''); isValidHargaModal = true;

    $('#label-keuntungan').text('-');
  }

  function formConfigure() {
    $('#selectedCabang').select2();

		$('#modal-barang-cat-food').modal('show');
		$('#btnSubmitBarangCatFood').attr('disabled', true);
  }

  function validationHargaJual() {
    let hargaJual  = $('#hargaJual').val().replaceAll('.', '');
    let hargaModal = $('#hargaModal').val().replaceAll('.', '');
    let totalKeuntungan = '-';

    if(hargaJual && hargaModal) {
      totalKeuntungan = parseInt(hargaJual) - parseInt(hargaModal);
    }

    $('#label-keuntungan').text(totalKeuntungan.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
  }

  function validationBtnSubmitBarangCatFood() {
    if (!isValidSelectedCabang || !isValidNamaBarang || !isValidJumlahBarang
      || !isValidHargaJual || !isValidHargaModal || !customErr1 || isBeErr) {
			$('#btnSubmitBarangCatFood').attr('disabled', true);
		} else {
			$('#btnSubmitBarangCatFood').attr('disabled', false);
		}
  }

})
