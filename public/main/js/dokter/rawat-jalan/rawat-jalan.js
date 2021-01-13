$(document).ready(function() {

  let getId = null;
  let isValidAlasan = false;
  let isBeErr = false;
  let paramUrlSetup = {
		orderby:'',
		column: '',
		keyword: ''
  };
  
  loadDokterRawatJalan();

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

		loadDokterRawatJalan();
  });

  function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadDokterRawatJalan();
	}

  function loadDokterRawatJalan() {
    getId = null;
		$.ajax({
			url     : $('.baseUrl').val() + '/api/dokter-rawat-jalan',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword},
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listDokterRawatJalan = '';
				$('#list-dokter-rawat-jalan tr').remove();

				$.each(data, function(idx, v) {
					listDokterRawatJalan += `<tr>`
						+ `<td>${++idx}</td>`
						+ `<td>${v.id_number}</td>`
            + `<td>${v.id_number_patient}</td>`
            + `<td>${v.pet_category}</td>`
            + `<td>${v.pet_name}</td>`
            + `<td>${v.complaint}</td>`
            + `<td>${v.registrant}</td>`
						+ `<td>${v.created_by}</td>`
						+ `<td>${v.created_at}</td>`
						+ `<td>
								<button type="button" class="btn btn-info openDetail" value=${v.id} title="Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-success openTerima" value=${v.id} title="Diterima"><i class="fa fa-check" aria-hidden="true"></i></button>
                <button type="button" class="btn btn-danger openTolak" value=${v.id} title="Ditolak"><i class="fa fa-close" aria-hidden="true"></i></button>
							</td>`
						+ `</tr>`;
				});
				$('#list-dokter-rawat-jalan').append(listDokterRawatJalan);

				$('.openDetail').click(function() {
          const getObj = data.find(x => x.id == $(this).val());
          console.log('getObj', getObj);
					getId = getObj.id; refreshForm();
          $('.modal-title').text('Detail Pasien Rawat Jalan');
          $('#detail-rawat-jalan').modal('show');

          $('#nomorRegistrasiTxt').text(getObj.id_number); $('#keluhanTxt').text(getObj.complaint); $('#namaPendaftarTxt').text(getObj.registrant);
          $('#nomorPasienTxt').text(getObj.id_number_patient); $('#jenisHewanTxt').text(getObj.pet_category);
          $('#namaHewanTxt').text(getObj.pet_name); $('#jenisKelaminTxt').text(getObj.pet_gender);
          $('#usiaHewanTahunTxt').text(`${getObj.pet_year_age} Tahun`); $('#usiaHewanBulanTxt').text(`${getObj.pet_month_age} Bulan`);
          $('#namaPemilikTxt').text(getObj.owner_name); $('#alamatPemilikTxt').text(getObj.owner_address);
          $('#nomorHpPemilikTxt').text(getObj.owner_phone_number);
				});
			
				$('.openTerima').click(function() {
					getId = $(this).val();
					const getObj = data.find(x => x.id == getId);
					if (getObj.acceptance_status != 1) {
						modalState = 'delete';

						$('#modal-confirmation .modal-title').text('Peringatan');
						$('#modal-confirmation .box-body').text('Anda yakin ingin menghapus data ini?');
						$('#modal-confirmation').modal('show');
					}
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

  function refreshForm() {
    $('#nomorRegistrasiTxt').text(''); $('#keluhanTxt').text('');  $('#namaPendaftarTxt').text('');
    $('#nomorPasienTxt').text(''); $('#jenisHewanTxt').text('');
    $('#namaHewanTxt').text(''); $('#jenisKelaminTxt').text('');
    $('#usiaHewanTahunTxt').text(''); $('#usiaHewanBulanTxt').text('');
    $('#namaPemilikTxt').text(''); $('#alamatPemilikTxt').text('');
    $('#nomorHpPemilikTxt').text('');
    
    $('#alasanErr1').text(''); isValidAlasan = true;
    $('#beErr').empty(); isBeErr = false;
	}

});