$(document).ready(function() {

  let optPasien = '';
  let optJasa = '';
  let optBarang = '';
  let isValidSelectedPasien = false;
  let listPasien = [];
  let listJasa = [];
  let listBarang = [];
  let selectedListJasa = [];
  let selectedListBarang = [];

  let getId = null;
  let modalState = '';
  
  let isBeErr = false;
  let paramUrlSetup = {
		orderby:'',
		column: '',
		keyword: ''
  };
  
  loadHasilPemeriksaan();

  loadPasien();
  
  loadJasa();

  loadBarang();

  $('.openFormAdd').click(function() {
		modalState = 'add';
    $('.modal-title').text('Tambah Hasil Pemeriksaan');
    $('.table-list-jasa').hide(); $('.table-list-barang').hide();
		refreshText(); refreshForm();
		formConfigure();
  });

  $('#btnSubmitHasilPemeriksaan').click(function() {
    console.log('selectedListJasa', selectedListJasa);
    console.log('selectedListBarang', selectedListBarang);
  });
  
  $('#selectedPasien').on('select2:select', function (e) {
    const getObj = listPasien.find(x => x.id == $(this).val());
    console.log('getObj', getObj);
		if (getObj) {
			$('#nomorPasienTxt').text(getObj.id_member); $('#jenisHewanTxt').text(getObj.pet_category);
			$('#namaHewanTxt').text(getObj.pet_name); $('#jenisKelaminTxt').text(getObj.pet_gender);
			$('#usiaHewanTahunTxt').text(`${getObj.pet_year_age} Tahun`); $('#usiaHewanBulanTxt').text(`${getObj.pet_month_age} Bulan`);
			$('#namaPemilikTxt').text(getObj.owner_name); $('#alamatPemilikTxt').text(getObj.owner_address);
      $('#nomorHpPemilikTxt').text(getObj.owner_phone_number);
      $('#nomorRegistrasiTxt').text('-');
      $('#keluhanTxt').text('-'); $('#namaPendaftarTxt').text('-'); 

		} else { refreshText(); }

		validationForm();
  });

  $('#selectedJasa').on('select2:select', function (e) {
    console.log('select jasa', $(this).val());
    processSelectedJasa($(this).val());
  });

  $('#selectedJasa').on('select2:unselect', function(e) {
    console.log('unselect jasa', $(this).val());
    processSelectedJasa($(this).val());
  });

  $('#selectedBarang').on('select2:select', function (e) {
    console.log('select barang', $(this).val());
    processSelectedBarang($(this).val());
  });

  $('#selectedBarang').on('select2:unselect', function (e) {
    console.log('select barang', $(this).val());
    processSelectedBarang($(this).val());
  });
  
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
    $('#selectedBarang').select2({ placeholder: 'Nama Barang - Kategori Barang', allowClear: true });

    $('#modal-hasil-pemeriksaan').modal('show');
		// $('#btnSubmitHasilPemeriksaan').attr('disabled', true);
  }

  function processAppendListSelectedJasa() {
    let rowSelectedListJasa = '';
    $('#list-selected-jasa tr').remove();
    let no = 1;
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

  function processAppendListSelectedBarang() {
    let rowSelectedListBarang = '';
    $('#list-selected-barang tr').remove();
    let no = 1;
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

  function processSelectedBarang(getIds) {
    selectedListBarang = [];

    if (getIds.length) {
      getIds.forEach(id => {
        const getObj = listBarang.find(x => x.id == parseInt(id));
        selectedListBarang.push({ 
          item_id: getObj.id, 
          category_name: getObj.category_name, 
          item_name: getObj.item_name, 
          unit_name: getObj.unit_name,
          selling_price: getObj.selling_price,
          quantity: null, price_overall: null
        });
      });

      processAppendListSelectedBarang();      
      $('.table-list-barang').show();
    } else {
      $('.table-list-barang').hide();
    }
  }

  function loadHasilPemeriksaan() {
    getId = null; modalState = '';

    $.ajax({
			url     : $('.baseUrl').val() + '/api/hasil-pemeriksaan',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listHasilPemeriksaan = '';
				$('#list-hasil-pemeriksaan tr').remove();
        console.log('data', data);
				// $.each(data, function(idx, v) {
				// 	listHasilPemeriksaan += `<tr>`
				// 		+ `<td>${++idx}</td>`
				// 		+ `<td>${v.id_number}</td>`
				// 		+ `<td>${v.id_number_patient}</td>`
        //     + `<td>${v.pet_name}</td>`
        //     + `<td>${v.complaint}</td>`
        //     + `<td>${v.registrant}</td>`
				// 		+ `<td>${v.username_doctor}</td>`     
				// 		+ `<td>${generateBedge(v.acceptance_status)}</td>`
				// 		+ `<td>${v.created_by}</td>`
				// 		+ `<td>${v.created_at}</td>`
				// 		+ `<td>
				// 				<button type="button" class="btn btn-warning openFormEdit" ${v.acceptance_status == 1 ? 'disabled' : ''} value=${v.id}><i class="fa fa-pencil" aria-hidden="true"></i></button>
				// 				<button type="button" class="btn btn-danger openFormDelete" ${v.acceptance_status == 1 ? 'disabled' : ''} value=${v.id}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
				// 			</td>`
				// 		+ `</tr>`;
				// });
				$('#list-hasil-pemeriksaan').append(listHasilPemeriksaan);

				function generateBedge(status) {
					let bedge = '';
					switch (status) {
						case 1:
							bedge = '<span class="label label-success">Diterima</span>';
							break;
						case 2:
							bedge = '<span class="label label-danger">Ditolak</span>';
							break;
						default:
							bedge = '<span class="label label-warning">Menunggu Konfirmasi</span>';
							break;
					}
					return bedge;
				}

				$('.openFormEdit').click(function() {
					const getObj = data.find(x => x.id == $(this).val());
					if (getObj.acceptance_status != 1) {
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
						optPasien += `<option value=${listPasien[i].id}>${listPasien[i].pet_name} - ${listPasien[i].branch_name}</option>`;
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
        console.log('data Barang', data);
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

});