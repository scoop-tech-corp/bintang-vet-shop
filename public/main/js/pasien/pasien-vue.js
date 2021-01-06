$(document).ready(function() {

	const pasienApp = new Vue({
		el: '#pasien-app',
		data: {
			listPasien: [],
			searchTxt: '',
			titleModal: '',   stateModal: '',
			msgContent: '',   confirmContent: '',
			idPasien: '',     nomorRegis: '',
			animalType: '',   animalName: '',
			animalSex: '',
			animalYear: null, animalMonth: null,
			ownerName: '',
			ownerAddress: '', ownerTelp: '',
			animalTypeErr1: false, animalNameErr1: false,
			animalSexErr1: false, animalAgeErr1: false,
			ownerNameErr1: false, ownerAddressErr1: false,
			ownerTelpErr1: false, touchedForm: false,
			beErr: false,     msgBeErr: '',
			columnStatus: {
				id_member: 'none',
				pet_category: 'none',
				pet_name: 'none',
				pet_gender: 'none',
				pet_year_age: 'none',
				branch_name: 'none',
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
			this.getData();
		},
		computed: {
			validateSimpanPasien: function() {
				return this.animalTypeErr1 || this.animalNameErr1||	this.animalSexErr1 || this.animalAgeErr1 
					|| this.ownerNameErr1 || this.ownerAddressErr1 || this.ownerTelpErr1 || this.beErr || !this.touchedForm;
			}
		},
		methods: {
			openFormAdd: function() {
				this.stateModal = 'add';
				this.titleModal = 'Tambah Pasien';
				this.refreshVariable();
				$('#modal-pasien').modal('show');
			},
			openFormUpdate: function(item) {
				this.stateModal = 'edit';
				this.titleModal = 'Ubah User';
				this.refreshVariable();
				this.idPasien = item.id;

				this.getDetail();
			},
			openFormDelete: function(item) {
				this.stateModal = 'delete';
				this.idPasien = item.id;
				this.confirmContent = 'Anda yakin ingin menghapus pasien ini?';
				$('#modal-confirmation').modal('show');
			},
			openFormDetail: function(item) {

			},
			animalTypeKeyup: function() {
				this.validationForm();
			},
			animalNameKeyup: function() {
				this.validationForm();
			},
			onSelectAnimalSex: function() {
				this.validationForm();
			},
			animalAgeKeyup: function() {
				this.validationForm();
			},
			ownerNameKeyup: function() {
				this.validationForm();
			},
			ownerAddressKeyup: function() {
				this.validationForm();
			},
			ownerTelpKeyup: function() {
				this.validationForm();
			},
			submitPasien: function() {
				if (this.stateModal === 'add') {
					const form_data = new FormData();
					form_data.append('kategori_hewan', this.animalType);
					form_data.append('nama_hewan', this.animalName);
					form_data.append('jenis_kelamin_hewan', this.animalSex);
					form_data.append('usia_tahun_hewan', this.animalYear);
					form_data.append('usia_bulan_hewan', this.animalMonth);
					form_data.append('nama_pemilik', this.ownerName);
					form_data.append('alamat_pemilik', this.ownerAddress);
					form_data.append('nomor_ponsel_pengirim', this.ownerTelp);

					this.processSave(form_data);
				} else if (this.stateModal === 'edit') {
					$('#modal-confirmation').modal('show');
					this.confirmContent = 'Anda yakin untuk mengubah pasien ?';
				}
			},
			submitConfirm: function() {

				if (this.stateModal === 'edit') {
					const request = {
						id: this.idPasien,
						kategori_hewan: this.animalType,
						nama_hewan: this.animalName,
						jenis_kelamin_hewan: this.animalSex,
						usia_tahun_hewan: this.animalYear,
						usia_bulan_hewan: this.animalMonth,
						nama_pemilik: this.ownerName,
						alamat_pemilik: this.ownerAddress,
						nomor_ponsel_pengirim: this.ownerTelp
					};

					this.processEdit(request);
				} else {
					this.processDelete({ id: this.idPasien });
				}
			},
			onSearch: function() {
				this.paramUrlSetup.keyword =  this.searchTxt;
				this.getData();
			},
			processSave: function(form_data) {
				$('#loading-screen').show();
				axios.post($('.baseUrl').val() + '/api/pasien', form_data, { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
				.then(resp => {
					if(resp.status == 200) {
						this.msgContent = 'Berhasil Menambah User';
						$('#msg-box').modal('show');

						setTimeout(() => {
							$('#modal-pasien').modal('toggle');
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
				axios.put($('.baseUrl').val() + '/api/pasien', form_data, { headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
				.then(resp => {
					if(resp.status == 200) {
						$('#modal-confirmation').modal('toggle');

						this.msgContent = 'Berhasil Mengubah Pasien';
						$('#msg-box').modal('show');

						setTimeout(() => {
							$('#modal-pasien').modal('toggle');
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
				axios.delete($('.baseUrl').val() + '/api/pasien', { params: form_data, headers: { 'Authorization': `Bearer ${token}` } })
				.then(resp => {
					if (resp.status == 200) {
						$('#modal-confirmation').modal('toggle');

						this.msgContent = 'Berhasil menghapus pasien';
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
			onOrdering: function(e) {
				this.columnStatus[e] = (this.columnStatus[e] == 'asc') ? 'desc' : 'asc';
				if (e === 'id_member') {
					this.columnStatus['pet_category'] = 'none';
					this.columnStatus['pet_name'] = 'none';
					this.columnStatus['pet_gender'] = 'none';
					this.columnStatus['pet_year_age'] = 'none';
					this.columnStatus['branch_name'] = 'none';
					this.columnStatus['created_by'] = 'none';
					this.columnStatus['created_at'] = 'none';
				} else if (e === 'pet_category') {
					this.columnStatus['id_member'] = 'none';
					this.columnStatus['pet_name'] = 'none';
					this.columnStatus['pet_gender'] = 'none';
					this.columnStatus['pet_year_age'] = 'none';
					this.columnStatus['branch_name'] = 'none';
					this.columnStatus['created_by'] = 'none';
					this.columnStatus['created_at'] = 'none';
				} else if (e === 'pet_name') {
					this.columnStatus['id_member'] = 'none';
					this.columnStatus['pet_category'] = 'none';
					this.columnStatus['pet_gender'] = 'none';
					this.columnStatus['pet_year_age'] = 'none';
					this.columnStatus['branch_name'] = 'none';
					this.columnStatus['created_by'] = 'none';
					this.columnStatus['created_at'] = 'none';
				} else if (e === 'pet_gender') {
					this.columnStatus['id_member'] = 'none';
					this.columnStatus['pet_category'] = 'none';
					this.columnStatus['pet_name'] = 'none';
					this.columnStatus['pet_year_age'] = 'none';
					this.columnStatus['branch_name'] = 'none';
					this.columnStatus['created_by'] = 'none';
					this.columnStatus['created_at'] = 'none';
				} else if (e === 'pet_year_age') { 
					this.columnStatus['id_member'] = 'none';
					this.columnStatus['pet_category'] = 'none';
					this.columnStatus['pet_name'] = 'none';
					this.columnStatus['pet_gender'] = 'none';
					this.columnStatus['branch_name'] = 'none';
					this.columnStatus['created_by'] = 'none';
					this.columnStatus['created_at'] = 'none';
				} else if (e === 'branch_name') { 
					this.columnStatus['id_member'] = 'none';
					this.columnStatus['pet_category'] = 'none';
					this.columnStatus['pet_name'] = 'none';
					this.columnStatus['pet_gender'] = 'none';
					this.columnStatus['pet_year_age'] = 'none';
					this.columnStatus['created_by'] = 'none';
					this.columnStatus['created_at'] = 'none';
				} else if (e === 'created_by') {
					this.columnStatus['id_member'] = 'none';
					this.columnStatus['pet_category'] = 'none';
					this.columnStatus['pet_name'] = 'none';
					this.columnStatus['pet_gender'] = 'none';
					this.columnStatus['pet_year_age'] = 'none';
					this.columnStatus['branch_name'] = 'none';
					this.columnStatus['created_at'] = 'none';
				} else {
					this.columnStatus['id_member'] = 'none';
					this.columnStatus['pet_category'] = 'none';
					this.columnStatus['pet_name'] = 'none';
					this.columnStatus['pet_gender'] = 'none';
					this.columnStatus['pet_year_age'] = 'none';
					this.columnStatus['branch_name'] = 'none';
					this.columnStatus['created_by'] = 'none';
				}

				this.paramUrlSetup.orderby = this.columnStatus[e];
				this.paramUrlSetup.column = e;
				this.getData();
			},
			getData: function() {
				$('#loading-screen').show();
				axios.get($('.baseUrl').val() + '/api/pasien', { params: this.paramUrlSetup, headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
					.then(resp => {
						this.listPasien = resp.data;
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
			getDetail: function() {
				$('#loading-screen').show();
				axios.get($('.baseUrl').val() + '/api/pasien/detail', { params: {id: this.idPasien }, headers: { 'Content-Type': 'application/json', 'Authorization': `Bearer ${token}` }})
					.then(resp => {
						const getPasienDetail = resp.data;
						this.nomorRegis = getPasienDetail[0].id_member;
						this.animalType = getPasienDetail[0].pet_category;
						this.animalName = getPasienDetail[0].pet_name;
						this.animalSex = getPasienDetail[0].pet_gender;
						this.animalSex = getPasienDetail[0].pet_gender;
						this.animalYear = getPasienDetail[0].pet_year_age;
						this.animalMonth = getPasienDetail[0].pet_month_age;
						this.ownerName = getPasienDetail[0].owner_name;
						this.ownerAddress = getPasienDetail[0].owner_address;
						this.ownerTelp = getPasienDetail[0].owner_phone_number;

						$('#modal-pasien').modal('show');
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
			validationForm: function() {
				this.touchedForm = true; this.beErr = false;
				this.animalTypeErr1 = (!this.animalType) ? true : false; 
				this.animalNameErr1 = (!this.animalName) ? true : false;
				this.animalSexErr1 = (!this.animalSex) ? true : false; 
				this.animalAgeErr1 = (!this.animalYear || !this.animalMonth) ? true : false; 
				this.ownerNameErr1 = (!this.ownerName) ? true : false; 
				this.ownerAddressErr1 = (!this.ownerAddress) ? true : false;
				this.ownerTelpErr1 = (!this.ownerTelp) ? true : false;
			},
			refreshVariable: function() {
				this.idPasien = '';     this.nomorRegis = '';
				this.animalType = '';   this.animalName = '';
				this.animalSex = '';	  this.ownerName = '';
				this.animalYear = null; this.animalMonth = null;
				this.ownerAddress = ''; this.ownerTelp = '';
				this.animalTypeErr1 = false; this.animalNameErr1 = false;
				this.animalSexErr1 = false; this.animalAgeErr1 = false;
				this.ownerNameErr1 = false; this.ownerAddressErr1 = false;
				this.ownerTelpErr1 = false
				this.beErr = false; this.touchedForm = false;
			}
		}
	});

});