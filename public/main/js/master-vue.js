const masterApp = new Vue({
	el: '#master-app',
	data: {
    fullname: '',
    username: '',
    role: '',
    token: '',
    email: '',
    userId: null,
    baseUrl: ''
  },
  mounted() {
    let getAuthUser = localStorage.getItem('vet-clinic');
    if (!getAuthUser) {
      alert('You Must Login First!');
      location.href = this.$refs.baseUrl.value + '/login';
    } else {
      getAuthUser = JSON.parse(getAuthUser);
      this.fullname = getAuthUser.fullname;
      this.username = getAuthUser.username;
      this.userId = getAuthUser.user_id;
      this.token = getAuthUser.token;
      this.email = getAuthUser.email;
      this.role = getAuthUser.role.toLowerCase();
    }
  },
  methods: {
    onLogOut: function() {
      const formData = { 'username': this.username };
      axios.post(this.$refs.baseUrl.value + '/api/logout', formData, { 
        headers: { 
          'Content-Type': 'application/json',
          'Authorization': `Bearer ${this.token}`
       },
      })
      .then(resp => {
        localStorage.removeItem('vet-clinic');
        location.href = this.$refs.baseUrl.value + '/login';
      })
      .catch(err => {
        console.log('error nich', err);
      });
    }
  }
});
