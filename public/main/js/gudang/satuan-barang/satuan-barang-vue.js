$(document).ready(function() {
	const satuanBarangApp = new Vue({
		el: '#satuan-barang-app',
		data: {
			listData: [],
			searchTxt: '',
			titleModal: '',   stateModal: '',
			msgContent: '',   confirmContent: '',
			idSatuan: '',   satuanBarang: '',
			satuanErr1: false, touchedForm: false,
			beErr: false,     msgBeErr: '',
			columnStatus: {
				unit_name: 'none',
				created_by: 'none',
				created_at: 'none'
			},
			paramUrlSetup: {
				orderby:'',
				column: '',
				keyword: ''
			}
		},
		mounted() {
			if (role.toLowerCase() != 'admin') {
				$('.columnAction').hide();
				$('.section-left-box-title .btn').hide();
			}
			this.getData();
		},
		computed: {
			validateSimpanSatuanBarang: function() {
				return this.satuanErr1 || this.beErr || !this.touchedForm;
			}
		},
		methods: {
			openFormAdd: function() {
				if (role.toLowerCase() != 'dokter') {
					this.stateModal = 'add';
					this.titleModal = 'Tambah Satuan Barang';
					this.refreshVariable();
					$('#modal-satuan-barang').modal('show');
				}
			},
			openFormUpdate: function(item) {
				this.stateModal = 'edit';
				this.titleModal = 'Ubah Satuan Barang';
				this.refreshVariable();
				this.idSatuan = item.id;
				this.satuanBarang = item.unit_name;
				$('#modal-satuan-barang').modal('show');
			},
			openFormDelete: function(item) {
				this.stateModal = 'delete';
				this.idSatuan = item.id;
				this.confirmContent = 'Anda yakin ingin menghapus Satuan Barang ini?';
				$('#modal-confirmation').modal('show');
			},
			satuanBarangKeyup: function() {
				this.validationForm();
			},
			submitSatuanBarang: function() {
				if (this.stateModal === 'add') {
					const form_data = new FormData();
					form_data.append('NamaSatuan', this.satuanBarang);

					this.processSave(form_data);
				} else if (this.stateModal === 'edit') {
					$('#modal-confirmation').modal('show');
					this.confirmContent = 'Anda yakin untuk mengubah satuan barang ?';
				}
			},
			submitConfirm: function() {

				if (this.stateModal === 'edit') {
					const request = {
						id: this.idSatuan,
						NamaSatuan: this.satuanBarang,
					};

					this.processEdit(request);
				} else {
					this.processDelete({ id: this.idSatuan });
				}
			},
			onOrdering: function(e) {
				this.columnStatus[e] = (this.columnStatus[e] == 'asc') ? 'desc' : 'asc';
				if (e === 'unit_name') {
					this.columnStatus['created_by'] = 'none';
					this.columnStatus['created_at'] = 'none';
				}  else if (e === 'created_by') {
					this.columnStatus['unit_name'] = 'none';
					this.columnStatus['created_at'] = 'none';
				} else {
					this.columnStatus['unit_name'] = 'none';
					this.columnStatus['created_by'] = 'none';
				}

				this.paramUrlSetup.orderby = this.columnStatus[e];
				this.paramUrlSetup.column = e;
				this.getData();
			},
			onSearch: function() {
				this.paramUrlSetup.keyword =  this.searchTxt;
				this.getData();
			},
			getData: function() {
				$('#loading-screen').show();
				axios.get($('.baseUrl').val() + '/api/satuan-barang', { params: this.paramUrlSetup, headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
					.then(resp => {
						const getRespData = resp.data;
						getRespData.map(item => {
							item.isRoleAccess = (role.toLowerCase() != 'admin') ? false : true;
							return item;
						});
						this.listData = getRespData;
					})
					.catch(err => {
						if (err.response.status === 401) {
							localStorage.removeItem('vet-clinic');
							location.href = $('.baseUrl').val() + '/masuk';
						}
					})
					.finally(() => {
						$('#loading-screen').hide();
					});
			},
			processSave: function(form_data) {
				$('#loading-screen').show();
				axios.post($('.baseUrl').val() + '/api/satuan-barang', form_data, { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
				.then(resp => {
					if(resp.status == 200) {
						this.msgContent = 'Berhasil Menambah Satuan Barang';
						$('#msg-box').modal('show');

						setTimeout(() => {
							$('#modal-satuan-barang').modal('toggle');
							this.refreshVariable();
							this.getData();
						}, 1000);
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
					$('#loading-screen').hide();
				});
			},
			processEdit: function(form_data) {
				$('#loading-screen').show();
				axios.put($('.baseUrl').val() + '/api/satuan-barang', form_data, { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
				.then(resp => {
					if(resp.status == 200) {
						$('#modal-confirmation').modal('toggle');

						this.msgContent = 'Berhasil Mengubah Satuan barang';
						$('#msg-box').modal('show');

						setTimeout(() => {
							$('#modal-satuan-barang').modal('toggle');
							this.refreshVariable();
							this.getData();
						}, 1000);
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
					$('#loading-screen').hide();
				});
			},
			processDelete: function(form_data) {
				axios.delete($('.baseUrl').val() + '/api/satuan-barang', { params: form_data, headers: { 'Authorization': `Bearer ${token}` } })
				.then(resp => {
					if (resp.status == 200) {
						$('#modal-confirmation').modal('toggle');

						this.msgContent = 'Berhasil menghapus satuan barang';
						$('#msg-box').modal('show');
						this.getData();
					}
				})
				.catch(err => {
					if (err.response.status === 401) {
						localStorage.removeItem('vet-clinic');
	          location.href = $('.baseUrl').val() + '/masuk';
					}
				})
			},
			validationForm: function() {
				this.touchedForm = true; this.beErr = false;
				this.satuanErr1 = (!this.satuanBarang) ? true : false; 
			},
			refreshVariable: function() {
				this.satuanBarang = ''; this.satuanErr1 = false;
				this.beErr = false; this.touchedForm = false;
			}
		}
	})
});