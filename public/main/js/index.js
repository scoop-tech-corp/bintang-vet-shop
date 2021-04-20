$(document).ready(function() {

  let paramUrlSetup = {
    orderby:'', column: '', keyword: ''
  };

  $.ajax({
    url     : $('.baseUrl').val() + '/api/cabang',
    headers : { 'Authorization': `Bearer ${token}` },
    type    : 'GET',
    data	  : { orderby: paramUrlSetup.orderby, column: paramUrlSetup.column, keyword: paramUrlSetup.keyword },
    beforeSend: function() { $('#loading-screen').show(); },
    success: function(data) {
      
    }, complete: function() { $('#loading-screen').hide(); },
    error: function(err) {
      if (err.status == 401) {
        localStorage.removeItem('vet-clinic');
        location.href = $('.baseUrl').val() + '/masuk';
      }
    }
  });


});