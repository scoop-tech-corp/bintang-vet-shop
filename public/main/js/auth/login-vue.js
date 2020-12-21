const loginApp = new Vue({
  el: '#login-app',
	data: {
    form: {
      username: '',
      password: ''
    },
    message: '',
    usernameError: false,
    passwordError: false,
    disableSubmit: true,
    showAlert: false,
    isSuccess: false,
    baseUrl: ''
  },
  mounted() { },
  computed: {
  },
	methods: {
    usernameKeyup: function(event) {
      const value = event.target.value;
      this.usernameError = !value ? true : false;
      this.passwordError = !this.form.password ? true : false;
      this.disableSubmit = (this.usernameError || this.passwordError) ? true : false;
    },
    passwordKeyup: function(event) {
      const value = event.target.value;
      this.passwordError = !value ? true : false;
      this.usernameError = !this.form.username ? true : false;
      this.disableSubmit = (this.usernameError || this.passwordError) ? true : false;
    },
		onSubmit: function() {
      const formData = {
        'username': this.form.username,
        'password': this.form.password
      }

      this.message = '';
      if (formData.username && formData.password) {
        axios.post(this.$refs.baseUrl.value + '/api/admin/auth/signin', formData, { headers: { "Content-Type": "application/json" } })
        .then(resp => {
          console.log('Success', resp)
          this.showAlert = true; this.isSuccess = true;
          this.message = 'Login Success';
          this.form.username = ''; this.form.password = '';
          const getDataLogin = resp.data;
          localStorage.setItem('muda-cantik', JSON.stringify({
            email: getDataLogin.email,
            fullName: getDataLogin.firstname + ' ' + getDataLogin.lastname,
            imageprofile: getDataLogin.imageprofile,
            phonenumber: getDataLogin.phonenumber,
            role: getDataLogin.role,
            token: getDataLogin.token,
            user_id: getDataLogin.user_id,
            username: getDataLogin.username
          }));
        })
        .catch(err => {
          err.response.data.errors.forEach((element, idx) => {
            const msg = (idx !== 0 ) ? element + '<br>' : element;
            this.message += msg;
          });

          this.showAlert = true; this.isSuccess = false;
        })
        .finally(() => {
          setTimeout(() => {
            if (this.isSuccess) { 
              this.showAlert = false;
              location.href = this.$refs.baseUrl.value + '/admin'; 
            }
          }, 2000);
        });
      }
		}
	},
});
