let username = '';
let fullname = '';
let role = '';
let imageProfile = '';
let email = '';
let token = '';
let userId = '';
let branchName = '';

$(document).ready(function() {

  let getAuthUser = localStorage.getItem('vet-clinic');
  if (!getAuthUser) {
    location.href = $('.baseUrl').val() + '/masuk';
  } else {
    getAuthUser = JSON.parse(getAuthUser);
    
    username     = getAuthUser.username;
    fullname     = getAuthUser.fullname;
    role         = getAuthUser.role.toLowerCase();
    imageProfile = getAuthUser.image_profile;
    userId       = getAuthUser.user_id;
    token        = getAuthUser.token;
    email        = getAuthUser.email;
    branchName   = getAuthUser.branch_name;

    $('.username-txt').append(username);
    $('.nameAndRole-txt').append(fullname + ' - ' + role + ' - ' + branchName);

    const setUrlImage = `${$('.baseUrl').val()}${imageProfile ? imageProfile : '/assets/image/avatar-default.svg'}`;
    $('.image-header').attr("src", setUrlImage);

    const getUrl = window.location.pathname;
    if (getUrl.includes('profil')) {
      $('.content-header').append('<h3>User Profil</h3>')
    } else if(getUrl == '/') {
      $('.content-header').append('<h2>Selamat datang di Sistem Administrasi Bintang Vet Clinic</h2>')
    }

    if (role === 'admin') {
      $('.menuPasien').show();   $('.menuPendaftaran').show();
      $('.menuDokter').show();   $('.menuTindakan').show();
      $('.menuGudang').show();   $('.menuPembayaran').show();
      $('.menuKeuangan').show(); $('.menuKunjungan').show();
      $('.menuCabang').show();   $('.menuUser').show();
      $('.menuPeriksa').show();
    } else if (role === 'resepsionis') {
      $('.menuPasien').show();   $('.menuPendaftaran').show();
      $('.menuTindakan').show(); $('.menuPembayaran').show();
      $('.menuKunjungan').show(); $('.menuGudang').show();
    } else if (role === 'dokter') {
      $('.menuDokter').show();   $('.menuPasien').show();
      $('.menuTindakan').show(); $('.menuGudang').show();
      $('.menuKunjungan').show(); $('.menuPeriksa').show();
      $('.menuPendaftaran').show();
    }
  }

  // set active class for current page
  const origin = window.location.origin;
  const pathName = window.location.pathname;
  const fullPath = origin + pathName;
  $('.sidebar-menu a').each(function(Key, Value) {

    if ( Value['href'] === fullPath) {
      $(Value).parent().addClass('active');

      if (pathName === '/kategori-barang' || pathName === '/satuan-barang'  || pathName === '/daftar-barang'
        || pathName === '/pembagian-harga-barang' || pathName === '/kelompok-obat' || pathName === '/pembagian-harga-kelompok-obat') {
        $('.menuGudang').addClass('active');
      } else if(pathName === '/kategori-jasa' || pathName === '/daftar-jasa' || pathName === '/pembagian-harga-jasa') {
        $('.menuLayanan').addClass('active');
      } else if (pathName === '/rawat-jalan' || pathName === '/rawat-inap') {
        $('.menuPendaftaran').addClass('active');
      } else if (pathName === '/dokter-rawat-jalan' || pathName === '/dokter-rawat-inap') {
        $('.menuDokter').addClass('active');
      } else if (pathName === '/laporan-keuangan-harian' || pathName === '/laporan-keuangan-mingguan' || pathName === '/laporan-keuangan-bulanan') {
        $('.menuKeuangan').addClass('active');
      }
    } else {
      // additional custom url
      if (Value['href'] ==  origin + '/pembayaran' 
        && (pathName == '/pembayaran/tambah' || pathName.includes('/pembayaran/edit') || pathName.includes('/pembayaran/detail'))) {
        $(Value).parent().addClass('active');
      } else if (Value['href'] ==  origin + '/pasien' && pathName.includes('/riwayat-pameriksaan')) {
        $(Value).parent().addClass('active');
      } else if (Value['href'] ==  origin + '/hasil-pemeriksaan' && (pathName == '/hasil-pemeriksaan/tambah'
        || pathName.includes('/hasil-pemeriksaan/edit') || pathName.includes('/hasil-pemeriksaan/detail'))) {
        $(Value).parent().addClass('active');
      } else if ((Value['href'] ==  origin + '/laporan-keuangan-harian' && (pathName.includes('/laporan-keuangan-harian/detail')))
        || (Value['href'] ==  origin + '/laporan-keuangan-mingguan' && (pathName.includes('/laporan-keuangan-mingguan/detail')))
        || (Value['href'] ==  origin + '/laporan-keuangan-bulanan' && (pathName.includes('/laporan-keuangan-bulanan/detail'))) ) {
        $('.menuKeuangan').addClass('active'); $(Value).parent().addClass('active');
      }
    }
  });
  $('#btn-profil').click(function() {
    window.location.href = $('.baseUrl').val() + `/profil/${userId}`;
  });

  $('#btn-logout').click(function() {
    const fd = new FormData();
    fd.append('username', username);

    $.ajax({
      url : $('.baseUrl').val() + '/api/keluar',
      type: 'POST',
      dataType: "json",
      headers: { 'Authorization': `Bearer ${token}` },
      data: fd, contentType: false, cache: false,
      processData: false,
      success:function(resp) {
        localStorage.removeItem('vet-clinic');
        location.href = $('.baseUrl').val() + '/masuk';
      },
      error: function(err) {
        if (err.status == 401) {
          localStorage.removeItem('vet-clinic');
          location.href = $('.baseUrl').val() + '/masuk';
        }
      }
    });
  });
});

// const masterApp = new Vue({
// 	el: '#master-app',
// 	data: {
//     fullname: '',
//     username: '',
//     role: '',
//     token: '',
//     email: '',
//     userId: null,
//     baseUrl: ''
//   },
//   mounted() {
//     let getAuthUser = localStorage.getItem('vet-clinic');
//     if (!getAuthUser) {
//       alert('You Must Login First!');
//       location.href = this.$refs.baseUrl.value + '/login';
//     } else {
//       getAuthUser = JSON.parse(getAuthUser);
//       this.fullname = getAuthUser.fullname;
//       this.username = getAuthUser.username;
//       this.userId = getAuthUser.user_id;
//       this.token = getAuthUser.token;
//       this.email = getAuthUser.email;
//       this.role = getAuthUser.role.toLowerCase();
//     }
//   },
//   methods: {
//     onLogOut: function() {
//       const formData = { 'username': this.username };
//       axios.post(this.$refs.baseUrl.value + '/api/keluar', formData, { 
//         headers: { 
//           'Content-Type': 'application/json',
//           'Authorization': `Bearer ${this.token}`
//        },
//       })
//       .then(resp => {
//         localStorage.removeItem('vet-clinic');
//         location.href = this.$refs.baseUrl.value + '/login';
//       })
//       .catch(err => {
//         if (err.response.status === 401) {
//           localStorage.removeItem('vet-clinic');
//           location.href = this.$refs.baseUrl.value + '/login';
//         }
//       });
//     }
//   }
// });
