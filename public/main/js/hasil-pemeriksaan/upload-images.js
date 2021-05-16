$(document).ready(function() {


  $('#upload-image-1').change(function() {
    const file = this.files[0];
    validationForm();

    if(file) {
      $('.img-preview-1').show(); $('#icon-plus-upload-1').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload img.img-preview-1').attr("src", this.result);
        $('.box-image-upload a.img-preview-1').attr('href', this.result);
      };
      $('[noUploadImage="1"]').show();
      reader.readAsDataURL(file);
    } else {
      $('.img-preview-1').hide(); $('#icon-plus-upload-1').show();
      $('#upload-image-1').val(''); $('[noUploadImage="1"]').hide();
    }
  });

  $('#box-1, #box-2, #box-3, #box-4, #box-5').mouseenter(function(event) {
    // const getDisplayImgPreview = event.currentTarget.children[0].style.display;
    // if(getDisplayImgPreview && getDisplayImgPreview != 'none') {
    //   event.currentTarget.lastElementChild.style.display = 'block';
    // }
  });

  $('#box-1, #box-2, #box-3, #box-4, #box-5').mouseleave(function(event) {
    // event.currentTarget.lastElementChild.style.display = 'none';
  });

  $('.btn-trash-upload-image').click(function(event) {
    const getNoUploadImage = $(this).attr('noUploadImage');
    const getImgId = $(`.box-image-upload a.img-preview-${getNoUploadImage}`).attr('image_id');
    validationForm();

    $(`#upload-image-${getNoUploadImage}`).val('');
    $(`#upload-image-${getNoUploadImage}`).show();
    $(`#icon-plus-upload-${getNoUploadImage}`).show();
    $(`.box-image-upload img.img-preview-${getNoUploadImage}`).attr('src', null);
    $(`.box-image-upload a.img-preview-${getNoUploadImage}`).removeAttr('href');
    $(`.box-image-upload .img-preview-${getNoUploadImage}`).hide();
    $(`#btn-trash-upload-image-${getNoUploadImage}`).hide();
    $(`[noUploadImage="${getNoUploadImage}"]`).hide();

    const getIdxImgExisting = getFileImagesExisting.findIndex(x => Number(x.image_id) === Number(getImgId) );
    if(getIdxImgExisting > -1) {
      getFileImagesExisting[getIdxImgExisting].status = 'del';
    }
  });


  $('#upload-image-2').change(function() {
    const file = this.files[0];
    validationForm();

    if(file) {
      $('.img-preview-2').show(); $('#icon-plus-upload-2').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload img.img-preview-2').attr('src', this.result);
        $('.box-image-upload a.img-preview-2').attr('href', this.result);
      };
      $('[noUploadImage="2"]').show();
      reader.readAsDataURL(file);
    } else {
      $('.img-preview-2').hide(); $('#icon-plus-upload-2').show();
      $('#upload-image-2').val(''); $('[noUploadImage="2"]').hide();
    }
  });

  $('#upload-image-3').change(function() {
    const file = this.files[0];
    validationForm();

    if(file) {
      $('.img-preview-3').show(); $('#icon-plus-upload-3').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload img.img-preview-3').attr('src', this.result);
        $('.box-image-upload a.img-preview-3').attr('href', this.result);
      };

      $('[noUploadImage="3"]').show();
      reader.readAsDataURL(file);
    } else {
      $('.img-preview-3').hide(); $('#icon-plus-upload-3').show();
      $('#upload-image-3').val(''); $('[noUploadImage="3"]').hide();
    }
  });

  $('#upload-image-4').change(function() {
    const file = this.files[0];
    validationForm();

    if(file) {
      $('.img-preview-4').show(); $('#icon-plus-upload-4').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload img.img-preview-4').attr('src', this.result);
        $('.box-image-upload a.img-preview-4').attr('href', this.result);
      };

      $('[noUploadImage="4"]').show();
      reader.readAsDataURL(file);
    } else {
      $('.img-preview-4').hide(); $('#icon-plus-upload-4').show();
      $('#upload-image-4').val(''); $('[noUploadImage="4"]').hide();
    }
  });

  $('#upload-image-5').change(function() {
    const file = this.files[0];
    validationForm();

    if(file) {
      $('.img-preview-5').show(); $('#icon-plus-upload-5').hide();
      const reader = new FileReader();

      reader.onload = function(event) {
        $('.box-image-upload img.img-preview-5').attr('src', this.result);
        $('.box-image-upload a.img-preview-5').attr('href', this.result);
      };

      $('[noUploadImage="5"]').show();
      reader.readAsDataURL(file);
    } else {
      $('.img-preview-5').hide(); $('#icon-plus-upload-5').show();
      $('#upload-image-5').val(''); $('[noUploadImage="5"]').hide();
    }
  });

  // function loadDropzone() {
  //   dropzone = new Dropzone('#fotoKondisiPasien', {
  //     url: $('.baseUrl').val() + '/api/hasil-pemeriksaan/upload-gambar',
  //     uploadMultiple: true,
  //     addRemoveLinks: true,
  //     acceptedFiles: 'image/jpeg,image/png',
  //     autoProcessQueue: false,
  //     parallelUploads: 5,
  //     maxFiles: 5,
  //     maxFilesize: 1, // MB
  //     dictMaxFilesExceeded: 'You cannot upload anymore, max is {{maxFiles}} file',
  //     dictFileSizeUnits: 'File size cannot greater than 1MB',
  //     paramName: 'filenames',
  //     headers: { Authorization: `Bearer ${token}` },
  //     init: function() {
  //       this.on('sending', function (file, xhr, formData) {
  //         formData.append('check_up_result_id', getCheckupResultId);
  //       });
  //       this.on('error', function(file, response) {
  //         console.log('eror upload meessage', response, file);
  //         // detectValidPhoto(false);
  //         // validationForm();
  //       });
  //       this.on('success', function(file, response) {
  //         console.log('success upload meessage', response, file);
  //         $("#msg-box .modal-body").text(`Berhasil ${(formState === 'add') ? 'Menambah': 'Merubah'} Data`);
  //         $('#msg-box').modal('show');
  //         setTimeout(() => {
  //           window.location.href = $('.baseUrl').val() + '/hasil-pemeriksaan';
  //         }, 1000);
  //       });
  //       this.on('addedfile', function(file) {
  //         console.log('add file', file);
          
  //         tempFile.push({name: file.name, type: file.type});
  //         validationPhoto();
  //       });
  //       this.on('removedfile', function(file) {
  //         console.log('removedfile', file);
          
  //         let getIdxFile = tempFile.findIndex(x => x.name === file.name);
  //         if (getIdxFile > -1) { tempFile.splice(getIdxFile, 1); }

  //         validationPhoto();
  //       });
      
  //     }
  //   });
  // }


});