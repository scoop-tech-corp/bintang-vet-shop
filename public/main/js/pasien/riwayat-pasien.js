$(document).ready(function() {

  const url = window.location.pathname;
  const stuff = url.split('/');
  const id = stuff[stuff.length-1];
  let listJasaDetail = [];
  let listBarangDetail = [];
  let listDeskripsiPasien = [];

  loadRiwayatPasien();

  function loadRiwayatPasien() {
    $.ajax({
			url     : $('.baseUrl').val() + '/api/pasien/riwayat',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
      data: { patient_id: id },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {
        console.log('data', data);
				let listRiwayatPasien = '';
				$('#list-riwayat-pasien tr').remove();

        if(data.length) {
          $.each(data, function(idx, v) {
            listRiwayatPasien += `<tr>`
              + `<td>${++idx}</td>`
              + `<td>${v.registration_number}</td>`
              + `<td>${v.complaint}</td>`
              + `<td>${v.registrant}</td>`
              + `<td>${v.created_by}</td>`
              + `<td>${v.created_at}</td>`
              + `<td>
                  <button type="button" class="btn btn-info openDetail" title="Detail" value=${v.registration_id}><i class="fa fa-eye" aria-hidden="true"></i></button>
                </td>`
              + `</tr>`;
          });
        } else { listRiwayatPasien += `<tr><td colspan="7">Tidak ada data.</td></tr>` }
				$('#list-riwayat-pasien').append(listRiwayatPasien);

				$('.openDetail').click(function() {
          $('.modal-title').text('Detail Riwayat Pemeriksaan');
          $('#modal-detail-riwayat-pasien').modal('show');

          $.ajax({
            url     : $('.baseUrl').val() + '/api/pasien/detail-riwayat',
            headers : { 'Authorization': `Bearer ${token}` },
            type    : 'GET',
            data    : { patient_registration_id: $(this).val() },
            beforeSend: function() { $('#loading-screen').show(); },
            success: function(data) {
              console.log('console data detail', data);
              $('#nomorRegistrasiTxt').text(data.registration_number);
              $('#anamnesaTxt').text(data.anamnesa); $('#signTxt').text(data.sign);
              $('#diagnosaTxt').text(data.diagnosa);
              $('#rawatInapTxt').text(data.status_outpatient_inpatient ? 'Ya' : 'Tidak'); $('#statusPemeriksaanTxt').text(data.status_finish ? 'Selesai' : 'Belum');
              listJasaDetail = data.services; listBarangDetail = data.item;
              listDeskripsiPasien = data.inpatient;

              processAppendListJasaDetail(); processAppendListBarangDetail();
              processAppendListDeskripsiKondisiPasien();

            }, complete: function() { $('#loading-screen').hide(); },
            error: function(err) {
              if (err.status == 401) {
                localStorage.removeItem('vet-clinic');
                location.href = $('.baseUrl').val() + '/masuk';
              }
            }
          });
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

  function processAppendListDeskripsiKondisiPasien() {
    let rowListDeksripsiKondPasien = '';
    let no = 1;
    $('#list-deskripsi-kondisi-pasien tr').remove();

    listDeskripsiPasien.forEach((desc) => {
      rowListDeksripsiKondPasien += `<tr>`
        + `<td>${no}</td>`
        + `<td>${desc.created_at}</td>`
        + `<td>${desc.created_by}</td>`
        + `<td>${desc.description}</td>`
        + `</tr>`;
        ++no;
    });
    $('#list-deskripsi-kondisi-pasien').append(rowListDeksripsiKondPasien);
  }

  function processAppendListJasaDetail() {
    let rowListJasaDetail = '';
    let no = 1;
    $('#detail-list-jasa tr').remove();

    listJasaDetail.forEach((lj, idx) => {
      rowListJasaDetail += `<tr>`
        + `<td>${no}</td>`
        + `<td>${lj.created_at}</td>`
        + `<td>${lj.created_by}</td>`
        + `<td>${lj.category_name}</td>`
        + `<td>${lj.service_name}</td>`
        + `<td>${typeof(lj.price_overall) == 'number' ? lj.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `</tr>`;
        ++no;
    });
    $('#detail-list-jasa').append(rowListJasaDetail);
  }

  function processAppendListBarangDetail() {
    let rowListBarangDetail = '';
    let no = 1;
    $('#detail-list-barang tr').remove();

    listBarangDetail.forEach((lb) => {
      rowListBarangDetail += `<tr>`
        + `<td>${no}</td>`
        + `<td>${lb.created_at}</td>`
        + `<td>${lb.created_by}</td>`
        + `<td>${lb.item_name}</td>`
        + `<td>${lb.category_name}</td>`
        + `<td>${lb.unit_name}</td>`
        + `<td>${typeof(lb.price_overall) == 'number' ? lb.price_overall.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.') : ''}</td>`
        + `</tr>`;
        ++no;
    });
    $('#detail-list-barang').append(rowListBarangDetail);
  }

});