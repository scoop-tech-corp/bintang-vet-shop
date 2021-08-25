$(document).ready(function() {

 loadPembayaran();

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

});
