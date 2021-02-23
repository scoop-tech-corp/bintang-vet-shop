$(document).ready(function() {

  let paramUrlSetup = {
		orderby:'',
		column: '',
    branchId: ''
  };

  loadPembayaran();

  $('#filterCabang').select2({ placeholder: 'Cabang', allowClear: true });

  $('#filterCabang').on('select2:select', function () { onFilterCabang($(this).val()); });
  $('#filterCabang').on("select2:unselect", function () { onFilterCabang($(this).val()); });

  $('.openFormAdd').click(function() {
    window.location.href = $('.baseUrl').val() + '/pembayaran/tambah';
  });

  function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadPembayaran();
  }

  function loadPembayaran() {
    getIdPasien = null;

    $.ajax({
			url     : $('.baseUrl').val() + '/api/pembayaran',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, branch_id: paramUrlSetup.branchId },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listPembayaran = '';
				$('#list-pembayaran tr').remove();
        console.log('data', data);
				$.each(data, function(idx, v) {
					listPembayaran += `<tr>`
						+ `<td>${++idx}</td>`
						+ `<td>${v.registration_number}</td>`
						+ `<td>${v.patient_number}</td>`
            + `<td>${v.pet_category}</td>`
            + `<td>${v.pet_name}</td>`
						+ `<td>${v.complaint}</td>`
            + `<td>${(v.status_outpatient_inpatient == 1) ? 'Rawat Inap' : 'Rawat Jalan'}</td>`
						+ `<td>${v.created_by}</td>`
						+ `<td>${v.created_at}</td>`
            + `<td>
                <button type="button" class="btn btn-info openDetail" value=${v.check_up_result_id} title="Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
								<button type="button" class="btn btn-warning openFormEdit" value=${v.check_up_result_id}><i class="fa fa-pencil" aria-hidden="true"></i></button>
							</td>`
						+ `</tr>`;
						// <button type="button" class="btn btn-danger openFormDelete" value=${v.check_up_result_id}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
				});
				$('#list-pembayaran').append(listPembayaran);

        $('.openDetail').click(function() {
					window.location.href = $('.baseUrl').val() + `/pembayaran/detail/${$(this).val()}`;					
        });

				$('.openFormEdit').click(function() {
					window.location.href = $('.baseUrl').val() + `/pembayaran/edit/${$(this).val()}`;	
				});
			
				$('.openFormDelete').click(function() {

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

});
