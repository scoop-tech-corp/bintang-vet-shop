$(document).ready(function() {
	let optCabang = '';
  let optCabangForm = '';
  let optBarang = '';
  let listBarang = [];
  let listSelectedBarang = [];

  let isValidSelectedCabang = false;
  let isValidListSelectedBarang = false;

  let paramUrlSetup = {
		orderby:'',
		column: '',
    branchId: ''
  };
  let getId = null;
  let modalState  = '';

  if (role.toLowerCase() == 'admin') {
    loadCabang(); $('.showDropdownBarang').hide();
    $('#filterCabang').select2({ placeholder: 'Cabang', allowClear: true });
  } else {
    $('.showDropdownBarang').show();
    $('.showDropdownCabang').hide();
    $('#filterCabang').hide();
    $('.columnAction').hide();
  }

  loadPembayaran();

  $('#filterCabang').on('select2:select', function () { onFilterCabang($(this).val()); });
  $('#filterCabang').on("select2:unselect", function () { onFilterCabang($(this).val()); });

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

		loadPembayaran();
  });

  $('#selectedCabang').on('select2:select', function (e) {
    const getCabangId = $(this).val();
    if (getCabangId) { loadBarang(getCabangId); }
  });

  $('#selectedBarang').on('select2:select', function (e) {
    const getBarangId = $(this).val();
    let getObj = listBarang.find(barang => barang.id == getBarangId);
    listSelectedBarang.push({
      id: getObj.id,
      item_name: getObj.item_name,
      category: getObj.category,
      total_item: 0,
      selling_price: getObj.selling_price,
      price_overall: 0
    });
    drawTableSelectedBarang();

    // deleted list barang
    let getIdxBarang = listBarang.findIndex(barang => barang.id == getBarangId);
    listBarang.splice(getIdxBarang, 1);
    drawDropdownListBarang();

    validationForm();
  });

  function drawTableSelectedBarang() {
    let listSelectedBarangTxt = '';
    $('#list-selected-barang tr').remove();

    if (listSelectedBarang.length) {
      listSelectedBarang.forEach((barang, idx) => {
        listSelectedBarangTxt += `<tr>`
          + `<td>${idx + 1}</td>`
          + `<td>${barang.item_name}</td>`
          + `<td>${barang.category}</td>`
          + `<td><input type="number" min="0" class="qty-input-barang" index=${idx} value=${barang.total_item}></td>`
          + `<td>Rp ${barang.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>`
          + `<td>Rp <span id="overallPrice-${idx}">
              ${typeof(barang.price_overall) == 'number' ?
                barang.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')
                : ''}</span>
            </td>`
          +`<td>
              <button type="button" class="btn btn-danger btnDeleteSelectedBarang" value=${idx}>
                <i class="fa fa-trash-o" aria-hidden="true"></i>
              </button>
            </td>`
          + `</tr>`;
      });
    } else {
      listSelectedBarangTxt += `<tr class="text-center"><td colspan="7">Tidak ada data.</td></tr>`;
    }

    $('#list-selected-barang').append(listSelectedBarangTxt);

    $('.qty-input-barang').on('input', function(e) {
      const idx        = $(this).attr('index');
      const value      = parseFloat($(this).val());
      const eachItem   = parseFloat(listSelectedBarang[idx].selling_price);
      let overallPrice = value * eachItem;

      listSelectedBarang[idx].total_item = value;
      listSelectedBarang[idx].price_overall = overallPrice;
      validationForm();

      $('#overallPrice-'+idx).text(overallPrice.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });

    $('.btnDeleteSelectedBarang').click(function() {
      const getObj = listSelectedBarang[$(this).val()];
      listBarang.push(getObj);
      drawDropdownListBarang();

      listSelectedBarang.splice($(this).val(), 1);
      drawTableSelectedBarang();
      validationForm();
    });
  }

  function drawDropdownListBarang() {
    optBarang = `<option value=''>Pilih Barang</option>`;
    $('#selectedBarang option').remove();

    if (listBarang.length) {
      for (let i = 0 ; i < listBarang.length ; i++) {
        optBarang += `<option value=${listBarang[i].id}>${listBarang[i].item_name} - ${listBarang[i].category}</option>`;
      }
    }
    $('#selectedBarang').append(optBarang);
  }

  $('.openFormAdd').click(function() {
    modalState = 'add';
    $('.modal-title').text('Tambah Pembayaran');

    if (role.toLowerCase() == 'kasir') {
      loadBarang(branchId);
    }

    refreshForm(); formConfigure();
  });

  $('.downloadRekap').click(function() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/payment/generate',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
      data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword, branch_id: paramUrlSetup.branchId },
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

  $('#submitConfirm').click(function() {
    // process delete
			$.ajax({
				url     : $('.baseUrl').val() + '/api/payment',
				headers : { 'Authorization': `Bearer ${token}` },
				type    : 'DELETE',
				data	  : { id: getId },
				beforeSend: function() { $('#loading-screen').show(); },
				success: function(data) {
					$('#modal-confirmation .modal-title').text('Peringatan');
					$('#modal-confirmation').modal('toggle');

					$("#msg-box .modal-body").text('Berhasil menghapus data');
					$('#msg-box').modal('show');

					loadPembayaran();

				}, complete: function() { $('#loading-screen').hide(); }
				, error: function(err) {
					if (err.status == 401) {
						localStorage.removeItem('vet-shop');
						location.href = $('.baseUrl').val() + '/masuk';
					}
				}
			});
  });

  $('#btnSubmitPembayaran').click(function() {
    let finalSelectedBarang = [];
    const fd = new FormData();
    const getBranchId = (role.toLowerCase() == 'admin') ? $('#selectedCabang').val() : branchId;

    listSelectedBarang.forEach(barang => {
      finalSelectedBarang.push({
        list_of_item_id: barang.id,
        total_item: barang.total_item
      })
    });

    fd.append('branch_id', getBranchId); // for kasir id cabang from login data
    fd.append('list_of_items', JSON.stringify(finalSelectedBarang));

    $.ajax({
      url: $('.baseUrl').val() + '/api/payment',
      type: 'POST',
      dataType: 'JSON',
      headers: { 'Authorization': `Bearer ${token}` },
      data: fd, contentType: false, cache: false,
      processData: false,
      beforeSend: function () { $('#loading-screen').show(); },
      success: function (resp) {

        $("#msg-box .modal-body").text('Berhasil Menambah Data');
        $('#msg-box').modal('show');

        const getMasterPaymentId = resp.master_payment_id;

        processPrint(getMasterPaymentId);

        setTimeout(() => {
          $('#modal-tambah-pembayaran').modal('toggle');
          refreshForm(); loadPembayaran();
        }, 1000);
      }, complete: function () { $('#loading-screen').hide(); }
      , error: function (err) {
        if (err.status === 422) {
          let errText = ''; $('#beErr').empty(); $('#btnSubmitPembayaran').attr('disabled', true);
          $.each(err.responseJSON.errors, function (idx, v) {
            errText += v + ((idx !== err.responseJSON.errors.length - 1) ? '<br/>' : '');
          });
          $('#beErr').append(errText); isBeErr = true;
        } else if (err.status == 401) {
          localStorage.removeItem('vet-shop');
          location.href = $('.baseUrl').val() + '/masuk';
        }
      }
    });

  });

  function processPrint(master_payment_id) {
    let url = '/payment/printreceipt/' + master_payment_id;
    window.open($('.baseUrl').val() + url, '_blank');
  }

  function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadPembayaran();
  }

  function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadPembayaran();
	}

  function refreshForm() {
		$('#selectedCabang').val(null);
    $('#selectedBarang').val(null);
    listSelectedBarang = [];
    drawTableSelectedBarang();

    $('#selectedCabang').prop('disabled', false);

    if(role.toLowerCase() == 'admin') {
      $('.showDropdownBarang').hide();
    }

    $('#beErr').empty(); isBeErr = false;

    $('#cabangErr1').text(''); isValidSelectedCabang = true;
    $('#barangErr1').text(''); isValidListSelectedBarang = true;
  }

  function formConfigure() {
    $('#selectedCabang').select2();
    $('#selectedBarang').select2();

		$('#modal-tambah-pembayaran').modal('show');
		$('#btnSubmitPembayaran').attr('disabled', true);
  }

  function loadPembayaran() {

    $.ajax({
			url     : $('.baseUrl').val() + '/api/payment',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword, branch_id: paramUrlSetup.branchId },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listPembayaran = '';
				$('#list-pembayaran tr').remove();

        if (data.length) {
          $.each(data, function(idx, v) {
            listPembayaran += `<tr>`
              + `<td>${++idx}</td>`
              + `<td>${v.created_at}</td>`
              + `<td>${v.branch_name}</td>`
              + `<td>${v.payment_number}</td>`
              + `<td>${v.item_name}</td>`
              + `<td>${v.category}</td>`
              + `<td>${v.total_item}</td>`
              + `<td>Rp ${v.each_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>`
              + `<td>Rp ${v.overall_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')}</td>`
              + `<td>${v.created_by}</td>`
              + ((role.toLowerCase() == 'admin') ? 
                  `<td>
                    <button type="button" class="btn btn-danger openFormDelete" value=${v.id}>
                      <i class="fa fa-trash-o" aria-hidden="true"></i>
                    </button>
                  </td>` : ``
                )
              + `</tr>`;
          });
        } else {
          listPembayaran += `<tr class="text-center"><td colspan="${(role.toLowerCase() == 'admin') ? '9' : '8'}">Tidak ada data.</td></tr>`;
        }

				$('#list-pembayaran').append(listPembayaran);
			
				$('.openFormDelete').click(function() {
          getId = $(this).val();

					if (role.toLowerCase() == 'admin') {
            $('#modal-confirmation .modal-title').text('Peringatan');
						$('#modal-confirmation .box-body').text('Anda yakin ingin menghapus data ini?');
						$('#modal-confirmation').modal('show');
          }
				});

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
    if (!$('#selectedCabang').val() && role.toLowerCase() == 'admin') {
      $('#cabangErr1').text('Cabang harus di isi'); isValidSelectedCabang = false;
    } else {
      $('#cabangErr1').text(''); isValidSelectedCabang = true;
    }

    if (!listSelectedBarang.length) {
      $('#barangErr1').text('Barang harus di pilih'); isValidListSelectedBarang = false;
    } else {
      $('#barangErr1').text(''); isValidListSelectedBarang = true;
    }

    $('#beErr').empty(); isBeErr = false;

    $('#btnSubmitPembayaran').attr('disabled', (!isValidSelectedCabang || !isValidListSelectedBarang || isBeErr) ? true : false);
  }

	function loadCabang() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/cabang',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				optCabang += `<option value=''>Cabang</option>`
        optCabangForm += `<option value=''>Cabang</option>`
	
				if (data.length) {
					for (let i = 0 ; i < data.length ; i++) {
						optCabang += `<option value=${data[i].id}>${data[i].branch_name}</option>`;
            optCabangForm += `<option value=${data[i].id}>${data[i].branch_name}</option>`;
					}
				}
				$('#filterCabang').append(optCabang);
        $('#selectedCabang').append(optCabangForm);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-shop');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
	}

  function loadBarang(getCabangId) {
    optBarang = `<option value=''>Pilih Barang</option>`;

    $.ajax({
      url: $('.baseUrl').val() + '/api/payment/filteritem',
      headers: { 'Authorization': `Bearer ${token}` },
      type: 'GET',
      data: { branch_id: getCabangId },
      beforeSend: function () { $('#loading-screen').show(); },
      success: function (data) {
        $('#selectedBarang option').remove();
  
        if (data.length) {
          for (let i = 0 ; i < data.length ; i++) {
            optBarang += `<option value=${data[i].id}>${data[i].item_name} - ${data[i].category}</option>`;
            listBarang.push(data[i]);
          }
        }
        $('#selectedCabang').prop('disabled', true);
        $('.showDropdownBarang').show();
        $('#selectedBarang').append(optBarang);

        validationForm();
      }, complete: function() { $('#loading-screen').hide(); },
      error: function(err) {
        if (err.status == 401) {
          localStorage.removeItem('vet-shop');
          location.href = $('.baseUrl').val() + '/masuk';
        }
      }
    });
  }

});
