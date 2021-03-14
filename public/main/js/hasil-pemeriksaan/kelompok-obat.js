$(document).ready(function() {

  let optKelompokObat = '';
  let listKelompokObat = [];
  let optBarang = '';
  let listBarang = [];
  let selectedListBarang = [];
  let deletedUpdateListBarang = [];

  if (role.toLowerCase() != 'resepsionis') {
    loadKelompokObat(); loadBarang();
  }
  
  $('#btnKelompokObat').click(function () {
    console.log('arrayKelompokObat', arrayKelompokObat);
  });

  $('#btnTambahKelompokObat').click(function() {
    arrayKelompokObat.push({ kelompokObatId: null, selectDropdownBarang: [], selectedListBarang: [], deletedUpdateListBarang: [] });
    drawListKelompokObat();
  });

  function drawListKelompokObat() {
    let rowKelompokObat = '';
    let no = 1;

    $('#locateDrawKelompokBarang .target').remove();
    arrayKelompokObat.forEach((ko, idx) => {
      let rowSelectedListBarang = appendListSelectBarang(ko.selectedListBarang, idx);

      rowKelompokObat += `<div class="target" style="margin-bottom: 30px">`
        + `<div class="label-detail-div m-b-10px">Kelompok Obat ${no} `
        + `<button type="button" class="btn btn-xs btn-danger btnRemoveKelompokObat" title="Hapus Kelompok Obat" value=${idx} style="margin-left: 5px;"><i class="fa fa-trash-o" aria-hidden="true"></i></button>`
        + `</div>`
        + `<div class="m-b-10px">`
        +  `<select class="selectedKelompokObat" class="form-control" style="width: 100%" idx=${idx}></select>`
        + `</div>`
        + `<div class="m-b-10px">`
        +  `<select class="form-control selectedBarang" id="selectedBarang-${idx}" style="width: 100%" multiple="multiple" idx=${idx}></select>`
        + `</div>`
        + `<div class="table-responsive" id="table-list-barang-${idx}" style="display: ${ko.selectedListBarang.length ? 'block': 'none'}">`
        +   `<table class="table table-striped text-nowrap">`
        +    `<thead>`
        +      `<tr>`
        +        `<th>No</th>`
        +        `<th class="dibuat-edit" style="display: ${formState == 'edit' ? 'block': 'none'}">Dibuat Oleh</th>`
        +        `<th>Nama Barang</th>`
        +        `<th>Kategori Barang</th>`
        +        `<th>Satuan Barang</th>`
        +        `<th>Jumlah</th>`
        +        `<th>Harga Satuan</th>`
        +        `<th>Harga Keseluruhan</th>`
        +        `<th>Hapus</th>`
        +      `</tr>`
        +    `</thead>`
        +    `<tbody id="list-selected-barang-${idx}">${rowSelectedListBarang}</tbody>`
        +  `</table>`
        + `</div>`
        + `</div>`;
        ++no;
    });
    $('#locateDrawKelompokBarang').append(rowKelompokObat);
    appendDropdownKelompokObat();
    appendDropdownSelectBarang();

    $('.selectedKelompokObat').on('select2:select', function (e) {
      const getIdx = parseInt(e.target.getAttribute('idx'));
      arrayKelompokObat[getIdx].kelompokObatId = parseInt(e.params.data.id);
    });

    $('.selectedBarang').on('select2:select', function (e) {
      const getIdx = parseInt(e.target.getAttribute('idx'));
      processSelectedBarang(e.params.data.id, e.params.data.selected, getIdx);
    });

    $('.selectedBarang').on('select2:unselect', function (e) { 
      const getIdx = parseInt(e.target.getAttribute('idx'));
      processSelectedBarang(e.params.data.id, e.params.data.selected, getIdx);
    });

    $('.btnRemoveKelompokObat').click(function() {
      arrayKelompokObat.splice($(this).val(), 1);
      drawListKelompokObat();
    });
  }

  function appendDropdownKelompokObat() {
    $('.selectedKelompokObat').each(function(index, obj) {
      let getValue = arrayKelompokObat[index].kelompokObatId;
      getValue = getValue !== null ? parseInt(getValue) : '';

      optKelompokObat = '';
      optKelompokObat += `<option value=''>Pilih Kategori Obat - Cabang</option>`

      if (listKelompokObat.length) {
        for (let i = 0 ; i < listKelompokObat.length ; i++) {
          optKelompokObat += `<option value=${listKelompokObat[i].id}>${listKelompokObat[i].group_name} - ${listKelompokObat[i].branch_name}</option>`;
        }
      } 
      $(this).append(optKelompokObat);
      $(this).select2().val(getValue).trigger('change');
    });
  }

  function appendDropdownSelectBarang() {
    $('.selectedBarang').each(function(index, obj) {
      let getValue = arrayKelompokObat[index].selectDropdownBarang;
      optBarang = '';
      if (listBarang.length) {
        for (let i = 0 ; i < listBarang.length ; i++) {
          optBarang += `<option value=${listBarang[i].id}>${listBarang[i].item_name} - ${listBarang[i].category_name}</option>`;
        }
      }

      $(this).append(optBarang);
      $(this).select2({ placeholder: 'Nama Barang - Kategori Barang', allowClear: true }).val(getValue).trigger('change');
    });
  }

  function appendListSelectBarang(arrSelectedListBarang, idxKelompokObat) {
    let = rowSelectedListBarang = '';
    let no = 1;
    arrSelectedListBarang.forEach((lb, idx) => {
      rowSelectedListBarang += `<tr>`
        + `<td>${no}</td>`
        + `${(formState) == 'edit' ? '<td>'+(lb.created_at ? lb.created_at : '-')+'</td>' : '' }`
        + `${(formState) == 'edit' ? '<td>'+(lb.created_by ? lb.created_by : '-')+'</td>' : '' }`
        + `<td>${lb.item_name}</td>`
        + `<td>${lb.category_name}</td>`
        + `<td>${lb.unit_name}</td>`
        + `<td><input type="number" min="0" class="qty-input-barang" index=${idx} value=${lb.quantity}></td>`
        + `<td>${typeof(lb.selling_price) == 'number' ? lb.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `<td><span id="totalBarang-${idxKelompokObat}-${idx}">${typeof(lb.price_overall) == 'number' ? lb.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</span></td>`
        + `<td>
            <button type="button" class="btn btn-danger btnRemoveSelectedListBarang" value=${idx}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>`
        + `</tr>`;
        ++no;
    });

    return rowSelectedListBarang;
  }

  function processSelectedBarang(selectedId, selected, idx) {
    if (formState == 'edit') { $('.dibuat-edit').show(); } else { $('.dibuat-edit').hide(); }

    if (selected) {
      const getObj = listBarang.find(x => x.id == parseInt(selectedId));
      arrayKelompokObat[idx].selectDropdownBarang.push(selectedId);
      arrayKelompokObat[idx].selectedListBarang.push({
        id: null,
        price_item_id: getObj.id, 
        category_name: getObj.category_name, 
        item_name: getObj.item_name, 
        unit_name: getObj.unit_name,
        selling_price: getObj.selling_price,
        quantity: null, price_overall: null
      });
      processAppendListSelectedBarang(idx);

      $('#table-list-barang-'+ idx).show();
    } else {
      const getIds = [];
      const getIdx1 = arrayKelompokObat[idx].selectedListBarang.findIndex(i => i.price_item_id == selectedId);
      const getIdx2 = arrayKelompokObat[idx].selectDropdownBarang.findIndex(i => i == selectedId);

      arrayKelompokObat[idx].selectedListBarang.splice(getIdx1, 1);
      arrayKelompokObat[idx].selectDropdownBarang.splice(getIdx2, 1);

      arrayKelompokObat[idx].selectedListBarang.forEach(lb => { getIds.push(lb.price_item_id); });
      if (!arrayKelompokObat[idx].selectedListBarang.length) { $('#table-list-barang-'+ idx).hide(); }

      processAppendListSelectedBarang(idx);
    }
  }

  function processAppendListSelectedBarang(idxKelompokObat) {
    let rowSelectedListBarang = '';
    let no = 1;

    $(`#list-selected-barang-${idxKelompokObat} tr`).remove();
    arrayKelompokObat[idxKelompokObat].selectedListBarang.forEach((lb, idx) => {
      rowSelectedListBarang += `<tr>`
        + `<td>${no}</td>`
        + `${(formState) == 'edit' ? '<td>'+(lb.created_at ? lb.created_at : '-')+'</td>' : '' }`
        + `${(formState) == 'edit' ? '<td>'+(lb.created_by ? lb.created_by : '-')+'</td>' : '' }`
        + `<td>${lb.item_name}</td>`
        + `<td>${lb.category_name}</td>`
        + `<td>${lb.unit_name}</td>`
        + `<td><input type="number" min="0" class="qty-input-barang" index=${idx} value=${lb.quantity}></td>`
        + `<td>${typeof(lb.selling_price) == 'number' ? lb.selling_price.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `<td><span id="totalBarang-${idxKelompokObat}-${idx}">${typeof(lb.price_overall) == 'number' ? lb.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</span></td>`
        + `<td>
            <button type="button" class="btn btn-danger btnRemoveSelectedListBarang" value=${idx}><i class="fa fa-trash-o" aria-hidden="true"></i></button>
          </td>`
        + `</tr>`;
        ++no;
    });
    $(`#list-selected-barang-${idxKelompokObat}`).append(rowSelectedListBarang);

    $('.qty-input-barang').on('input', function(e) {
      const idx          = $(this).attr('index');
      const value        = parseFloat($(this).val());
      const sellingPrice = parseFloat(
          arrayKelompokObat[idxKelompokObat].selectedListBarang[idx].selling_price);
      let totalBarang    = value * sellingPrice;

      arrayKelompokObat[idxKelompokObat].selectedListBarang[idx].quantity = value;
      arrayKelompokObat[idxKelompokObat].selectedListBarang[idx].price_overall = totalBarang;
      // validationForm();
      $(`#totalBarang-${idxKelompokObat}-${idx}`).text(
          totalBarang.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'));
    });

    $('.btnRemoveSelectedListBarang').click(function() {
      const getIds = [];
      arrayKelompokObat[idxKelompokObat].deletedUpdateListBarang.push(arrayKelompokObat[idxKelompokObat].selectedListBarang[$(this).val()]);
      arrayKelompokObat[idxKelompokObat].selectedListBarang.splice($(this).val(), 1);
      arrayKelompokObat[idxKelompokObat].selectedListBarang.forEach(lb => { getIds.push(lb.price_item_id); });
      if (!arrayKelompokObat[idxKelompokObat].selectedListBarang.length) { $(`#table-list-barang-${idxKelompokObat}`).hide(); }
      arrayKelompokObat[idxKelompokObat].selectDropdownBarang.splice($(this).val(), 1);

      // validationForm();
      $(`#selectedBarang-${idxKelompokObat}`).val(getIds); $(`#selectedBarang-${idxKelompokObat}`).trigger('change');
      // appendDropdownSelectBarang();
      processAppendListSelectedBarang(idxKelompokObat);
    });
  }

  function loadKelompokObat() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/kelompok-obat',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
        listKelompokObat = data;
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