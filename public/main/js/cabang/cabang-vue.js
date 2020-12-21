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
  },
  mounted() {

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
			$('#modal-cabang').modal('show');
		},
		kodeCabangKeyup: function(e) {
			const regexp = /^[^a-z ]*$/;
			this.kdCabangErr1 = (!this.kodeCabang) ? true : false;
			this.kdCabangErr2 = (!regexp.test(this.kodeCabang)) ? true : false;
		},
		namaCabangKeyup: function(e) {
			this.namaCabangErr = (!this.namaCabang) ? true : false;
		}
  }
});