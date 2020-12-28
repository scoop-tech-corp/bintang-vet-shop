$(document).ready(function() {

	const userApp = new Vue({
		el: '#user-app',
		data: {
			listUser: [],
			listCabang: [],
			searchTxt: '',
			nomorPegawai: '', idPegawai: '',
			username: '', namaLengkap: '',
			email: '', password: '',
			confPassword: '', nomPonsel: '',
			selectRole: '', selectCabang: '',
			selectStatus: '',
			titleModal: '', stateModal: '',
			msgContent: '', confirmContent: '',
			usernameErr1: false, namaLengkapErr1: false,
			emailErr1: false, passwordErr1: false,
			confPasswordErr1: false, nomPonselErr1: false,
			roleErr1: false, cabangErr1: false, statusErr1: false,
			touchedForm: false, beErr: false, passwordErr2: false,
			passwordType1: 'password', showPassword1: false,
			passwordType2: 'password', showPassword2: false,
			msgBeErr: '',
			columnStatus: {
				staffing_number: 'none',
				username: 'none',
				fullname: 'none',
				email: 'none',
				role: 'none',
				branch_name: 'none',
				status: 'none',
				created_by: 'none',
				created_at: 'none'
			},
			orderSetup: {
				orderby:'',
				column: ''
			}
		},
		mounted() {
			this.reloadData();
		},
		computed: {
			validateSimpanUser: function() {
				return this.usernameErr1 || this.namaLengkapErr1 || this.emailErr1 || this.passwordErr1 || this.passwordErr2 || this.confPasswordErr1
					|| this.nomPonselErr1 || this.roleErr1 || this.statusErr1 || this.cabangErr1 || this.beErr || !this.touchedForm;
			}
		},
		methods: {
			openFormAdd: function() {
				this.stateModal = 'add';
				this.titleModal = 'Tambah User';
				this.refreshVariable();
				$('#modal-user').modal('show');
			},
			openFormUpdate: function(item) {
				this.stateModal = 'edit';
				this.titleModal = 'Ubah User';
				this.refreshVariable();

				this.nomorPegawai = item.staffing_number; this.username = item.username;
				this.email = item.email; this.nomPonsel = item.phone_number;
				this.namaLengkap = item.fullname; this.idPegawai = item.id;
				this.selectRole = item.role; this.selectStatus = item.status;
				this.selectCabang = this.listCabang.find(x => x.id == item.branch_id);

				$('#modal-user').modal('show');
			},
			submitUser: function() {
				if (this.stateModal === 'add') {
					const form_data = new FormData();
					form_data.append('username', this.username);
					form_data.append('nama_lengkap', this.namaLengkap);
					form_data.append('email', this.email);
					form_data.append('password', this.password);
					form_data.append('nomor_ponsel', this.nomPonsel);
					form_data.append('role', this.selectRole);
					form_data.append('status', this.selectStatus);
					form_data.append('id_cabang', this.selectCabang.id);
					form_data.append('kode_cabang', this.selectCabang.branch_code);

					this.processSave(form_data);
				} else if (this.stateModal === 'edit') {
					$('#modal-confirmation').modal('show');
					this.confirmContent = 'Anda yakin untuk mengubah user ?';
				}
			},
			submitConfirm: function() {
				// const form_data = new FormData();
				// form_data.append('id', this.idPegawai);

				if (this.stateModal === 'edit') {
					// form_data.append('nomor_kepegawaian', this.nomorPegawai);
					// form_data.append('nama_lengkap', this.namaLengkap);
					// form_data.append('role', this.selectRole);
					// form_data.append('status', this.selectStatus);
					// form_data.append('id_cabang', this.selectCabang.id);
					// form_data.append('kode_cabang', this.selectCabang.branch_code);

					const request = {
						id: this.idPegawai,
						nomor_kepegawaian: this.nomorPegawai,
						nama_lengkap: this.namaLengkap,
						role: this.selectRole,
						status: this.selectStatus,
						id_cabang: this.selectCabang.id,
						kode_cabang: this.selectCabang.branch_code
					};
					
					this.processEdit(request);
				}
			},
			getData: function() {
				$('.loading-screen').show();
				axios.get($('.baseUrl').val() + '/api/user', { params: this.orderSetup, headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
					.then(resp => {
						this.listUser = resp.data;
					})
					.catch(err => {
						if (err.response.status === 401) {
							localStorage.removeItem('vet-clinic');
							location.href = $('.baseUrl').val() + '/masuk';
						}
					})
					.finally(() => {
						$('.loading-screen').hide();
					});
			},
			getDataCabang: function() {
				axios.get($('.baseUrl').val() + '/api/cabang', { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
					.then(resp => {
						this.listCabang = resp.data;
					})
					.catch(err => {
						if (err.response.status === 401) {
							localStorage.removeItem('vet-clinic');
							location.href = $('.baseUrl').val() + '/masuk';
						}
					});
			},
			processSave: function(form_data) {
				$('.loading-screen').show();
				axios.post($('.baseUrl').val() + '/api/user', form_data, { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
				.then(resp => {
					if(resp.status == 200) {
						this.msgContent = 'Berhasil Menambah User';
						$('#msg-box').modal('show');

						setTimeout(() => {
							$('#modal-user').modal('toggle');
							this.refreshVariable();
							this.reloadData();
						}, 2000);
					}
				})
				.catch(err => {
					if (err.response.status === 422) {
						this.msgBeErr = '',
						err.response.data.errors.forEach((element, idx) => {
							this.msgBeErr += element + ((idx !== err.response.data.errors.length - 1) ? '<br/>' : '');
						});
						this.beErr = true;

					} else if (err.response.status === 401) {
						localStorage.removeItem('vet-clinic');
	          location.href = $('.baseUrl').val() + '/masuk';
					}
				})
				.finally(() => {
					$('.loading-screen').hide();
				});
			},
			processEdit: function(form_data) {
				axios.put($('.baseUrl').val() + '/api/user', form_data, { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
				.then(resp => {
					if(resp.status == 200) {
						$('#modal-confirmation').modal('toggle');

						this.msgContent = 'Berhasil Mengubah User';
						$('#msg-box').modal('show');

						setTimeout(() => {
							$('#modal-user').modal('toggle');
							this.refreshVariable();
							this.reloadData();
						}, 2000);
					}
				})
				.catch(err => {
					if (err.response.status === 422) {
						this.msgBeErr = '',
						err.response.data.errors.forEach((element, idx) => {
							this.msgBeErr += element + ((idx !== err.response.data.errors.length - 1) ? '<br/>' : '');
						});
						this.beErr = true;
						$('#modal-confirmation').modal('toggle');

					} else if (err.response.status === 401) {
						localStorage.removeItem('vet-clinic');
	          location.href = $('.baseUrl').val() + '/masuk';
					}
				})
				.finally(() => {
					$('.loading-screen').hide();
				});
			},
			onSearch: function(e) {
				console.log('event', this.searchTxt);
			},
			usernameKeyup: function(e) {
				this.validationForm();
			},
			namaLengkapKeyup: function(e) {
				this.validationForm();
			},
			emailKeyup: function(e) {
				this.validationForm();
			},
			passwordKeyup: function(e) {
				this.passwordErr2 = (this.password !== this.confPassword) ? true : false;
				this.validationForm();
			},
			confPasswordKeyup: function() {
				this.passwordErr2 = (this.password !== this.confPassword) ? true : false;
				this.validationForm();
			},
			nomPonselKeyup: function() {
				this.validationForm();
			},
			togglePassword: function() {
				this.showPassword1 = !this.showPassword1;
      	this.passwordType1 = !this.showPassword1 ? 'password' : 'text';
			},
			toggleConfPassword: function() {
				this.showPassword2 = !this.showPassword2;
      	this.passwordType2 = !this.showPassword2 ? 'password' : 'text';
			},
			onSelectRole: function() {
				this.validationForm();
			},
			onSelectCabang: function(e) {
				this.validationForm();
			},
			onSelectStatus: function() {
				this.validationForm();
			},
			onOrdering: function(e) {
				this.columnStatus[e] = (this.columnStatus[e] == 'asc') ? 'desc' : 'asc';
				if (e === 'staffing_number') {
					this.columnStatus['username'] = 'none';
					this.columnStatus['fullname'] = 'none';
					this.columnStatus['email'] = 'none';
					this.columnStatus['role'] = 'none';
					this.columnStatus['branch_name'] = 'none';
				} else if (e === 'username') {
					this.columnStatus['staffing_number'] = 'none';
					this.columnStatus['fullname'] = 'none';
					this.columnStatus['email'] = 'none';
					this.columnStatus['role'] = 'none';
					this.columnStatus['branch_name'] = 'none';
				} else if (e === 'fullname') {
					this.columnStatus['staffing_number'] = 'none';
					this.columnStatus['username'] = 'none';
					this.columnStatus['email'] = 'none';
					this.columnStatus['role'] = 'none';
					this.columnStatus['branch_name'] = 'none';
				} else if (e === 'email') {
					this.columnStatus['staffing_number'] = 'none';
					this.columnStatus['username'] = 'none';
					this.columnStatus['fullname'] = 'none';
					this.columnStatus['role'] = 'none';
					this.columnStatus['branch_name'] = 'none';
				} else if (e === 'role') { 
					this.columnStatus['staffing_number'] = 'none';
					this.columnStatus['username'] = 'none';
					this.columnStatus['fullname'] = 'none';
					this.columnStatus['email'] = 'none';
					this.columnStatus['branch_name'] = 'none';
				} else { 
					this.columnStatus['staffing_number'] = 'none';
					this.columnStatus['username'] = 'none';
					this.columnStatus['fullname'] = 'none';
					this.columnStatus['email'] = 'none';
					this.columnStatus['role'] = 'none';
				}

				this.orderSetup.orderby = this.columnStatus[e];
				this.orderSetup.column = e;
				this.getData();
			},
			validationForm: function() {
				this.touchedForm = true; this.beErr = false;
				this.usernameErr1 = (!this.username) ? true : false;
				this.namaLengkapErr1 = (!this.namaLengkap) ? true : false;
				this.emailErr1 = (!this.email) ? true : false;
				this.roleErr1 = (!this.selectRole) ? true : false;
				this.cabangErr1 = (!this.selectCabang) ? true : false;
				this.statusErr1 = (!this.selectStatus) ? true : false;
				if (this.stateModal === 'add') {
					this.passwordErr1 = (!this.password) ? true : false;
					this.confPasswordErr1 = (!this.confPassword) ? true : false;
					this.nomPonselErr1 = (!this.nomPonsel) ? true : false;
				}
			},
			reloadData: function() {
				this.getData();
				this.getDataCabang();
			},
			refreshVariable: function() {
				this.username = ''; this.namaLengkap = '';
				this.email = ''; this.password = '';
				this.selectRole = ''; this.selectCabang = '';
				this.selectStatus = ''; this.nomPonsel = '';
				this.confPassword = '';
				this.usernameErr1 = false; this.namaLengkapErr1 = false;
				this.emailErr1 = false; this.passwordErr1 = false; 
				this.passwordErr2 = false; this.beErr = false;
				this.roleErr1 = false; this.cabangErr1 = false;
				this.statusErr1 = false; this.confPasswordErr1 = false;
				this.touchedForm = false; this.nomPonselErr1 = false;
			}
		}
	});


});