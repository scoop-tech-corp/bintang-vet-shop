$(document).ready(function() {

  let optPasien = '';
  let optJasa = '';
  let optBarang = '';
  let optCabang = '';
  let isValidSelectedPasien = false;
  let listPasien = [];

  let listJasa = [];
  let listBarang = [];

  let selectedListJasa = [];
  let selectedListBarang = [];

  let selectedListJasaRawatInap = [];
  let selectedListBarangRawatInap = [];

  let getId = null;
  let modalState = '';
  
  let isBeErr = false;
  let paramUrlSetup = {
		orderby:'',
		column: '',
    keyword: '',
    branchId: ''
  };
  
  loadHasilPemeriksaan();

  loadPasien();
  
  loadJasa();

  loadBarang();

  loadCabang();

  $('#filterCabang').select2({ placeholder: 'Cabang', allowClear: true });

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
		modalState = 'add';
    $('.modal-title').text('Tambah Hasil Pemeriksaan');
    $('.table-list-jasa').hide(); $('.table-list-barang').hide();
    $('.table-list-jasa-rawat-inap').hide(); $('.table-list-barang-rawat-inap').hide();
		refreshText(); refreshForm();
		formConfigure();
  });

  $('#btnSubmitHasilPemeriksaan').click(function() {
    console.log('selectedListJasa', selectedListJasa);
    console.log('selectedListBarang', selectedListBarang);

    console.log('selectedListJasa', selectedListJasaRawatInap);
    console.log('selectedListBarang', selectedListBarangRawatInap);

    const getCheckedStatusPemeriksa = $("input[name='radioStatusPemeriksa']:checked").val();

    if (parseInt(getCheckedStatusPemeriksa)) {
      $('#modal-confirmation .modal-title').text('Peringatan');
      $('#modal-confirmation .box-body').text(`Anda yakin ingin menyelesaikan Hasil Pemeriksaan ini? Jika menyelesaikan Hasil Pemeriksaan ini anda
        tidak dapat mengubah data ini kembali`);
      $('#modal-confirmation').modal('show');
    } else {

      if (modalState === 'Add') {

      } else {
        // process Edit

      }
    }

  });
  
  $('#selectedPasien').on('select2:select', function (e) {
    const getObj = listPasien.find(x => x.registration_id == $(this).val());
    console.log('getObj', getObj);
		if (getObj) {
			$('#nomorPasienTxt').text(getObj.id_number_patient); $('#jenisHewanTxt').text(getObj.pet_category);
			$('#namaHewanTxt').text(getObj.pet_name); $('#jenisKelaminTxt').text(getObj.pet_gender);
			$('#usiaHewanTahunTxt').text(`${getObj.pet_year_age} Tahun`); $('#usiaHewanBulanTxt').text(`${getObj.pet_month_age} Bulan`);
			$('#namaPemilikTxt').text(getObj.owner_name); $('#alamatPemilikTxt').text(getObj.owner_address);
      $('#nomorHpPemilikTxt').text(getObj.owner_phone_number); $('#nomorRegistrasiTxt').text(getObj.registration_number);
      $('#keluhanTxt').text(getObj.complaint); $('#namaPendaftarTxt').text(getObj.registrant);

		} else { refreshText(); }

		validationForm();
  });

  $('#selectedJasa').on('select2:select', function (e) { processSelectedJasa($(this).val()); });
  $('#selectedJasa').on('select2:unselect', function(e) { processSelectedJasa($(this).val()); });

  $('#selectedJasaRawatInap').on('select2:select', function (e) { processSelectedJasaRawatInap($(this).val()); });
  $('#selectedJasaRawatInap').on('select2:unselect', function(e) { processSelectedJasaRawatInap($(this).val()); });

  $('#selectedBarang').on('select2:select', function (e) { processSelectedBarang(e.params.data.id, e.params.data.selected); });
  $('#selectedBarang').on('select2:unselect', function (e) { processSelectedBarang(e.params.data.id, e.params.data.selected); });

  $('#selectedBarangRawatInap').on('select2:select', function (e) { processSelectedBarangRawatInap(e.params.data.id, e.params.data.selected); });
  $('#selectedBarangRawatInap').on('select2:unselect', function (e) { processSelectedBarangRawatInap(e.params.data.id, e.params.data.selected); });

  $('input:radio[name="radioRawatInap"]').change(function (e) {
    if (this.checked) {
      if (parseInt(this.value)) {
        $('.form-jasa-barang-rawat-inap').show();
      } else {
        $('.form-jasa-barang-rawat-inap').hide();
      }
    }
  });

  
  $('#submitConfirm').click(function() {
    if (modalState === 'add') {

    } else {
      // process Edit
    }
  });

  // $('input:radio[name="radioStatusPemeriksa"]').change(function (e) {
  //   if (this.checked) {
  //     if (parseInt(this.value)) {
  //       $('#modal-confirmation .modal-title').text('Peringatan');
  //       $('#modal-confirmation .box-body').text(`Anda yakin ingin menyelesaikan Hasil Pemeriksaan ini? Jika menyelesaikan Hasil Pemeriksaan ini anda
  //         tidak dapat mengubah data ini kembali`);
  //       $('#modal-confirmation').modal('show');
  //     }
  //   }
  // });

  // $('#notSubmitConfirm').click(function() { $("input[name=radioStatusPemeriksa][value=0]").prop('checked', true);  });

  function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadHasilPemeriksaan();
	}

	function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadHasilPemeriksaan();
  }
  
  function validationForm() {
    if (!$('#selectedPasien').val()) {
			$('#pasienErr1').text('Pasien harus di isi'); isValidSelectedPasien = false;
		} else { 
			$('#pasienErr1').text(''); isValidSelectedPasien = true;
		}
  }

  function refreshText() {
    $('#nomorPasienTxt').text('-'); $('#jenisHewanTxt').text('-');
		$('#namaHewanTxt').text('-'); $('#jenisKelaminTxt').text('-');
		$('#usiaHewanTahunTxt').text('- Tahun'); $('#usiaHewanBulanTxt').text('- Bulan');
		$('#namaPemilikTxt').text('-'); $('#alamatPemilikTxt').text('-');
    $('#nomorHpPemilikTxt').text('-'); $('#nomorRegistrasiTxt').text('-');
    $('#keluhanTxt').text('-'); $('#namaPendaftarTxt').text('-'); 
  }

  function refreshForm() {
    $('#selectedPasien').val(null); 
    $('#selectedJasa').val(null); selectedListJasa = [];
    $('#selectedBarang').val(null); selectedListBarang = [];
    $('#pasienErr1').text(''); isValidSelectedPasien = true;

    $('#beErr').empty(); isBeErr = false;
  }

  function formConfigure() {
    $('#selectedPasien').select2();
    $('#selectedJasa').select2({ placeholder: 'Jenis Pelayanan - Kategori Jasa', allowClear: true });
    $('#selectedJasaRawatInap').select2({ placeholder: 'Jenis Pelayanan - Kategori Jasa', allowClear: true });
    $('#selectedBarang').select2({ placeholder: 'Nama Barang - Kategori Barang', allowClear: true });
    $('#selectedBarangRawatInap').select2({ placeholder: 'Nama Barang - Kategori Barang', allowClear: true });

    $('#modal-hasil-pemeriksaan').modal('show');

    $('.form-jasa-barang-rawat-inap').hide();
		// $('#btnSubmitHasilPemeriksaan').attr('disabled', true);
  }

  function processAppendListSelectedJasa() {
    let rowSelectedListJasa = '';
    let no = 1;
    $('#list-selected-jasa tr').remove();

    selectedListJasa.forEach((lj, idx) => {
      rowSelectedListJasa += `<tr>`
        + `<td>${no}</td>`
        + `<td>${lj.category_name}</td>`
        + `<td>${lj.service_name}</td>`
        + `<td>${lj.selling_price}</td>`
        + `<td>
            <button type="button" class="btn btn-danger btnRemoveSelectedListJasa" value=${idx}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>`;
        ++no;
    });
    $('#list-selected-jasa').append(rowSelectedListJasa);

    $('.btnRemoveSelectedListJasa').click(function() {
      const getIds = [];
      selectedListJasa.splice($(this).val(), 1);
      selectedListJasa.forEach(lj => { getIds.push(lj.id); });
      if (!selectedListJasa.length) { $('.table-list-jasa').hide(); }

      $('#selectedJasa').val(getIds); $('#selectedJasa').trigger('change');
      processAppendListSelectedJasa();
    });
  }

  function processAppendListSelectedJasaRawatInap() {
    let rowSelectedListJasaRawatInap = '';
    let no = 1;

    $('#list-selected-jasa-rawat-inap tr').remove();
    selectedListJasaRawatInap.forEach((lj, idx) => {
      rowSelectedListJasaRawatInap += `<tr>`
        + `<td>${no}</td>`
        + `<td>${lj.category_name}</td>`
        + `<td>${lj.service_name}</td>`
        + `<td>${lj.selling_price}</td>`
        + `<td>
            <button type="button" class="btn btn-danger btnRemoveSelectedListJasaRawatInap" value=${idx}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>`;
        ++no;
    });
    $('#list-selected-jasa-rawat-inap').append(rowSelectedListJasaRawatInap);

    $('.btnRemoveSelectedListJasaRawatInap').click(function() {
      const getIds = [];
      selectedListJasaRawatInap.splice($(this).val(), 1);
      selectedListJasaRawatInap.forEach(lj => { getIds.push(lj.id); });
      if (!selectedListJasaRawatInap.length) { $('.table-list-jasa-rawat-inap').hide(); }

      $('#selectedJasaRawatInap').val(getIds); $('#selectedJasaRawatInap').trigger('change');
      processAppendListSelectedJasaRawatInap();
    });
  }

  function processAppendListSelectedBarang() {
    let rowSelectedListBarang = '';
    let no = 1;

    $('#list-selected-barang tr').remove();
    selectedListBarang.forEach((lb, idx) => {
      rowSelectedListBarang += `<tr>`
        + `<td>${no}</td>`
        + `<td>${lb.item_name}</td>`
        + `<td>${lb.category_name}</td>`
        + `<td>${lb.unit_name}</td>`
        + `<td><input type="number" min="0" class="qty-input-barang" index=${idx} value=${lb.quantity}></td>`
        + `<td>${lb.selling_price}</td>`
        + `<td><span id="totalBarang-${idx}">${lb.price_overall ? lb.price_overall : ''}</span></td>`
        + `<td>
            <button type="button" class="btn btn-danger btnRemoveSelectedListBarang" value=${idx}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>`;
        ++no;
    });
    $('#list-selected-barang').append(rowSelectedListBarang);

    $('.qty-input-barang').on('input', function(e) {
      const idx          = $(this).attr('index');
      const value        = parseFloat($(this).val());
      const sellingPrice = parseFloat(selectedListBarang[idx].selling_price);
      let totalBarang    = value * sellingPrice;

      selectedListBarang[idx].quantity = value;
      selectedListBarang[idx].price_overall = totalBarang;
      $('#totalBarang-'+idx).text(totalBarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
    });

    $('.btnRemoveSelectedListBarang').click(function() {
      const getIds = [];
      selectedListBarang.splice($(this).val(), 1);
      selectedListBarang.forEach(lb => { getIds.push(lb.item_id); });
      if (!selectedListBarang.length) { $('.table-list-barang').hide(); }

      $('#selectedBarang').val(getIds); $('#selectedBarang').trigger('change');
      processAppendListSelectedBarang();
    });
  }

  function processAppendListSelectedBarangRawatInap() {
    let rowSelectedListBarangRawatInap = '';
    let no = 1;

    $('#list-selected-barang-rawat-inap tr').remove();
    selectedListBarangRawatInap.forEach((lb, idx) => {
      rowSelectedListBarangRawatInap += `<tr>`
        + `<td>${no}</td>`
        + `<td>${lb.item_name}</td>`
        + `<td>${lb.category_name}</td>`
        + `<td>${lb.unit_name}</td>`
        + `<td><input type="number" min="0" class="qty-input-barang-rawat-inap" index=${idx} value=${lb.quantity}></td>`
        + `<td>${lb.selling_price}</td>`
        + `<td><span id="totalBarangRawatInap-${idx}">${lb.price_overall ? lb.price_overall : ''}</span></td>`
        + `<td>
            <button type="button" class="btn btn-danger btnRemoveSelectedListBarangRawatInap" value=${idx}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>`;
        ++no;
    });
    $('#list-selected-barang-rawat-inap').append(rowSelectedListBarangRawatInap);

    $('.qty-input-barang-rawat-inap').on('input', function(e) {
      const idx          = $(this).attr('index');
      const value        = parseFloat($(this).val());
      const sellingPrice = parseFloat(selectedListBarangRawatInap[idx].selling_price);
      let totalBarang    = value * sellingPrice;

      selectedListBarangRawatInap[idx].quantity = value;
      selectedListBarangRawatInap[idx].price_overall = totalBarang;
      $('#totalBarangRawatInap-'+idx).text(totalBarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ','));
    });

    $('.btnRemoveSelectedListBarangRawatInap').click(function() {
      const getIds = [];
      selectedListBarangRawatInap.splice($(this).val(), 1);
      selectedListBarangRawatInap.forEach(lb => { getIds.push(lb.item_id); });
      if (!selectedListBarangRawatInap.length) { $('.table-list-barang-rawat-inap').hide(); }

      $('#selectedBarangRawatInap').val(getIds); $('#selectedBarangRawatInap').trigger('change');
      processAppendListSelectedBarangRawatInap();
    });
  }

  function processSelectedJasa(getIds) {
    selectedListJasa = [];

    if (getIds.length) {
      getIds.forEach(id => {
        const getObj = listJasa.find(x => x.id == parseInt(id));
        selectedListJasa.push(getObj);
      });

      processAppendListSelectedJasa();      
      $('.table-list-jasa').show();
    } else {
      $('.table-list-jasa').hide();
    }
  }

  function processSelectedJasaRawatInap(getIds) {
    selectedListJasaRawatInap = [];

    if (getIds.length) {
      getIds.forEach(id => {
        const getObj = listJasa.find(x => x.id == parseInt(id));
        selectedListJasaRawatInap.push(getObj);
      });

      processAppendListSelectedJasaRawatInap();      
      $('.table-list-jasa-rawat-inap').show();
    } else {
      $('.table-list-jasa-rawat-inap').hide();
    }
  }

  function processSelectedBarang(selectedId, selected) {

    if (selected) {
      const getObj = listBarang.find(x => x.id == parseInt(selectedId));
      selectedListBarang.push({ 
        item_id: getObj.id, 
        category_name: getObj.category_name, 
        item_name: getObj.item_name, 
        unit_name: getObj.unit_name,
        selling_price: getObj.selling_price,
        quantity: null, price_overall: null
      });
      processAppendListSelectedBarang();      
      $('.table-list-barang').show();
    } else {

      const getIds = [];
      const getIdx = selectedListBarang.findIndex(i => i.item_id === selectedId);
      selectedListBarang.splice(getIdx, 1);
      selectedListBarang.forEach(lb => { getIds.push(lb.item_id); });
      if (!selectedListBarang.length) { $('.table-list-barang').hide(); }

      $('#selectedBarang').val(getIds); $('#selectedBarang').trigger('change');
      processAppendListSelectedBarang();
    }
  }

  function processSelectedBarangRawatInap(selectedId, selected) {
    if (selected) {
      const getObj = listBarang.find(x => x.id == parseInt(selectedId));
      selectedListBarangRawatInap.push({ 
        item_id: getObj.id, 
        category_name: getObj.category_name, 
        item_name: getObj.item_name, 
        unit_name: getObj.unit_name,
        selling_price: getObj.selling_price,
        quantity: null, price_overall: null
      });
      processAppendListSelectedBarangRawatInap();      
      $('.table-list-barang-rawat-inap').show();
    } else {

      const getIds = [];
      const getIdx = selectedListBarangRawatInap.findIndex(i => i.item_id === selectedId);
      selectedListBarangRawatInap.splice(getIdx, 1);
      selectedListBarangRawatInap.forEach(lb => { getIds.push(lb.item_id); });
      if (!selectedListBarangRawatInap.length) { $('.table-list-barang-rawat-inap').hide(); }

      $('#selectedBarangRawatInap').val(getIds); $('#selectedBarangRawatInap').trigger('change');
      processAppendListSelectedBarangRawatInap();
    }
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
        console.log('data', data);
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
                console.log('detail', data);
                const getData = data[0];

                $('.modal-title').text('Detail Hasil Pemeriksaan');

                $('#nomorPasienTxt').text('-'); $('#jenisHewanTxt').text(getData.pet_category);
                $('#namaHewanTxt').text(getData.pet_name); $('#jenisKelaminTxt').text(getData.pet_gender);
                $('#usiaHewanTahunTxt').text(`${getData.pet_year_age} Tahun`); $('#usiaHewanBulanTxt').text(`${getData.pet_month_age} Bulan`);
                $('#namaPemilikTxt').text(getData.owner_name); $('#alamatPemilikTxt').text(getData.owner_address);
                $('#nomorHpPemilikTxt').text(getData.owner_phone_number); $('#nomorRegistrasiTxt').text(getData.registration.id_number);
                $('#keluhanTxt').text(getData.complaint); $('#namaPendaftarTxt').text(getData.registrant);
                $('#anamnesaTxt').text(getData.anamnesa); $('#diagnosaTxt').text(getData.diagnosa);
                $('#signTxt').text(getData.sign); $('#rawatInapTxt').text(getData.status_outpatient_inpatient ? 'Ya' : 'Tidak');
                $('#statusPemeriksaanTxt').text(getData.status_finish ? 'Selesai' : 'Belum');

                if (getData.status_outpatient_inpatient) {

                  let rowSelectedListJasaRawatInap = '';
                  let no1 = 1;

                  $('#detail-selected-jasa-rawat-inap tr').remove();
                  getData.service_inpatient.forEach((lj, idx) => {
                    rowSelectedListJasaRawatInap += `<tr>`
                      + `<td>${no1}</td>`
                      + `<td>${lj.category_name}</td>`
                      + `<td>${lj.service_name}</td>`
                      + `<td>${lj.selling_price}</td>`;
                      ++no1;
                  });
                  $('#detail-selected-jasa-rawat-inap').append(rowSelectedListJasaRawatInap);


                  let rowSelectedListBarangRawatInap = '';
                  let no2 = 1;

                  $('#detail-selected-barang-rawat-inap tr').remove();
                  getData.item_inpatient.forEach((lb, idx) => {
                    rowSelectedListBarangRawatInap += `<tr>`
                      + `<td>${no2}</td>`
                      + `<td>${lb.item_name}</td>`
                      + `<td>${lb.category_name}</td>`
                      + `<td>${lb.unit_name}</td>`
                      + `<td>${lb.quantity}</td>`
                      + `<td>${lb.selling_price}</td>`
                      + `<td>${lb.price_overall ? lb.price_overall : ''}</td>`;
                      ++no2;
                  });
                  $('#detail-selected-barang-rawat-inap').append(rowSelectedListBarangRawatInap);



                  $('#table-list-jasa-rawat-inap').show();
                  $('#table-list-barang-rawat-inap').show();
                } else {
                  $('#table-list-jasa-rawat-inap').hide();
                  $('#table-list-barang-rawat-inap').hide();
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
						modalState = 'edit';

						$('.modal-title').text('Edit Pendaftaran Pasien');
						refreshForm();

						formConfigure();
						getId = getObj.id;
						$('#namaPendaftar').val(getObj.registrant); $('#keluhan').val(getObj.complaint);
						$('#selectedPasien').val(getObj.patient_id); $('#selectedPasien').trigger('change');
						$('#selectedDokter').val(getObj.user_doctor_id); $('#selectedDokter').trigger('change');
						$('#nomorPasienTxt').text(getObj.id_member); $('#jenisHewanTxt').text(getObj.pet_category);
						$('#namaHewanTxt').text(getObj.pet_name); $('#jenisKelaminTxt').text(getObj.pet_gender);
						$('#usiaHewanTahunTxt').text(`${getObj.pet_year_age} Tahun`); $('#usiaHewanBulanTxt').text(`${getObj.pet_month_age} Bulan`);
						$('#namaPemilikTxt').text(getObj.owner_name); $('#alamatPemilikTxt').text(getObj.owner_address);
						$('#nomorHpPemilikTxt').text(getObj.owner_phone_number);
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

  function loadPasien() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/pasien/status-terima',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
        optPasien += `<option value=''>Pilih Pasien</option>`
        listPasien = data;
				if (listPasien.length) {
					for (let i = 0 ; i < listPasien.length ; i++) {
						optPasien += `<option value=${listPasien[i].registration_id}>${listPasien[i].pet_name} - ${listPasien[i].branch_name}</option>`;
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

  function loadJasa() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/pembagian-harga-jasa',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
        // console.log('data JASA', data);
        listJasa = data; listJasaRawatInap = data;
				if (listJasa.length) {
					for (let i = 0 ; i < listJasa.length ; i++) {
						optJasa += `<option value=${listJasa[i].id}>${listJasa[i].category_name} - ${listJasa[i].service_name}</option>`;
					}
        }
				$('#selectedJasa').append(optJasa); $('#selectedJasaRawatInap').append(optJasa);
			}, complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-clinic');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
  }

  function loadBarang() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/pembagian-harga-barang',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
        console.log('data Barang', data);
        listBarang = data;
				if (listBarang.length) {
					for (let i = 0 ; i < listBarang.length ; i++) {
						optBarang += `<option value=${listBarang[i].id}>${listBarang[i].item_name} - ${listBarang[i].category_name}</option>`;
					}
        }
				$('#selectedBarang').append(optBarang); $('#selectedBarangRawatInap').append(optBarang);
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