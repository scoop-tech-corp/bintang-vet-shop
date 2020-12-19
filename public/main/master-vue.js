// Vue.http.headers.common['X-CSRF-TOKEN'] = document.querySelector('#token').getAttribute('value');

const masterApp = new Vue({
	el: '#master-app',
	data: {
    fullname: '',
    username: '',
    role: '',
    token: '',
    message: '',
    showAlert: false,
    isSuccess: false,
    baseUrl: ''
  },
  mounted() {

  },
  methods: {

  }
});
