Dropzone.autoDiscover = false;
let arrayKelompokObat = [];
let arrayKelompokObatDelete = [];
let formState = '';
let isValidSelectedPasien = false;
let isValidAnamnesa = false;
let isValidSign = false;
let isValidDiagnosa = false;
let isValidRadioRawatInap = false;
let isValidRadioStatusPemeriksa = false;
let isValidFotoKondisiPasien = true;
let customErr1 = false;
let isBeErr = false;
let getCheckupResultId = null;
let tempFile = [];

$(document).ready(function() {
  
  let optPasien = '';
  let optJasa = '';
  
  let listPasien = [];
  let listJasa = [];
  let selectedListJasa = [];
  let deletedUpdateListJasa = [];

  let getId = null;
  let getPatienRegistrationId = null;
  let dropzone = null;  

  const url = window.location.pathname;
  const stuff = url.split('/');
  const lastUrl = stuff[stuff.length-1];

  if (role.toLowerCase() == 'resepsionis') {
		window.location.href = $('.baseUrl').val() + `/unauthorized`;	
	} else {
    formConfigure();
    loadPasien();
    loadJasa();
    // loadDropzone();
    loadKelompokObat(); loadBarang();
    refreshText();
    if (lastUrl == 'tambah') {
      formState = 'add';
      $('#tab-foto-pasien').hide();
      loadFormAdd();
    } else {
      formState = 'edit';
      $('#tab-foto-pasien').show();
      loadFormEdit();
    }
  }

  $('.btn-back-to-list .text, #btnKembali').click(function() {
    window.location.href = $('.baseUrl').val() + '/hasil-pemeriksaan';
  });

  $('#btnSubmitHasilPemeriksaan').click(function() {
    const getCheckedStatusPemeriksa = $("input[name='radioStatusPemeriksa']:checked").val();

    if (parseInt(getCheckedStatusPemeriksa)) {
      $('#modal-confirmation .modal-title').text('Peringatan');
      $('#modal-confirmation .box-body').text(`Anda yakin ingin menyelesaikan Hasil Pemeriksaan ini? Jika menyelesaikan Hasil Pemeriksaan ini anda
        tidak dapat mengubah data ini kembali`);
      $('#modal-confirmation').modal('show');
    } else {
      if (formState === 'add') {
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

  $('input:radio[name="radioRawatInap"]').change(function (e) {
    if (this.checked) {
      if (parseInt(this.value)) {
        $('.form-deskripsi-kondisi-pasien').show();
      } else {
        $('.form-deskripsi-kondisi-pasien').hide();
      }
    }
    validationForm();
  });

  $('input:radio[name="radioStatusPemeriksa"]').change(function (e) { validationForm(); });

  $('#upload-image-1').change(function() {
    const file = this.files[0];

    if(file) {
      $('.img-preview-1').show(); $('#icon-plus-upload-1').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload .img-preview-1').attr("src", this.result);
      };

      reader.readAsDataURL(file);
    } else {
      $('.img-preview-1').hide(); $('#icon-plus-upload-1').show();
    }
  });

  $('#box-1').mouseenter(function(event) {
    let getImg = $('#upload-image-1').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'block';
    }
  });
  $('#box-1').mouseleave(function(event) {
    let getImg = $('#upload-image-1').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'none';
    }
  });

  $('#btn-trash-upload-image-1').click(function(event) {
    // $('#image_file')[0].files[0]
    $('#upload-image-1').val('');
    $('#icon-plus-upload-1').show();
    $('.box-image-upload .img-preview-1').attr('src', null);
    $('.box-image-upload .img-preview-1').hide();
    $('#btn-trash-upload-image-1').hide();
  });


  $('#upload-image-2').change(function() {
    const file = this.files[0];

    if(file) {
      $('.img-preview-2').show(); $('#icon-plus-upload-2').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload .img-preview-2').attr("src", this.result);
      };

      reader.readAsDataURL(file);
    } else {
      $('.img-preview-2').hide(); $('#icon-plus-upload-2').show();
    }
  });

  $('#box-2').mouseenter(function(event) {
    let getImg = $('#upload-image-2').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'block';
    }
  });
  $('#box-2').mouseleave(function(event) {
    let getImg = $('#upload-image-2').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'none';
    }
  });

  $('#btn-trash-upload-image-2').click(function(event) {
    // $('#image_file')[0].files[0]
    $('#upload-image-2').val('');
    $('#icon-plus-upload-2').show();
    $('.box-image-upload .img-preview-2').attr('src', null);
    $('.box-image-upload .img-preview-2').hide();
    $('#btn-trash-upload-image-2').hide();
  });


  $('#upload-image-3').change(function() {
    const file = this.files[0];

    if(file) {
      $('.img-preview-3').show(); $('#icon-plus-upload-3').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload .img-preview-3').attr("src", this.result);
      };

      reader.readAsDataURL(file);
    } else {
      $('.img-preview-3').hide(); $('#icon-plus-upload-3').show();
    }
  });

  $('#box-3').mouseenter(function(event) {
    let getImg = $('#upload-image-3').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'block';
    }
  });
  $('#box-3').mouseleave(function(event) {
    let getImg = $('#upload-image-3').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'none';
    }
  });

  $('#btn-trash-upload-image-3').click(function(event) {
    // $('#image_file')[0].files[0]
    $('#upload-image-3').val('');
    $('#icon-plus-upload-3').show();
    $('.box-image-upload .img-preview-3').attr('src', null);
    $('.box-image-upload .img-preview-3').hide();
    $('#btn-trash-upload-image-3').hide();
  });



  $('#upload-image-4').change(function() {
    const file = this.files[0];

    if(file) {
      $('.img-preview-4').show(); $('#icon-plus-upload-4').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload .img-preview-4').attr("src", this.result);
      };

      reader.readAsDataURL(file);
    } else {
      $('.img-preview-4').hide(); $('#icon-plus-upload-4').show();
    }
  });

  $('#box-4').mouseenter(function(event) {
    let getImg = $('#upload-image-4').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'block';
    }
  });
  $('#box-4').mouseleave(function(event) {
    let getImg = $('#upload-image-4').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'none';
    }
  });

  $('#btn-trash-upload-image-4').click(function(event) {
    // $('#image_file')[0].files[0]
    $('#upload-image-4').val('');
    $('#icon-plus-upload-4').show();
    $('.box-image-upload .img-preview-4').attr('src', null);
    $('.box-image-upload .img-preview-4').hide();
    $('#btn-trash-upload-image-4').hide();
  });


  $('#upload-image-5').change(function() {
    const file = this.files[0];

    if(file) {
      $('.img-preview-5').show(); $('#icon-plus-upload-5').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload .img-preview-5').attr("src", this.result);
      };

      reader.readAsDataURL(file);
    } else {
      $('.img-preview-5').hide(); $('#icon-plus-upload-5').show();
    }
  });

  $('#box-5').mouseenter(function(event) {
    let getImg = $('#upload-image-5').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'block';
    }
  });
  $('#box-5').mouseleave(function(event) {
    let getImg = $('#upload-image-5').prop('files')[0];
    if (getImg) {
      event.currentTarget.lastElementChild.style.display = 'none';
    }
  });

  $('#btn-trash-upload-image-5').click(function(event) {
    // $('#image_file')[0].files[0]
    $('#upload-image-5').val('');
    $('#icon-plus-upload-5').show();
    $('.box-image-upload .img-preview-5').attr('src', null);
    $('.box-image-upload .img-preview-5').hide();
    $('#btn-trash-upload-image-5').hide();
  });



  function loadDropzone() {
    dropzone = new Dropzone('#fotoKondisiPasien', {
      url: $('.baseUrl').val() + '/api/hasil-pemeriksaan/upload-gambar',
      uploadMultiple: true,
      addRemoveLinks: true,
      acceptedFiles: 'image/jpeg,image/png',
      autoProcessQueue: false,
      parallelUploads: 5,
      maxFiles: 5,
      maxFilesize: 1, // MB
      dictMaxFilesExceeded: 'You cannot upload anymore, max is {{maxFiles}} file',
      dictFileSizeUnits: 'File size cannot greater than 1MB',
      paramName: 'filenames',
      headers: { Authorization: `Bearer ${token}` },
      init: function() {
        this.on('sending', function (file, xhr, formData) {
          formData.append('check_up_result_id', getCheckupResultId);
        });
        this.on('error', function(file, response) {
          console.log('eror upload meessage', response, file);
          // detectValidPhoto(false);
          // validationForm();
        });
        this.on('success', function(file, response) {
          console.log('success upload meessage', response, file);
          $("#msg-box .modal-body").text(`Berhasil ${(formState === 'add') ? 'Menambah': 'Merubah'} Data`);
          $('#msg-box').modal('show');
          setTimeout(() => {
            window.location.href = $('.baseUrl').val() + '/hasil-pemeriksaan';
          }, 1000);
        });
        this.on('addedfile', function(file) {
          console.log('add file', file);
          
          tempFile.push({name: file.name, type: file.type});
          validationPhoto();
        });
        this.on('removedfile', function(file) {
          console.log('removedfile', file);
          
          let getIdxFile = tempFile.findIndex(x => x.name === file.name);
          if (getIdxFile > -1) { tempFile.splice(getIdxFile, 1); }

          validationPhoto();
        });
      
      }
    });
  }

  $('#testUpload').click(function() {
    // dropzone.processQueue();
    let tempFile = [];
    for(let i = 1 ; i <= 5 ; i++) {
      const getFile = $(`#upload-image-${i}`)[0].files[0];
      tempFile.push(getFile);
    }
    console.log('tempFile', tempFile.filter(x => x));
  });

  $('#submitConfirm').click(function() {
    if (formState === 'add') {
      processSaved();
      $('#modal-confirmation .modal-title').text('Peringatan');
      $('#modal-confirmation').modal('toggle');
    } else if(formState === 'edit') {
      // process Edit
      processEdit();
      $('#modal-confirmation .modal-title').text('Peringatan');
      $('#modal-confirmation').modal('toggle');
    }
  });

  function validationPhoto() {
    const getLengthPhoto = tempFile.length;
    let isError = false;

    if (getLengthPhoto > 5) {
      detectValidPhoto(false); isError = true;
    }

    if (!isError) { detectValidPhoto(true); }
    validationForm();
  }

  function detectValidPhoto(isValid) {
    if(!isValid) {
      $('#fotoKondErr1').text('Foto tidak boleh lebih dari 5'); 
      isValidFotoKondisiPasien = false;
    } else {
      $('#fotoKondErr1').text(''); 
      isValidFotoKondisiPasien = true;
    }
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

    arrayKelompokObat.forEach(ko => {
      let newObj = {medicine_group_id: null, list_of_medicine: []};
      newObj.medicine_group_id = ko.kelompokObatId;

      ko.selectedListBarang.forEach(lb => {
        newObj.list_of_medicine.push({price_item_id: lb.price_item_id, quantity: lb.quantity, price_overall: lb.price_overall});
      });

      finalSelectedBarang.push(newObj);
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
        console.log('resp hasil pemeriksaan', resp);
        getCheckupResultId = resp.id
        // dropzone.processQueue();

        let tempFile = [];
        const fdUpload = new FormData();
        fdUpload.append('check_up_result_id', resp.id);

        for(let i = 1 ; i <= 5 ; i++) {
          const getFile = $(`#upload-image-${i}`)[0].files[0];
          fdUpload.append('filenames[]', getFile);
          tempFile.push(getFile);
        }
        console.log('tempFile', tempFile.filter(x => x));
        if (tempFile.length) {
          $.ajax({
            url : $('.baseUrl').val() + '/api/hasil-pemeriksaan/upload-gambar',
            type: 'POST',
            dataType: 'JSON',
            headers: { 'Authorization': `Bearer ${token}` },
            data: fdUpload, contentType: false, cache: false,
            processData: false,
            beforeSend: function() { $('#loading-screen').show(); },
            success: function(resp) {
              $("#msg-box .modal-body").text(`Berhasil Menambah Data`);
              $('#msg-box').modal('show');
              // setTimeout(() => {
              //   window.location.href = $('.baseUrl').val() + '/hasil-pemeriksaan';
              // }, 1000);
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
    
    deletedUpdateListJasa.forEach(ulj => {
      finalSelectedJasa.push({ id: ulj.id, price_service_id: ulj.price_service_id, quantity: ulj.quantity, price_overall: ulj.price_overall, status: 'del' });
    });

    selectedListJasa.forEach(lj => {
      finalSelectedJasa.push({ id: lj.id, price_service_id: lj.price_service_id, quantity: lj.quantity, price_overall: lj.price_overall, status: '' });
    });

    arrayKelompokObatDelete.forEach(ko => {
      let newObj = {medicine_group_id: null, list_of_medicine: [], status: 'del'};
      newObj.medicine_group_id = ko.kelompokObatId;

      ko.deletedUpdateListBarang.forEach(dul => {
        newObj.list_of_medicine.push({id: dul.id, price_item_id: dul.price_item_id, quantity: dul.quantity, price_overall: dul.price_overall, status: 'del'});
      });

      ko.selectedListBarang.forEach(lb => {
        newObj.list_of_medicine.push({id: lb.id, price_item_id: lb.price_item_id, quantity: lb.quantity, price_overall: lb.price_overall, status: ''});
      });

      finalSelectedBarang.push(newObj);
    });

    arrayKelompokObat.forEach(ko => {
      let newObj = {medicine_group_id: null, list_of_medicine: [], status: ''};
      newObj.medicine_group_id = ko.kelompokObatId;

      ko.deletedUpdateListBarang.forEach(dul => {
        newObj.list_of_medicine.push({id: dul.id, price_item_id: dul.price_item_id, quantity: dul.quantity, price_overall: dul.price_overall, status: 'del'});
      });

      ko.selectedListBarang.forEach(lb => {
        newObj.list_of_medicine.push({id: lb.id, price_item_id: lb.price_item_id, quantity: lb.quantity, price_overall: lb.price_overall, status: ''});
      });

      finalSelectedBarang.push(newObj);
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
        console.log('resp edit hasil pemeriksaan', data);
        getCheckupResultId = getId;
        dropzone.processQueue();

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

      deletedUpdateListJasa.push(selectedListJasa[getIdx]);
      selectedListJasa.splice(getIdx, 1);
      selectedListJasa.forEach(lj => { getIds.push(lj.price_service_id); });
      if (!selectedListJasa.length) { $('.table-list-jasa').hide(); }

      $('#selectedJasa').val(getIds); $('#selectedJasa').trigger('change');
      processAppendListSelectedJasa();
    }
  }

  function processAppendListSelectedJasa() {
    let rowSelectedListJasa = '';
    let no = 1;
    $('#list-selected-jasa tr').remove();

    selectedListJasa.forEach((lj, idx) => {
      rowSelectedListJasa += `<tr>`
        + `<td>${no}</td>`
        + `${(formState) == 'edit' ? '<td>'+(lj.created_at ? lj.created_at : '-')+'</td>' : '' }`
        + `${(formState) == 'edit' ? '<td>'+(lj.created_by ? lj.created_by : '-')+'</td>' : '' }`
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

  function loadFormAdd() {
    $('.title-form-hasil-pemeriksaan').text('Tambah Hasil Pemeriksaan');
    $('.form-cari-pasien').show();
    $('.table-list-jasa').hide(); $('.table-list-barang').hide();
    $('.tgl-edit').hide(); $('.dibuat-edit').hide();
    $('.form-deskripsi-kondisi-pasien').hide(); $('.table-deskripsi-kondisi-pasien').hide();
    $('input[name="radioRawatInap"]').prop('disabled', false);
  }

  function loadFormEdit() {
    $('.table-deskripsi-kondisi-pasien').show();
    $('.title-form-hasil-pemeriksaan').text('Ubah Hasil Pemeriksaan');
    $('.tgl-edit').show(); $('.dibuat-edit').show();
    $('.form-cari-pasien').hide(); $('input[name="radioRawatInap"]').prop('disabled', true);

    $.ajax({
      url     : $('.baseUrl').val() + '/api/hasil-pemeriksaan/detail',
      headers : { 'Authorization': `Bearer ${token}` },
      type    : 'GET',
      data	  : { id: lastUrl },
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

        if (getData.item.length) {
          getData.item.forEach(item => {
            let newObj = { id: null, kelompokObatId: null, selectDropdownBarang: [], selectedListBarang: [], deletedUpdateListBarang: [] };
            newObj.id = item.medicine_group_id; // untuk membedakan data lama dan baru
            newObj.kelompokObatId = item.medicine_group_id;
            item.list_of_medicine.forEach(lom => {
              newObj.selectDropdownBarang.push(lom.price_item_id);
              newObj.selectedListBarang.push({
                id: lom.detail_item_patients_id,
                price_item_id: lom.price_item_id,
                category_name: lom.category_name,
                item_name: lom.item_name, unit_name: lom.unit_name,
                selling_price: lom.selling_price,
                quantity: lom.quantity, price_overall: lom.price_overall,
                created_at: lom.created_at, created_by: lom.created_by
              });
            });
            arrayKelompokObat.push(newObj);
          });
        }

        drawListKelompokObat();

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
          $('.form-deskripsi-kondisi-pasien').show();
          $('.table-deskripsi-kondisi-pasien').show();
        } else {
          $('.form-deskripsi-kondisi-pasien').hide();
          $('.table-deskripsi-kondisi-pasien').hide();
        }

        $(`input[name=radioStatusPemeriksa][value=${getData.status_finish}]`).prop('checked', true);

        let rowFotoKondPasien = '';
        $('#section-foto-kondisi-pasien .img-style').remove();
        if (getData.images.length) {
          getData.images.forEach(img => {
            rowFotoKondPasien += `<div class="img-style"><img src=${$('.baseUrl').val()+img.image}></div>`;
          });
        } else {
          rowFotoKondPasien = 'Tidak ada foto.';
        }
        $('#section-foto-kondisi-pasien').append(rowFotoKondPasien);

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

  function formConfigure() {
    $('#selectedPasien').select2();
    $('#selectedJasa').select2({ placeholder: 'Jenis Pelayanan - Kategori Jasa', allowClear: true });

		$('#btnSubmitHasilPemeriksaan').attr('disabled', true);
  }

  function refreshText() {
    $('#nomorPasienTxt').text('-'); $('#jenisHewanTxt').text('-');
		$('#namaHewanTxt').text('-'); $('#jenisKelaminTxt').text('-');
		$('#usiaHewanTahunTxt').text('- Tahun'); $('#usiaHewanBulanTxt').text('- Bulan');
		$('#namaPemilikTxt').text('-'); $('#alamatPemilikTxt').text('-');
    $('#nomorHpPemilikTxt').text('-'); $('#nomorRegistrasiTxt').text('-');
    $('#keluhanTxt').text('-'); $('#namaPendaftarTxt').text('-'); 
  }

  function loadKelompokObat() {
		$.ajax({
			url     : $('.baseUrl').val() + '/api/pembagian-harga-kelompok-obat',
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

function validationForm() {
  if (formState === 'add') {
    if (!$('#selectedPasien').val()) {
      $('#pasienErr1').text('Pasien harus di isi'); isValidSelectedPasien = false;
    } else { 
      $('#pasienErr1').text(''); isValidSelectedPasien = true;
    }
  } else { isValidSelectedPasien = true; } 

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

  if (!$("input[name='radioStatusPemeriksa']:checked").val()) {
    $('#statusPemeriksaErr1').text('Status Pemeriksa harus di isi'); isValidRadioStatusPemeriksa = false;
  } else {
    $('#statusPemeriksaErr1').text(''); isValidRadioStatusPemeriksa = true;
  }

  let isValidKelompokObat = true;
  for (let i = 0; i < arrayKelompokObat.length; i++) {
    if (!arrayKelompokObat[i].kelompokObatId || !arrayKelompokObat[i].selectedListBarang.length) {
      $('#customErr1').text('Terdapat Kelompok Obat atau Daftar Barang yang belum diisi, harap dicek kembali!'); 
      customErr1 = true; isValidKelompokObat = false;
      break;
    }
  }

  if(isValidKelompokObat) {
    $('#customErr1').empty(); customErr1 = false;
  }
  
  $('#beErr').empty(); isBeErr = false;

  if (!isValidSelectedPasien || !isValidAnamnesa || !isValidSign || !isValidDiagnosa 
    || !isValidRadioRawatInap || !isValidRadioStatusPemeriksa
    || !isValidFotoKondisiPasien || isBeErr || customErr1) {
    $('#btnSubmitHasilPemeriksaan').attr('disabled', true);
  } else {
    $('#btnSubmitHasilPemeriksaan').attr('disabled', false);
  }
}
