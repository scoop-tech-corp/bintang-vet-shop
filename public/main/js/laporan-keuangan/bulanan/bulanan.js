$(document).ready(function() {

  let optCabang = '';
  let paramUrlSetup = {
		orderby:'',
		column: '',
    month: '',
    year: '',
    branchId: ''
  };

  if (role.toLowerCase() != 'admin') {
		window.location.href = $('.baseUrl').val() + `/unauthorized`;	
	} else {
    loadCabang();
    $('#filterCabang').select2({ placeholder: 'Cabang', allowClear: true });

		loadLaporanKeuanganBulanan();
    //Date picker
    $('#datepicker').datepicker({
      autoclose: true,
			clearBtn: true,
			format: 'mm-yyyy',
			todayHighlight: true,
      startView: 'months', 
      minViewMode: 'months'
    }).on('changeDate', function(e) {
      const getDate = e.format();
      const getMonth = getDate.split('-')[0];
      const getYear = getDate.split('-')[1];
			paramUrlSetup.month = getMonth;
      paramUrlSetup.year  = getYear;
			loadLaporanKeuanganBulanan();
		});
	}

  $('#filterCabang').on('select2:select', function () { onFilterCabang($(this).val()); });
  $('#filterCabang').on("select2:unselect", function () { onFilterCabang($(this).val()); });

  $('.btn-download-laporan').click(function() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/monthly-finance-report/generate',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
      data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, month: paramUrlSetup.month, year: paramUrlSetup.year, branch_id: paramUrlSetup.branchId },
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

		loadLaporanKeuanganBulanan();
  });

  function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadLaporanKeuanganBulanan();
  }

  function loadLaporanKeuanganBulanan() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/monthly-finance-report',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, month: paramUrlSetup.month, year: paramUrlSetup.year, branch_id: paramUrlSetup.branchId },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(resp) {

        const getData = resp.data;
				let listLaporanKeuanganBulanan = '';

				$('#list-laporan-keuangan-bulanan tr').remove();        

				if (getData.length) {
					$.each(getData, function(idx, v) {
						listLaporanKeuanganBulanan += `<tr>`
							+ `<td>${++idx}</td>`
              + `<td>${v.created_at}</td>`
              + `<td>${v.branch_name}</td>`
              + `<td>${v.payment_number}</td>`
							+ `<td>${v.item_name}</td>`
							+ `<td>${v.category}</td>`
							+ `<td>${v.total_item}</td>`
							+ `<td>Rp ${typeof(v.capital_price) == 'number' ? v.capital_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
							+ `<td>Rp ${typeof(v.selling_price) == 'number' ? v.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
							+ `<td>Rp ${typeof(v.profit) == 'number' ? v.profit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
							+ `<td>Rp ${typeof(v.overall_price)== 'number' ? v.overall_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
							+ `<td>${v.created_by}</td>`
							+ `</tr>`;
					});
				} else { listLaporanKeuanganBulanan += `<tr class="text-center"><td colspan="14">Tidak ada data.</td></tr>`; }
				$('#list-laporan-keuangan-bulanan').append(listLaporanKeuanganBulanan);

        const capitalPrice = resp.capital_price ? resp.capital_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '-';
				const sellingPrice = resp.selling_price ? resp.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '-';
				const profit       = resp.profit ? resp.profit.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : '';

        $('#harga-modal-txt').text(`Rp. ${capitalPrice}`);
        $('#harga-jual-txt').text(`Rp. ${sellingPrice}`);
        $('#keuntungan-txt').text(`Rp. ${profit}`);

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
				optCabang += `<option value=''>Cabang</option>`
	
				if (data.length) {
					for (let i = 0 ; i < data.length ; i++) {
						optCabang += `<option value=${data[i].id}>${data[i].branch_name}</option>`;
					}
				}
				$('#filterCabang').append(optCabang);
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
