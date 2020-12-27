$(document).ready(function() {

	const cabangApp = new Vue({
		el: '#cabang-app',
		data: {
			idCabang: null,
			kodeCabang: '',
			namaCabang: '',
			titleModal: '',
			stateModal: '',
			msgContent: '',
			confirmContent: '',
			kdCabangErr1: false,
			kdCabangErr2: false,
			beErr: false,
			msgBeErr: '',
			namaCabangErr: false,
			listCabang: [],
			columnStatus: {
				id: 'none',
				branch_code: 'none',
				branch_name: 'none',
			}
		},
		mounted() {
			this.getData();
		},
		computed: {
			validateSimpanCabang: function() {
				return this.kdCabangErr1 || this.kdCabangErr2 || this.beErr || this.namaCabangErr;
			}
		},
		methods: {
			openFormAdd: function() {
				this.stateModal = 'add';
				this.titleModal = 'Tambah Cabang';
				this.refreshVariable();
				$('#modal-cabang').modal('show');
			},
			openFormUpdate: function(item) {
				this.stateModal = 'edit';
				this.titleModal = 'Ubah Cabang';
				$('#modal-cabang').modal('show');

				this.idCabang   = item.id;
				this.kodeCabang = item.BranchCode;
				this.namaCabang = item.BranchName;
			},
			openFormDelete: function(item) {
				this.stateModal = 'delete';
				this.idCabang = item.id;
				$('#modal-confirmation').modal('show');
					this.confirmContent = 'Menghapus cabang akan mempengaruhi keseluruhan data';
			},
			kodeCabangKeyup: function(e) {
				const regexp = /^[^a-z ]*$/;
				this.kdCabangErr1 = (!this.kodeCabang) ? true : false;
				this.kdCabangErr2 = (!regexp.test(this.kodeCabang)) ? true : false;
				this.beErr = false;
			},
			namaCabangKeyup: function(e) {
				this.namaCabangErr = (!this.namaCabang) ? true : false;
				this.beErr = false;
			},
			onOrdering: function(e) {

				this.columnStatus[e] = (this.columnStatus[e] == 'asc') ? 'desc' : 'asc';
				if (e === 'id') {
					this.columnStatus['branch_code'] = 'none';
					this.columnStatus['branch_name'] = 'none';
				} else if (e === 'branch_code') {
					this.columnStatus['id'] = 'none';
					this.columnStatus['branch_name'] = 'none';
				} else {
					this.columnStatus['id'] = 'none';
					this.columnStatus['branch_code'] = 'none';
				}

				if (this.columnStatus[e] == 'asc') {
					this.listCabang = this.listCabang.sort((a,b)=> (a[e] > b[e] ? 1 : -1));
				} else if (this.columnStatus[e] == 'desc') {
					this.listCabang = this.listCabang.sort((a,b)=> (a[e] < b[e] ? 1 : -1));
				}
			},
			getData: function() {
				$('.loading-screen').show();
				axios.get($('.baseUrl').val() + '/api/cabang', { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
					.then(resp => {
						this.listCabang = resp.data;
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
			submitCabang: function() {
				
				if (this.stateModal === 'add') {
					const form_data = new FormData();
					form_data.append('KodeCabang', this.kodeCabang);
					form_data.append('NamaCabang', this.namaCabang);

					this.processSave(form_data);
				} else {

					$('#modal-confirmation').modal('show');
					this.confirmContent = 'Perubahan cabang akan mempengaruhi keseluruhan data';
				}
			},
			submitConfirm: function() {
				const form_data = new FormData();
				form_data.append('id', this.idCabang);

				if (this.stateModal === 'edit') {
					form_data.append('KodeCabang', this.kodeCabang);
					form_data.append('NamaCabang', this.namaCabang);
					this.processEdit(form_data);
				} else {
					this.processDelete({ id: this.idCabang });
				}
			},
			processDelete: function(form_data) {
				axios.delete($('.baseUrl').val() + '/api/cabang', { params: form_data, headers: { 'Authorization': `Bearer ${token}` } })
				.then(resp => {
					if (resp.status == 200) {
						$('#modal-confirmation').modal('toggle');

						this.msgContent = 'Berhasil menghapus cabang';
						$('#msg-box').modal('show');
						this.getData();
					}
				})
				.catch(err => {
					console.log(err);
					if (err.response.status === 401) {
						localStorage.removeItem('vet-clinic');
	          location.href = $('.baseUrl').val() + '/masuk';
					}
				})
			},
			processEdit: function(form_data) {
				axios.post('/cabang/update', form_data)
				.then(resp => {
					if (resp.status == 200) {
						$('#modal-confirmation').modal('toggle');

						this.msgContent = 'Berhasil Mengubah Cabang';
						$('#msg-box').modal('show');

						setTimeout(() => {
							$('#modal-cabang').modal('toggle');
							this.refreshVariable();
							this.getData();
						}, 2000);
					}
				})
				.catch(err => {
					if (err.response.status === 401) {
						localStorage.removeItem('vet-clinic');
	          location.href = $('.baseUrl').val() + '/masuk';
					}
				})
			},
			processSave: function(form_data) {
				$('.loading-screen').show();
				axios.post($('.baseUrl').val() + '/api/cabang', form_data, { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
				.then(resp => {
					if(resp.status == 200) {
						this.msgContent = 'Berhasil Menambah Cabang';
						$('#msg-box').modal('show');

						setTimeout(() => {
							$('#modal-cabang').modal('toggle');
							this.refreshVariable();
							this.getData();
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
			refreshVariable: function() {
				this.kodeCabang = '';
				this.namaCabang = '';
				this.beErr = false;
			}
		}
	});

});