$(document).ready(function() {
  const fullname_profile = fullname;
  const role_profile = role.toUpperCase();

  $('.profile-username').text(fullname_profile);
  $('.profile-role').text(role_profile); 
  $('.box-profile img').attr("src", `${$('.baseUrl').val()}/assets/image/avatar-default.svg`);

  $('#tanggalLahir').datepicker({
    autoclose: true,
    clearBtn: true,
    format: 'yyyy-mm-dd',
    todayHighlight: true,
  });


  $('.btn-upload-foto').click(function() {
    $('#inputfileimg').val('');
    $('#modal-profile-upload-foto').modal('show');
    $('.temp-img-upload-section img').attr("src", `${$('.baseUrl').val()}/assets/image/avatar-default.svg`);
  });

  $('#inputfileimg').change(function() {
    const file = this.files[0];
    console.log('file', file);

    if(file) {
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.temp-img-upload-section img').attr("src", this.result);
      };

      reader.readAsDataURL(file);
    }
  });


});
