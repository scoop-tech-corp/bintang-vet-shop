$(document).ready(function() {

	let optCabang = "";

 loadPembayaran();
 widgetDaftarBarangLimit();
 loadCabang();

  function loadPembayaran() { // too triger cek token is expired
		$.ajax({
			url     : $('.baseUrl').val() + '/api/payment',
			headers : { 'Authorization': `Bearer ${token}` },
			type    : 'GET',
      data	  : { orderby: '', column: '', branch_id: '' },
			beforeSend: function() { $('#loading-screen').show(); },
			success: function(data) {},
      complete: function() { $('#loading-screen').hide(); },
			error: function(err) {
				if (err.status == 401) {
					localStorage.removeItem('vet-shop');
					location.href = $('.baseUrl').val() + '/masuk';
				}
			}
		});
	}

	function widgetDaftarBarangLimit() {
    let paramUrlSetup = {
      orderby: "",
      column: "",
      keyword: "",
      branchId: "",
    };

    if (role != "admin") {
      $(".section-right-box-title").append(`
        <div class="input-search-section m-r-10px">
          <input type="text" class="form-control" placeholder="cari..">
          <i class="fa fa-search" aria-hidden="true"></i>
        </div>
      `);
    } else {
      $(".section-right-box-title").append(`
        <div class="input-search-section m-r-10px">
          <input type="text" class="form-control" placeholder="cari..">
          <i class="fa fa-search" aria-hidden="true"></i>
        </div>
        <select id="filterCabangDaftarBarangLimit" style="width: 50%"></select>
      `);
    }

    $(".input-search-section .fa").click(function () {
      paramUrlSetup.keyword = $(".input-search-section input").val();
      loadDaftarBarangLimit(paramUrlSetup);
    });

    $(".input-search-section input").keypress(function (e) {
      if (e.which == 13) {
        paramUrlSetup.keyword = $(this).val();
        loadDaftarBarangLimit(paramUrlSetup);
      }
    });

    $(".onOrdering").click(function () {
      const column = $(this).attr("data");
      const orderBy = $(this).attr("orderby");
      $('.onOrdering[data="' + column + '"]')
        .children()
        .remove();

      if (orderBy == "none" || orderBy == "asc") {
        $(this).attr("orderby", "desc");
        $(this).append('<span class="fa fa-sort-desc"></span>');
      } else if (orderBy == "desc") {
        $(this).attr("orderby", "asc");
        $(this).append('<span class="fa fa-sort-asc"></span>');
      }

      paramUrlSetup.orderby = $(this).attr("orderby");
      paramUrlSetup.column = column;

      loadDaftarBarangLimit(paramUrlSetup);
    });

    $("#filterCabangDaftarBarangLimit").select2({
      placeholder: "Cabang",
      allowClear: true,
    });

    $("#filterCabangDaftarBarangLimit").on("select2:select", function () {
      paramUrlSetup.branchId = $(this).val();
      loadDaftarBarangLimit(paramUrlSetup);
    });
    $("#filterCabangDaftarBarangLimit").on("select2:unselect", function () {
      paramUrlSetup.branchId = $(this).val();
      loadDaftarBarangLimit(paramUrlSetup);
    });

    loadDaftarBarangLimit(paramUrlSetup);
  }

	function loadDaftarBarangLimit(paramUrlSetupDaftar) {
    let getCurrentPage = 1;
    $.ajax({
      url: $(".baseUrl").val() + "/api/daftar-barang-batas",
      headers: { Authorization: `Bearer ${token}` },
      type: "GET",
      data: {
        orderby: paramUrlSetupDaftar.orderby,
        column: paramUrlSetupDaftar.column,
        keyword: paramUrlSetupDaftar.keyword,
        branch_id: paramUrlSetupDaftar.branchId,
        page: getCurrentPage,
      },
      beforeSend: function () {
        $("#loading-screen").show();
      },
      success: function (resp) {
        const getData = resp;
        let listDaftarBarangLimit = "";
        $("#list-daftar-barang-limit tr").remove();

        if (getData.length) {
          $.each(getData, function (idx, v) {
            listDaftarBarangLimit +=
              `<tr>` +
              `<td class="${
                v.diff_expired_days < 60 ? "expired-date" : ""
              }">${++idx}</td>` +
              `<td class="${v.diff_item < 0 ? "item-outstock" : ""}">${
                v.item_name
              }</td>` +
              `<td>${v.total_item}</td>` +
              `<td>${v.category_name}</td>` +
              `<td>${v.branch_name}</td>` +
              `<td>${v.created_by}</td>` +
              `<td>${v.created_at}</td>` +
              `<td>${v.expired_date}</td>` +
              `</tr>`;
          });
        } else {
          listDaftarBarangLimit += `<tr class="text-center"><td colspan="12">Tidak ada data.</td></tr>`;
        }
        $("#list-daftar-barang-limit").append(listDaftarBarangLimit);

        $(".pagination > li > a").click(function () {
          const getClassName = this.className;
          const getNumber = parseFloat($(this).text());

          if (
            (getCurrentPage === 1 && getClassName.includes("arrow-left")) ||
            (getCurrentPage === resp.total_paging &&
              getClassName.includes("arrow-right"))
          ) {
            return;
          }

          if (getClassName.includes("arrow-left")) {
            getCurrentPage = getCurrentPage - 1;
          } else if (getClassName.includes("arrow-right")) {
            getCurrentPage = getCurrentPage + 1;
          } else {
            getCurrentPage = getNumber;
          }

          loadHasilPemeriksaan();
        });
      },
      complete: function () {
        $("#loading-screen").hide();
      },
      error: function (err) {
        if (err.status == 401) {
          localStorage.removeItem("vet-clinic");
          location.href = $(".baseUrl").val() + "/masuk";
        }
      },
    });
  }

	function loadCabang() {
    $.ajax({
      url: $(".baseUrl").val() + "/api/cabang",
      headers: { Authorization: `Bearer ${token}` },
      type: "GET",
      beforeSend: function () {
        $("#loading-screen").show();
      },
      success: function (data) {
        optCabang += `<option value=''>Cabang</option>`;

        if (data.length) {
          for (let i = 0; i < data.length; i++) {
            optCabang += `<option value=${data[i].id}>${data[i].branch_name}</option>`;
          }
        }

        $("#filterCabangDaftarBarangLimit").append(optCabang);
      },
      complete: function () {
        $("#loading-screen").hide();
      },
      error: function (err) {
        if (err.status == 401) {
          localStorage.removeItem("vet-clinic");
          location.href = $(".baseUrl").val() + "/masuk";
        }
      },
    });
  }

});
