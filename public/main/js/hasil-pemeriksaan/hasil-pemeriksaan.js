$(document).ready(function() {

  let optCabang = '';

  let paramUrlSetup = {
		orderby:'',
		column: '',
    keyword: '',
    branchId: ''
  };

  if (role.toLowerCase() == 'resepsionis') {
		window.location.href = $('.baseUrl').val() + `/unauthorized`;	
	} else {
    if (role.toLowerCase() != 'admin') {
      $('#filterCabang').hide();
    } else {
      loadCabang();
      $('#filterCabang').select2({ placeholder: 'Cabang', allowClear: true });
    }

		loadHasilPemeriksaan();
	}
  
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

		loadHasilPemeriksaan();
  });

  $('.openFormAdd').click(function() {
    window.location.href = $('.baseUrl').val() + `/hasil-pemeriksaan/tambah`;		
		// modalState = 'add';

		// refreshText(); refreshForm();
		// formConfigure();
  });

  function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadHasilPemeriksaan();
	}

	function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadHasilPemeriksaan();
  }

  $('#submitConfirm').click(function() {
    // process delete
    $.ajax({
      url     : $('.baseUrl').val() + '/api/hasil-pemeriksaan',
      headers : { 'Authorization': `Bearer ${token}` },
      type    : 'DELETE',
      data	  : { id: getId },
      beforeSend: function() { $('#loading-screen').show(); },
      success: function(data) {
        $('#modal-confirmation .modal-title').text('Peringatan');
        $('#modal-confirmation').modal('toggle');

        $("#msg-box .modal-body").text('Berhasil menghapus data');
        $('#msg-box').modal('show');

        loadHasilPemeriksaan();

      }, complete: function() { $('#loading-screen').hide(); }
      , error: function(err) {
        if (err.status == 401) {
          localStorage.removeItem('vet-clinic');
          location.href = $('.baseUrl').val() + '/masuk';
        }
      }
    });
  });


  function refreshForm() {
    $('#selectedJasa').val(null); selectedListJasa = [];
    $('#selectedBarang').val(null); selectedListBarang = [];

    $('#selectedPasien').val(null);
    $('#pasienErr1').text(''); isValidSelectedPasien = true;

    $('#anamnesa').val(null);
    $('#anamnesaErr1').text(''); isValidAnamnesa = true;

    $('#sign').val(null);
    $('#signErr1').text(''); isValidSign = true;

    $('#diagnosa').val(null);
    $('#diagnosaErr1').text(''); isValidDiagnosa = true;

    $('#descriptionCondPasien').val(null);
    $('#descriptionCondPasienErr1').text(''); isValidDiagnosa = true;

    $('input[name="radioRawatInap"]').prop('checked', false);
    $('#rawatInapErr1').text(''); isValidRadioRawatInap = true;

    $('input[name="radioStatusPemeriksa"]').prop('checked', false);
    $('#statusPemeriksaErr1').text(''); isValidRadioStatusPemeriksa = true;

    $('#nomorPasienDetailTxt').text('-'); $('#nomorRegistrasiDetailTxt').text('-');
    $('#jenisHewanDetailTxt').text('-'); $('#namaHewanDetailTxt').text('-'); 
    $('#jenisKelaminDetailTxt').text('-'); $('#nomorHpPemilikDetailTxt').text('-');
    $('#usiaHewanTahunDetailTxt').text('-'); $('#usiaHewanBulanDetailTxt').text('-');
    $('#namaPemilikDetailTxt').text('-'); $('#alamatPemilikDetailTxt').text('-');
    $('#keluhanDetailTxt').text('-'); $('#namaPendaftarDetailTxt').text('-');
    $('#anamnesaDetailTxt').text('-'); $('#diagnosaDetailTxt').text('-');
    $('#signDetailTxt').text('-');

    getId = null; getPatienRegistrationId = null;
    $('#beErr').empty(); isBeErr = false;
  }

  function loadHasilPemeriksaan() {
    getId = null; modalState = '';

    $.ajax({
			url     : $('.baseUrl').val() + '/api/hasil-pemeriksaan',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword, branch_id: paramUrlSetup.branchId },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listHasilPemeriksaan = '';
				$('#list-hasil-pemeriksaan tr').remove();

				$.each(data, function(idx, v) {
					listHasilPemeriksaan += `<tr>`
						+ `<td>${++idx}</td>`
						+ `<td>${v.registration_number}</td>`
						+ `<td>${v.patient_number}</td>`
            + `<td>${v.pet_category}</td>`
            + `<td>${v.pet_name}</td>`
						+ `<td>${v.complaint}</td>`
            + `<td>${generateBedge(v.status_finish)}</td>`
            + `<td>${(v.status_outpatient_inpatient == 1) ? 'Rawat Inap' : 'Rawat Jalan'}</td>`
						+ `<td>${v.created_by}</td>`
						+ `<td>${v.created_at}</td>`
            + `<td>
                <button type="button" class="btn btn-info openDetail" ${v.status_finish == 0 ? 'disabled' : ''} value=${v.id} title="Detail"><i class="fa fa-eye" aria-hidden="true"></i></button>
								<button type="button" class="btn btn-warning openFormEdit" ${v.status_finish == 1 ? 'disabled' : ''} value=${v.id}><i class="fa fa-pencil" aria-hidden="true"></i></button>
								<button type="button" class="btn btn-danger openFormDelete" ${v.status_finish == 1 ? 'disabled' : ''} value=${v.id}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
							</td>`
						+ `</tr>`;
				});
				$('#list-hasil-pemeriksaan').append(listHasilPemeriksaan);

				function generateBedge(status) {
					let bedge = '';
					switch (status) {
						case 1:
							bedge = '<span class="label label-success">Selesai</span>';
							break;
						default:
							bedge = '<span class="label label-warning">Belum</span>';
							break;
					}
					return bedge;
        }
        
        $('.openDetail').click(function() {
          const getObj = data.find(x => x.id == $(this).val());
					if (getObj.status_finish != 0) {
            $.ajax({
              url     : $('.baseUrl').val() + '/api/hasil-pemeriksaan/detail',
              headers : { 'Authorization': `Bearer ${token}` },
              type    : 'GET',
              data	  : { id: $(this).val() },
              beforeSend: function() { $('#loading-screen').show(); },
              success: function(data) {
                const getData = data;

                $('.modal-title').text('Detail Hasil Pemeriksaan');
                $('#nomorRegistrasiDetailTxt').text(getData.registration.registration_number); $('#nomorPasienDetailTxt').text(getData.registration.patient_number); 
                $('#jenisHewanDetailTxt').text(getData.registration.pet_category); $('#namaHewanDetailTxt').text(getData.registration.pet_name); 
                $('#jenisKelaminDetailTxt').text(getData.registration.pet_gender); 
                $('#usiaHewanTahunDetailTxt').text(`${getData.registration.pet_year_age} Tahun`); $('#usiaHewanBulanDetailTxt').text(`${getData.registration.pet_month_age} Bulan`);
                $('#namaPemilikDetailTxt').text(getData.registration.owner_name); $('#alamatPemilikDetailTxt').text(getData.registration.owner_address);
                $('#nomorHpPemilikDetailTxt').text(getData.registration.owner_phone_number);
                $('#keluhanDetailTxt').text(getData.registration.complaint); $('#namaPendaftarDetailTxt').text(getData.registration.registrant);
                $('#rawatInapDetailTxt').text(getData.status_outpatient_inpatient ? 'Ya' : 'Tidak');
                $('#statusPemeriksaanDetailTxt').text(getData.status_finish ? 'Selesai' : 'Belum');
                $('#anamnesaDetailTxt').text(getData.anamnesa); $('#diagnosaDetailTxt').text(getData.diagnosa);
                $('#signDetailTxt').text(getData.sign);

                let rowListJasa = ''; let no1 = 1;
                $('#detail-list-jasa tr').remove();
                  getData.services.forEach((lj, idx) => {
                    rowListJasa += `<tr>`
                      + `<td>${no1}</td>`
                      + `<td>${lj.created_at}</td>`
                      + `<td>${lj.created_by}</td>`
                      + `<td>${lj.category_name}</td>`
                      + `<td>${lj.service_name}</td>`
                      + `<td>${typeof(lj.price_overall) == 'number'  ? lj.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
                      + `</tr>`;
                      ++no1;
                  });
                  $('#detail-list-jasa').append(rowListJasa);

                  let rowListBarang = ''; let no2 = 1;
                $('#detail-list-barang tr').remove();
                  getData.item.forEach((lj, idx) => {
                    rowListBarang += `<tr>`
                      + `<td>${no2}</td>`
                      + `<td>${lj.created_at}</td>`
                      + `<td>${lj.created_by}</td>`
                      + `<td>${lj.item_name}</td>`
                      + `<td>${lj.category_name}</td>`
                      + `<td>${lj.unit_name}</td>`
                      + `<td>${lj.quantity}</td>`
                      + `<td>${typeof(lj.selling_price) == 'number'  ? lj.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
                      + `<td>${typeof(lj.price_overall) == 'number'  ? lj.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
                      + `</tr>`;
                      ++no2;
                  });
                  $('#detail-list-barang').append(rowListBarang);


                if (getData.status_outpatient_inpatient) {

                  let rowListDescription = '';
                  let no = 1;

                  $('#detail-list-inpatient tr').remove();
                  getData.inpatient.forEach((inp, idx) => {
                    rowListDescription += `<tr>`
                      + `<td>${no}</td>`
                      + `<td>${inp.created_at}</td>`
                      + `<td>${inp.created_by}</td>`
                      + `<td>${inp.description}</td>`
                      + `</tr>`;
                      ++no;
                  });
                  $('#detail-list-inpatient').append(rowListDescription);

                  $('#table-list-inpatient').show();
                } else {
                  $('#table-list-inpatient').hide();
                }

                $('#detail-hasil-pemeriksaan').modal('show');

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

				$('.openFormEdit').click(function() {
          const getObj = data.find(x => x.id == $(this).val());
					if (getObj.status_finish != 1) {
            window.location.href = $('.baseUrl').val() + `/hasil-pemeriksaan/edit/${$(this).val()}`;
          }
				});
			
				$('.openFormDelete').click(function() {
					getId = $(this).val();
					const getObj = data.find(x => x.id == getId);
					if (getObj.status_finish != 1) {
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
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
	}

});