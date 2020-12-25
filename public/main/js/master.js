let username = '';
let fullname = '';
let role = '';
let email = '';
let token = '';
let userId = '';

$(document).ready(function() {

  let getAuthUser = localStorage.getItem('vet-clinic');
  if (!getAuthUser) {
    alert('You Must Login First!');
    location.href = this.$refs.baseUrl.value + '/login';
  } else {
    getAuthUser = JSON.parse(getAuthUser);
    
    username = getAuthUser.username;
    fullname = getAuthUser.fullname;
    role     = getAuthUser.role.toLowerCase();
    userId   = getAuthUser.user_id;
    token    = getAuthUser.token;
    email    = getAuthUser.email;
    
    $('.username-txt').append(username);
    $('.nameAndRole-txt').append(fullname + ' - ' + role);

    if (role === 'admin') {
      $('.menuPasien').show();   $('.menuPendaftaran').show();
      $('.menuDokter').show();   $('.menuTindakan').show();
      $('.menuGudang').show();   $('.menuPembayaran').show();
      $('.menuKeuangan').show(); $('.menuKunjungan').show();
      $('.menuCabang').show();      
    } else if (role === 'resepsionis') {
      $('.menuPasien').show();   $('.menuPendaftaran').show();
      $('.menuTindakan').show(); $('.menuPembayaran').show();
      $('.menuKunjungan').show();
    } else if (role === 'dokter') {
      $('.menuDokter').show();   $('.menuPasien').show();
      $('.menuTindakan').show(); $('.menuGudang').show();
      $('.menuKunjungan').show();
    }
  }

  $('#btn-logout').click(function() {
    $.ajax({
      url : $('.baseUrl').val() + '/api/keluar',
      type: 'POST',
      headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${this.token}` },
      data: { 'username': username },
      success:function(resp) {
        console.log('resp', resp);
        localStorage.removeItem('vet-clinic');
        location.href = $('.baseUrl').val() + '/masuk';
      },
      error: function(err) {
        console.log('err', err);
        if (err.status == '401') {
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
