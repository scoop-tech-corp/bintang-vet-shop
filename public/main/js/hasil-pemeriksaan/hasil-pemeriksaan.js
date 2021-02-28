$(document).ready(function() {

  let optPasien = '';
  let optJasa = '';
  let optBarang = '';
  let optCabang = '';
  let isValidSelectedPasien = false;
  let isValidAnamnesa = false;
  let isValidSign = false;
  let isValidDiagnosa = false;
  let isValidRadioRawatInap = false;
  let isValidDescCondPasien = false;
  let isValidRadioStatusPemeriksa = false;
  let listPasien = [];

  let listJasa = [];
  let listBarang = [];

  let selectedListJasa = [];
  let selectedListBarang = [];

  let deletedUpdateListJasa = [];
  let deletedUpdateListBarang = [];

  let getId = null;
  let modalState = '';
  
  let isBeErr = false;
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
    loadPasien();
    loadJasa();
    loadBarang();
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
		modalState = 'add';
    $('.modal-title').text('Tambah Hasil Pemeriksaan');
    $('.form-cari-pasien').show();
    $('.table-list-jasa').hide(); $('.table-list-barang').hide();
    $('.tgl-edit').hide(); $('.dibuat-edit').hide();
    $('.form-deskripsi-kondisi-pasien').hide(); $('.table-deskripsi-kondisi-pasien').hide();
    $('input[name="radioRawatInap"]').prop('disabled', false);

		refreshText(); refreshForm();
		formConfigure();
  });

  $('#btnSubmitHasilPemeriksaan').click(function() {
    const getCheckedStatusPemeriksa = $("input[name='radioStatusPemeriksa']:checked").val();

    if (parseInt(getCheckedStatusPemeriksa)) {
      $('#modal-confirmation .modal-title').text('Peringatan');
      $('#modal-confirmation .box-body').text(`Anda yakin ingin menyelesaikan Hasil Pemeriksaan ini? Jika menyelesaikan Hasil Pemeriksaan ini anda
        tidak dapat mengubah data ini kembali`);
      $('#modal-confirmation').modal('show');
    } else {

      if (modalState === 'add') {
        processSaved();
      } else {
        // process Edit
        processEdit();
      }
    }

  });
  
  $('#selectedPasien').on('select2:select', function (e) {
    const getObj = listPasien.find(x => x.registration_id == $(this).val());
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

  $('#anamnesa').keyup(function () { validationForm(); });
  $('#sign').keyup(function () { validationForm(); });
  $('#diagnosa').keyup(function () { validationForm(); });
  $('#descriptionCondPasien').keyup(function () { validationForm(); });

  $('#selectedJasa').on('select2:select', function (e) { processSelectedJasa(e.params.data.id, e.params.data.selected); validationForm(); });
  $('#selectedJasa').on('select2:unselect', function(e) { processSelectedJasa(e.params.data.id, e.params.data.selected); validationForm(); });

  $('#selectedBarang').on('select2:select', function (e) { processSelectedBarang(e.params.data.id, e.params.data.selected); validationForm(); });
  $('#selectedBarang').on('select2:unselect', function (e) { processSelectedBarang(e.params.data.id, e.params.data.selected); validationForm(); });

  $('input:radio[name="radioRawatInap"]').change(function (e) {
    if (this.checked) {
      if (parseInt(this.value)) {
        $('.form-deskripsi-kondisi-pasien').show();
        if (!$('#descriptionCondPasien').val()) {
          $('#descriptionCondPasienErr1').text('Deskripsi Kondisi Pasien harus di isi'); isValidDescCondPasien = false;
        }
      } else {
        $('.form-deskripsi-kondisi-pasien').hide();
      }
    }
    validationForm();
  });

  $('input:radio[name="radioStatusPemeriksa"]').change(function (e) { validationForm(); });

  
  $('#submitConfirm').click(function() {
    if (modalState === 'add') {
      processSaved();
      $('#modal-confirmation .modal-title').text('Peringatan');
      $('#modal-confirmation').modal('toggle');
    } else if(modalState === 'edit') {
      // process Edit
      processEdit();
      $('#modal-confirmation .modal-title').text('Peringatan');
      $('#modal-confirmation').modal('toggle');
    } else {
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
    }
  });

  function onSearch(keyword) {
		paramUrlSetup.keyword = keyword;
		loadHasilPemeriksaan();
	}

	function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadHasilPemeriksaan();
  }

  function processSaved() {
    const fd = new FormData();
    fd.append('patient_registration_id', $('#selectedPasien').val());
    fd.append('anamnesa', $('#anamnesa').val());
    fd.append('sign', $('#sign').val());
    fd.append('diagnosa', $('#diagnosa').val());
    fd.append('status_finish', $("input[name='radioStatusPemeriksa']:checked").val());
    fd.append('status_outpatient_inpatient', $("input[name='radioRawatInap']:checked").val());
    fd.append('inpatient', $('#descriptionCondPasien').val());

    let finalSelectedJasa = [];
    let finalSelectedBarang = [];
    
    selectedListJasa.forEach(lj => {
      finalSelectedJasa.push({ price_service_id: lj.price_service_id, quantity: lj.quantity, price_overall: lj.price_overall });
    });
    selectedListBarang.forEach(lb => {
      finalSelectedBarang.push({ price_item_id: lb.price_item_id, quantity: lb.quantity, price_overall: lb.price_overall});
    });
    fd.append('service', JSON.stringify(finalSelectedJasa));
    fd.append('item', JSON.stringify(finalSelectedBarang));

    $.ajax({
      url : $('.baseUrl').val() + '/api/hasil-pemeriksaan',
      type: 'POST',
      dataType: 'JSON',
      headers: { 'Authorization': `Bearer ${token}` },
      data: fd, contentType: false, cache: false,
      processData: false,
      beforeSend: function() { $('#loading-screen').show(); },
      success: function(resp) {

        $("#msg-box .modal-body").text('Berhasil Menambah Data');
        $('#msg-box').modal('show');

        setTimeout(() => {
          $('#modal-hasil-pemeriksaan').modal('toggle');
          refreshForm(); loadHasilPemeriksaan();
        }, 1000);
      }, complete: function() { $('#loading-screen').hide(); }
      , error: function(err) {
        if (err.status === 422) {
          let errText = ''; $('#beErr').empty(); $('#btnSubmitHasilPemeriksaan').attr('disabled', true);
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
  }

  function processEdit() {
    let finalSelectedJasa = [];
    let finalSelectedBarang = [];
    
    selectedListJasa.forEach(lj => {
      finalSelectedJasa.push({ id: lj.id, price_service_id: lj.price_service_id, quantity: lj.quantity, price_overall: lj.price_overall, status: '' });
    });
    deletedUpdateListJasa.forEach(ulj => {
      finalSelectedJasa.push({ id: ulj.id, price_service_id: ulj.price_service_id, quantity: ulj.quantity, price_overall: ulj.price_overall, status: 'del' });
    });

    selectedListBarang.forEach(lb => {
      finalSelectedBarang.push({ id: lb.id, price_item_id: lb.price_item_id, quantity: lb.quantity, price_overall: lb.price_overall, status: '' });
    });
    deletedUpdateListBarang.forEach(ulb => {
      finalSelectedBarang.push({ id: ulb.id, price_item_id: ulb.price_item_id, quantity: ulb.quantity, price_overall: ulb.price_overall, status: 'del' });
    });

    const datas = {
      id: getId,
      patient_registration_id: getPatienRegistrationId,
      anamnesa: $('#anamnesa').val(),
      sign: $('#sign').val(),
      diagnosa: $('#diagnosa').val(),
      status_finish: parseInt($("input[name='radioStatusPemeriksa']:checked").val()),
      status_outpatient_inpatient: parseInt($("input[name='radioRawatInap']:checked").val()),
      inpatient: $('#descriptionCondPasien').val(),
      service: finalSelectedJasa,
      item: finalSelectedBarang
    };

    $.ajax({
      url : $('.baseUrl').val() + '/api/hasil-pemeriksaan',
      type: 'PUT',
      dataType: 'JSON',
      headers: { 'Authorization': `Bearer ${token}` },
      data: datas,
      beforeSend: function() { $('#loading-screen').show(); },
      success: function(data) {

        $("#msg-box .modal-body").text('Berhasil Mengubah Data');
        $('#msg-box').modal('show');

        setTimeout(() => {
          $('#modal-hasil-pemeriksaan').modal('toggle');
          refreshForm(); loadHasilPemeriksaan();
        }, 1000);

      }, complete: function() { $('#loading-screen').hide(); }
      , error: function(err) {
        if (err.status === 422) {
          let errText = ''; $('#beErr').empty(); $('#btnSubmitHasilPemeriksaan').attr('disabled', true);
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
  }
  
  function validationForm() {
    if (modalState === 'add') {
      if (!$('#selectedPasien').val()) {
        $('#pasienErr1').text('Pasien harus di isi'); isValidSelectedPasien = false;
      } else { 
        $('#pasienErr1').text(''); isValidSelectedPasien = true;
      }
    }

    if (!$('#anamnesa').val()) {
			$('#anamnesaErr1').text('Anamnesa harus di isi'); isValidAnamnesa = false;
		} else { 
			$('#anamnesaErr1').text(''); isValidAnamnesa = true;
    }

    if (!$('#sign').val()) {
			$('#signErr1').text('Sign harus di isi'); isValidSign = false;
		} else {
			$('#signErr1').text(''); isValidSign = true;
    }

    if (!$('#diagnosa').val()) {
			$('#diagnosaErr1').text('Diagnosa harus di isi'); isValidDiagnosa = false;
		} else {
			$('#diagnosaErr1').text(''); isValidDiagnosa = true;
    }

    if (!$("input[name='radioRawatInap']:checked").val()) {
			$('#rawatInapErr1').text('Rawat inap harus di isi'); isValidRadioRawatInap = false;
		} else {
			$('#rawatInapErr1').text(''); isValidRadioRawatInap = true;
    }

    if(parseInt($("input[name='radioRawatInap']:checked").val())) {
      if (!$('#descriptionCondPasien').val()) {
        $('#descriptionCondPasienErr1').text('Deskripsi Kondisi Pasien harus di isi'); isValidDescCondPasien = false;
      } else {
        $('#descriptionCondPasienErr1').text(''); isValidDescCondPasien = true;
      }
    } else { isValidDescCondPasien = true; }

    if (!$("input[name='radioStatusPemeriksa']:checked").val()) {
			$('#statusPemeriksaErr1').text('Status Pemeriksa harus di isi'); isValidRadioStatusPemeriksa = false;
		} else {
			$('#statusPemeriksaErr1').text(''); isValidRadioStatusPemeriksa = true;
    }

    $('#beErr').empty(); isBeErr = false;

    if (!isValidSelectedPasien || !isValidAnamnesa || !isValidSign || !isValidDiagnosa 
      || !isValidRadioRawatInap || !isValidRadioStatusPemeriksa || !isValidDescCondPasien || isBeErr) {
      $('#btnSubmitHasilPemeriksaan').attr('disabled', true);
    } else {
      $('#btnSubmitHasilPemeriksaan').attr('disabled', false);
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

  function formConfigure() {
    $('#selectedPasien').select2();
    $('#selectedJasa').select2({ placeholder: 'Jenis Pelayanan - Kategori Jasa', allowClear: true });
    $('#selectedBarang').select2({ placeholder: 'Nama Barang - Kategori Barang', allowClear: true });

    $('#modal-hasil-pemeriksaan').modal('show');
		$('#btnSubmitHasilPemeriksaan').attr('disabled', true);
  }

  function processAppendListSelectedJasa() {
    let rowSelectedListJasa = '';
    let no = 1;
    $('#list-selected-jasa tr').remove();

    selectedListJasa.forEach((lj, idx) => {
      rowSelectedListJasa += `<tr>`
        + `<td>${no}</td>`
        + `${(modalState) == 'edit' ? '<td>'+(lj.created_at ? lj.created_at : '-')+'</td>' : '' }`
        + `${(modalState) == 'edit' ? '<td>'+(lj.created_by ? lj.created_by : '-')+'</td>' : '' }`
        + `<td>${lj.category_name}</td>`
        + `<td>${lj.service_name}</td>`
        + `<td><input type="number" min="0" class="qty-input-jasa" index=${idx} value=${lj.quantity}></td>`
        + `<td>${typeof(lj.selling_price) == 'number' ? lj.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `<td><span id="totalBarang-jasa-${idx}">${typeof(lj.price_overall) == 'number' ? lj.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</span></td>`
        + `<td>
            <button type="button" class="btn btn-danger btnRemoveSelectedListJasa" value=${idx}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>`
        + `</tr>`;
        ++no;
    });
    $('#list-selected-jasa').append(rowSelectedListJasa);

    $('.qty-input-jasa').on('input', function(e) {
      const idx          = $(this).attr('index');
      const value        = parseFloat($(this).val());
      const sellingPrice = parseFloat(selectedListJasa[idx].selling_price);
      let totalBarang    = value * sellingPrice;

      selectedListJasa[idx].quantity = value;
      selectedListJasa[idx].price_overall = totalBarang;
      validationForm();
      $('#totalBarang-jasa-'+idx).text(totalBarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });

    $('.btnRemoveSelectedListJasa').click(function() {
      const getIds = [];
      deletedUpdateListJasa.push(selectedListJasa[$(this).val()]);
      selectedListJasa.splice($(this).val(), 1);
      
      selectedListJasa.forEach(lj => { getIds.push(lj.price_service_id); });
      if (!selectedListJasa.length) { $('.table-list-jasa').hide(); }
      validationForm();

      $('#selectedJasa').val(getIds); $('#selectedJasa').trigger('change');
      processAppendListSelectedJasa();
    });
  }

  function processAppendListSelectedBarang() {
    let rowSelectedListBarang = '';
    let no = 1;

    $('#list-selected-barang tr').remove();
    selectedListBarang.forEach((lb, idx) => {
      rowSelectedListBarang += `<tr>`
        + `<td>${no}</td>`
        + `${(modalState) == 'edit' ? '<td>'+(lb.created_at ? lb.created_at : '-')+'</td>' : '' }`
        + `${(modalState) == 'edit' ? '<td>'+(lb.created_by ? lb.created_by : '-')+'</td>' : '' }`
        + `<td>${lb.item_name}</td>`
        + `<td>${lb.category_name}</td>`
        + `<td>${lb.unit_name}</td>`
        + `<td><input type="number" min="0" class="qty-input-barang" index=${idx} value=${lb.quantity}></td>`
        + `<td>${typeof(lb.selling_price) == 'number' ? lb.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `<td><span id="totalBarang-${idx}">${typeof(lb.price_overall) == 'number' ? lb.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</span></td>`
        + `<td>
            <button type="button" class="btn btn-danger btnRemoveSelectedListBarang" value=${idx}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>`
        + `</tr>`;
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
      validationForm();
      $('#totalBarang-'+idx).text(totalBarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });

    $('.btnRemoveSelectedListBarang').click(function() {
      const getIds = [];
      deletedUpdateListBarang.push(selectedListBarang[$(this).val()]);
      selectedListBarang.splice($(this).val(), 1);
      selectedListBarang.forEach(lb => { getIds.push(lb.price_item_id); });
      if (!selectedListBarang.length) { $('.table-list-barang').hide(); }
      validationForm();
      $('#selectedBarang').val(getIds); $('#selectedBarang').trigger('change');
      processAppendListSelectedBarang();
    });
  }

  function processSelectedJasa(selectedId, selected) {

    if (selected) {
      const getObj = listJasa.find(x => x.id == parseInt(selectedId));
      selectedListJasa.push({
        id: null,
        price_service_id: getObj.id, 
        category_name: getObj.category_name, 
        service_name: getObj.service_name,
        selling_price: getObj.selling_price,
        quantity: null, price_overall: null
      });
      processAppendListSelectedJasa();
      $('.table-list-jasa').show();
    } else {
      const getIds = [];
      const getIdx = selectedListJasa.findIndex(i => i.price_service_id == selectedId);
      selectedListJasa.splice(getIdx, 1);
      selectedListJasa.forEach(lj => { getIds.push(lj.price_service_id); });
      if (!selectedListJasa.length) { $('.table-list-jasa').hide(); }

      $('#selectedJasa').val(getIds); $('#selectedJasa').trigger('change');
      processAppendListSelectedJasa();
    }
  }

  function processSelectedBarang(selectedId, selected) {

    if (selected) {
      const getObj = listBarang.find(x => x.id == parseInt(selectedId));
      selectedListBarang.push({
        id: null,
        price_item_id: getObj.id, 
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
      const getIdx = selectedListBarang.findIndex(i => i.price_item_id == selectedId);
      selectedListBarang.splice(getIdx, 1);
      selectedListBarang.forEach(lb => { getIds.push(lb.item_id); });
      if (!selectedListBarang.length) { $('.table-list-barang').hide(); }

      $('#selectedBarang').val(getIds); $('#selectedBarang').trigger('change');
      processAppendListSelectedBarang();
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
						modalState = 'edit';
            $('.table-deskripsi-kondisi-pasien').show();
            $('.modal-title').text('Edit Hasil Pemeriksaan');
            $('.tgl-edit').show(); $('.dibuat-edit').show();
            $('.form-cari-pasien').hide(); $('input[name="radioRawatInap"]').prop('disabled', true);
            refreshForm();

            $.ajax({
              url     : $('.baseUrl').val() + '/api/hasil-pemeriksaan/detail',
              headers : { 'Authorization': `Bearer ${token}` },
              type    : 'GET',
              data	  : { id: $(this).val() },
              beforeSend: function() { $('#loading-screen').show(); },
              success: function(data) {
                const getData = data;

                getId = getData.id; getPatienRegistrationId = getData.patient_registration_id;
                $('#nomorRegistrasiTxt').text(getData.registration.registration_number); $('#nomorPasienTxt').text(getData.registration.patient_number); 
                $('#jenisHewanTxt').text(getData.registration.pet_category); $('#namaHewanTxt').text(getData.registration.pet_name); 
                $('#jenisKelaminTxt').text(getData.registration.pet_gender); 
                $('#usiaHewanTahunTxt').text(`${getData.registration.pet_year_age} Tahun`); $('#usiaHewanBulanTxt').text(`${getData.registration.pet_month_age} Bulan`);
                $('#namaPemilikTxt').text(getData.registration.owner_name); $('#alamatPemilikTxt').text(getData.registration.owner_address);
                $('#nomorHpPemilikTxt').text(getData.registration.owner_phone_number);
                $('#keluhanTxt').text(getData.registration.complaint); $('#namaPendaftarTxt').text(getData.registration.registrant);

                $('#anamnesa').val(getData.anamnesa); $('#diagnosa').val(getData.diagnosa);
                $('#sign').val(getData.sign);

                const getIdJasa = [];
                if (getData.services.length) {
                  getData.services.forEach(item => {
                    selectedListJasa.push({
                      id: item.detail_service_patient_id,
                      price_service_id: item.price_service_id, 
                      category_name: item.category_name, service_name: item.service_name,
                      selling_price: item.selling_price,
                      quantity: item.quantity, price_overall: item.price_overall,
                      created_at: item.created_at, created_by: item.created_by
                    });
                    getIdJasa.push(item.price_service_id);
                  });
                  processAppendListSelectedJasa();
                  $('.table-list-jasa').show();
                } else {
                  $('.table-list-jasa').hide();
                }
                $('#selectedJasa').val(getIdJasa); $('#selectedJasa').trigger('change');

                const getIdBarang = [];
                if (getData.item.length) {
                  getData.item.forEach(item => {
                    selectedListBarang.push({
                      id: item.detail_item_patients_id,
                      price_item_id: item.price_item_id,
                      category_name: item.category_name,
                      item_name: item.item_name, unit_name: item.unit_name,
                      selling_price: item.selling_price,
                      quantity: item.quantity, price_overall: item.price_overall,
                      created_at: item.created_at, created_by: item.created_by
                    });
                    getIdBarang.push(item.price_item_id);
                  });
                  processAppendListSelectedBarang();
                  $('.table-list-barang').show();
                } else {
                  $('.table-list-barang').hide();
                }
                $('#selectedBarang').val(getIdBarang); $('#selectedBarang').trigger('change');

                $(`input[name=radioRawatInap][value=${getData.status_outpatient_inpatient}]`).prop('checked', true);
                if (getData.status_outpatient_inpatient) {
                  let rowListCondPasien = '';
                  let no = 1;

                  $('#list-deskripsi-kondisi-pasien tr').remove();
                  getData.inpatient.forEach((lj, idx) => {
                    rowListCondPasien += `<tr>`
                      + `<td>${no}</td>`
                      + `<td>${lj.created_at}</td>`
                      + `<td>${lj.created_by}</td>`
                      + `<td><div style="word-break: break-word;">${lj.description}</div></td>`
                      + `</tr>`;
                      ++no;
                  });
                  $('#list-deskripsi-kondisi-pasien').append(rowListCondPasien);

                  $('.table-deskripsi-kondisi-pasien').show();
                } else {
                  $('.table-deskripsi-kondisi-pasien').hide();
                }

                $(`input[name=radioStatusPemeriksa][value=${getData.status_finish}]`).prop('checked', true);

                formConfigure();
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
        listJasa = data;
				if (listJasa.length) {
					for (let i = 0 ; i < listJasa.length ; i++) {
						optJasa += `<option value=${listJasa[i].id}>${listJasa[i].category_name} - ${listJasa[i].service_name}</option>`;
					}
        }
				$('#selectedJasa').append(optJasa);
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
        listBarang = data;
				if (listBarang.length) {
					for (let i = 0 ; i < listBarang.length ; i++) {
						optBarang += `<option value=${listBarang[i].id}>${listBarang[i].item_name} - ${listBarang[i].category_name}</option>`;
					}
        }
				$('#selectedBarang').append(optBarang);
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