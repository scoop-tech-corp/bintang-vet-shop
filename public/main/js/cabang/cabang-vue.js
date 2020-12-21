const masterApp = new Vue({
	el: '#cabang-app',
	data: {
		kodeCabang: '',
		namaCabang: '',
		titleModal: '',
		stateModal: '',
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

			this.kodeCabang = item.BranchCode;
			this.namaCabang = item.BranchName;
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
					console.log('resp GET DATA', resp);
					this.listCabang = resp.data;
				})
				.catch(err => {
					console.log('error nich', err);
				});
		},
		submitCabang: function() {
			const form_data = new FormData();
			form_data.append('KodeCabang', this.kodeCabang);
			form_data.append('NamaCabang', this.namaCabang);

			axios.post('/cabang/store', form_data)
			.then(resp => {
				if(resp.status == 200) {
					$('#modal-cabang').modal('toggle');
					this.refreshVariable();
					this.getData();
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