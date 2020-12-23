const masterApp = new Vue({
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
		namaCabangErr: false,
		listCabang: []
  },
  mounted() {
		this.getData();
	},
	computed: {
		validateSimpanCabang: function() {
			return this.kdCabangErr1 || this.kdCabangErr2 || this.namaCabangErr;
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
		},
		namaCabangKeyup: function(e) {
			this.namaCabangErr = (!this.namaCabang) ? true : false;
		},
		getData: function() {
			axios.get('/getDataBranch')
				.then(resp => {
					this.listCabang = resp.data;
				})
				.catch(err => {
					console.log('error nich', err);
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
				this.processDelete(form_data);
			}
		},
		processDelete: function(form_data) {
			axios.post('/cabang/hapus', form_data)
			.then(resp => {
				if (resp.status == 200) {
					$('#modal-confirmation').modal('toggle');

					this.msgContent = 'Berhasil menghapus abang';
					$('#msg-box').modal('show');
					this.getData();
				}
			})
			.catch(err => {
				console.log('error nich', err);
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
				console.log('error nich', err);
			})
		},
		processSave: function(form_data) {
			axios.post('/cabang/store', form_data)
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
				console.log('error nich', err);
			})
		},
		refreshVariable: function() {
			this.kodeCabang = '';
			this.namaCabang = '';
		}
  }
});