$(document).ready(function() {
  let optPasien = '';
  let optCabang = '';
  let isValidSelectedPasien = false;
  let listPasien = [];
  let selectedListJasa = [{
    id: 1,
    price_service_id: 1, 
    category_name: 'Tindakan 2', service_name: 'rawat inap',
    selling_price: 120000,
    quantity: 2, price_overall: 240000,
    created_at: '13 Feb 2021', created_by: 'budi saputri'
  }, {
    id: 2,
    price_service_id: 2, 
    category_name: 'Tindakan 1', service_name: 'rawat jalan',
    selling_price: 100000,
    quantity: 3, price_overall: 300000,
    created_at: '13 Feb 2021', created_by: 'budi saputri'
  }];
  let selectedListBarang = [{
    category_name: "Git", created_at: "13 Feb 2021",
    created_by: "budi saputri", detail_item_patients_id: 2,
    item_name: "Loperamide", list_of_item_id: 3,
    price_item_id: 3, price_overall: 600000,
    quantity: 3, selling_price: 200000, unit_name: "Strip"
  }, {
    category_name: "Git", created_at: "13 Feb 2021",
    created_by: "budi saputri", detail_item_patients_id: 1,
    item_name: "Kaotin", list_of_item_id: 2,
    price_item_id: 2, price_overall: 400000,
    quantity: 2, selling_price: 200000, unit_name: "Botol"
  }];
  let listTagihanJasa = [];
  let listTagihanBarang = [];
  let calculationPay = [];

  let getId = null;
  let getCalculatedTagihan = null;
  let formState = '';

  let isBeErr = false;
  let paramUrlSetup = {
		orderby:'',
		column: '',
    branchId: ''
  };

  // loadPembayaran();
  loadPasien();

  $('#filterCabang').select2({ placeholder: 'Cabang', allowClear: true });
  $('#selectedPasien').select2();

  $('#filterCabang').on('select2:select', function () { onFilterCabang($(this).val()); });
  $('#filterCabang').on("select2:unselect", function () { onFilterCabang($(this).val()); });

  $('#pembayaran-app').show();
  $('#tambah-pembayaran-app').hide();

  $('.openFormAdd').click(function() {
    $('#pembayaran-app').hide();
    $('#tambah-pembayaran-app').show();
    formState = 'add';
    $('#totalBayarTxt').text('-');
    processAppendListSelectedJasa();
    processAppendListSelectedBarang();
    refreshText(); refreshForm();
		// formConfigure();
  });

  $('.btn-back-to-list .text').click(function() {
    $('#pembayaran-app').show();
    $('#tambah-pembayaran-app').hide();
    formState = '';
    refreshText(); refreshForm();
		// formConfigure();
    // loadPembayaran();
  });

  $('#btnSubmitPembayaran').click(function() {
    console.log('calculationPay', calculationPay);
    console.log('getCalculatedTagihan', getCalculatedTagihan);
  });

  function onFilterCabang(value) {
    paramUrlSetup.branchId = value;
		loadPembayaran();
  }

  function formConfigure() {
		$('#btnSubmitPembayaran').attr('disabled', true);
  }

  function processAppendListSelectedJasa() {
    let rowSelectedListJasa = '';
    let no = 1;
    $('#list-selected-jasa tr').remove();

    selectedListJasa.forEach((lj, idx) => {
      rowSelectedListJasa += `<tr>`
        + `<td>${no}</td>`
        + `<td>${lj.created_at}</td>`
        + `<td>${lj.created_by}</td>`
        + `<td>${lj.category_name}</td>`
        + `<td>${lj.service_name}</td>`
        + `<td>${lj.quantity}</td>`
        + `<td>${typeof(lj.selling_price) == 'number' ? lj.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `<td>${typeof(lj.price_overall) == 'number' ? lj.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `<td><input type="checkbox" index=${idx} class="isBayarJasa"/></td>`
        + `</tr>`;
        ++no;
    });
    $('#list-selected-jasa').append(rowSelectedListJasa);

    $('.isBayarJasa').click(function() {
      const idx = $(this).attr('index');
      const getDetailJasa = selectedListJasa[idx];
      // console.log('getDetailJasa', getDetailJasa);

      if (this.checked) {
        listTagihanJasa.push(getDetailJasa);
        calculationPay.push({ id: getDetailJasa.id, type: 'jasa', price: getDetailJasa.price_overall });
      } else {
        const getIdxTagihanJasa = listTagihanJasa.findIndex(i => i.id == getDetailJasa.id);
        const getIdxCalculation = calculationPay.findIndex(i => (i.type == 'jasa' && i.id == getDetailJasa.id));

        listTagihanJasa.splice(getIdxTagihanJasa, 1);
        calculationPay.splice(getIdxCalculation, 1);        
      }

      processAppendListTagihanJasa();
      processCalculationTagihan();
    });
  }

  function processAppendListTagihanJasa() {
    let rowListTagihanJasa = '';
    let no = 1;
    $('#list-tagihan-jasa tr').remove();

    if (listTagihanJasa.length) {
      listTagihanJasa.forEach((lj) => {
        rowListTagihanJasa += `<tr>`
          + `<td>${no}</td>`
          + `<td>${lj.created_at}</td>`
          + `<td>${lj.created_by}</td>`
          + `<td>${lj.category_name}</td>`
          + `<td>${lj.service_name}</td>`
          + `<td>${lj.quantity}</td>`
          + `<td>${typeof(lj.selling_price) == 'number' ? lj.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
          + `<td>${typeof(lj.price_overall) == 'number' ? lj.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
          + `</tr>`;
          ++no;
      });
    } else { rowListTagihanJasa += `<tr><td colspan="8">No data.</td></tr>` }
    $('#list-tagihan-jasa').append(rowListTagihanJasa);
  }

  function processAppendListSelectedBarang() {
    let rowSelectedListBarang = '';
    let no = 1;

    $('#list-selected-barang tr').remove();
    selectedListBarang.forEach((lb, idx) => {
      rowSelectedListBarang += `<tr>`
        + `<td>${no}</td>`
        + `<td>${lb.created_at}</td>`
        + `<td>${lb.created_by}</td>`
        + `<td>${lb.item_name}</td>`
        + `<td>${lb.category_name}</td>`
        + `<td>${lb.unit_name}</td>`
        + `<td>${lb.quantity}</td>`
        + `<td>${typeof(lb.selling_price) == 'number' ? lb.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `<td>${typeof(lb.price_overall) == 'number' ? lb.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `<td><input type="checkbox" index=${idx} class="isBayarBarang"/></td>`
        + `</tr>`;
        ++no;
    });
    $('#list-selected-barang').append(rowSelectedListBarang);
    
    $('.isBayarBarang').click(function() {
      const idx = $(this).attr('index');
      const getDetailBarang = selectedListBarang[idx];
      // console.log('getDetailBarang', getDetailBarang);

      if (this.checked) {
        listTagihanBarang.push(getDetailBarang);
        calculationPay.push({ id: getDetailBarang.list_of_item_id, type: 'barang', price: getDetailBarang.price_overall });
      } else {
        const getIdxTagihanBarang = listTagihanJasa.findIndex(i => i.list_of_item_id == getDetailBarang.list_of_item_id);
        const getIdxCalculation = calculationPay.findIndex(i => (i.type == 'barang' && i.id == getDetailBarang.list_of_item_id));

        listTagihanBarang.splice(getIdxTagihanBarang, 1);
        calculationPay.splice(getIdxCalculation, 1);
      }

      processAppendListTagihanBarang();
      processCalculationTagihan();
    });

  }

  function processAppendListTagihanBarang() {
    let rowListTagihanBarang = '';
    let no = 1;
    $('#list-tagihan-barang tr').remove();

    if (listTagihanBarang.length) { 
      listTagihanBarang.forEach((lb) => {
        rowListTagihanBarang += `<tr>`
          + `<td>${no}</td>`
          + `<td>${lb.created_at}</td>`
          + `<td>${lb.created_by}</td>`
          + `<td>${lb.item_name}</td>`
          + `<td>${lb.category_name}</td>`
          + `<td>${lb.unit_name}</td>`
          + `<td>${lb.quantity}</td>`
          + `<td>${typeof(lb.selling_price) == 'number' ? lb.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
          + `<td>${typeof(lb.price_overall) == 'number' ? lb.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
          + `</tr>`;
          ++no;
      });
    } else { rowListTagihanBarang += `<tr><td colspan="9">No data.</td></tr>` }
    $('#list-tagihan-barang').append(rowListTagihanBarang);
  }

  function processCalculationTagihan() {
    let total = 0;

    calculationPay.forEach(calc => total += calc.price );

    getCalculatedTagihan = total;
    let totalText = `Rp. ${total.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.')},00`;
    $('#totalBayarTxt').text(totalText);
  }

  function loadPembayaran() {
    getId = null;

    $.ajax({
			url     : $('.baseUrl').val() + '/api/pembayaran',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, branch_id: paramUrlSetup.branchId },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
				let listPembayaran = '';
				$('#list-pembayaran tr').remove();

				$.each(data, function(idx, v) {
					listPembayaran += `<tr>`
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
				$('#list-pembayaran').append(listPembayaran);

        $('.openDetail').click(function() {

        });

				$('.openFormEdit').click(function() {
					
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

  function loadPasien() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/pembayaran/pasien',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
        optPasien += `<option value=''>Pilih Pasien</option>`
        listPasien = data;
        console.log('listPasien', listPasien);
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

  function refreshText() {
    // $('#nomorPasienTxt').text('-'); $('#jenisHewanTxt').text('-');
		// $('#namaHewanTxt').text('-'); $('#jenisKelaminTxt').text('-');
		// $('#usiaHewanTahunTxt').text('- Tahun'); $('#usiaHewanBulanTxt').text('- Bulan');
		// $('#namaPemilikTxt').text('-'); $('#alamatPemilikTxt').text('-');
    // $('#nomorHpPemilikTxt').text('-'); $('#nomorRegistrasiTxt').text('-');
    // $('#keluhanTxt').text('-'); $('#namaPendaftarTxt').text('-'); 
  }

  function refreshForm() {

  }

});
