@extends('layout.master')

@section('content')
<div class="box box-info" id="form-hasil-pemeriksaan-app">
  <div class="box-header ">
    <h3 class="box-title title-form-hasil-pemeriksaan"></h3>
  </div>

  <div class="box-body">
    <div class="btn-back-to-list">
      <span class="fa fa-arrow-left"></span> <span class="text">List Hasil Pemeriksaan</span>
    </div>
    <div class="m-t-25px">
      <form class="form-pembarayan-tambah">
        <div class="col-md-12 m-b-20px form-cari-pasien">
          <label for="selectedPasien">Cari Pasien</label>
          <select id="selectedPasien" class="form-control" style="width: 100%">
          </select>
          <div id="pasienErr1" class="validate-error"></div>
        </div>
        <div class="col-md-6">
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Nomor Registrasi</div>
            <div id="nomorRegistrasiTxt" class="p-left-10px"></div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Nomor Pasien</div>
            <div id="nomorPasienTxt" class="p-left-10px"></div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Jenis Hewan</div>
            <div id="jenisHewanTxt" class="p-left-10px"></div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Nama Hewan</div>
            <div id="namaHewanTxt" class="p-left-10px"></div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Jenis Kelamin</div>
            <div id="jenisKelaminTxt" class="p-left-10px"></div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Nama Pendaftar</div>
            <div id="namaPendaftarTxt" class="p-left-10px"></div>
          </div>
        </div>
        <div class="col-md-6">
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Usia Hewan</div>
            <div class="p-left-10px">
              <span id="usiaHewanTahunTxt"></span>&nbsp;&nbsp;
              <span id="usiaHewanBulanTxt"></span>
            </div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Nama Pemilik</div>
            <div id="namaPemilikTxt" class="p-left-10px"></div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Alamat Pemilik</div>
            <div id="alamatPemilikTxt" class="p-left-10px"></div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Nomor HP Pemilik</div>
            <div id="nomorHpPemilikTxt" class="p-left-10px" style="width: 75%"></div>
          </div>
          <div class="d-flex m-b-10px">
            <div class="label-detail-div">Keluhan</div>
            <div id="keluhanTxt" class="p-left-10px" style="width: 75%"></div>
          </div>
        </div>
        <div class="col-md-12 m-b-10px">
          <div class="label-detail-div">Anamnesa</div>
          <textarea id="anamnesa" class="form-control" placeholder="Masukan Anamnesa"></textarea>
          <div id="anamnesaErr1" class="validate-error"></div>
        </div>
        <div class="col-md-12 m-b-10px">
          <div class="label-detail-div">Sign</div>
          <textarea id="sign" class="form-control" placeholder="Masukan Sign"></textarea>
          <div id="signErr1" class="validate-error"></div>
        </div>
        <div class="col-md-12 m-b-10px">
          <div class="label-detail-div">Diagnosa</div>
          <textarea id="diagnosa" class="form-control" placeholder="Masukan Diagnosa"></textarea>
          <div id="diagnosaErr1" class="validate-error"></div>
        </div>
        <div class="col-md-12 m-b-10px">
          <label for="foto">Foto Kondisi Pasien</label>
          <div class="dropzone" id="fotoKondisiPasien"></div>
        </div>
        <div class="col-md-12 m-b-10px">
          <div class="label-detail-div m-b-10px">Jasa</div>
          <select id="selectedJasa" class="form-control" style="width: 100%; margin-bottom: 10px" multiple="multiple"></select>
          <div class="table-responsive">
            <table class="table table-striped text-nowrap table-list-jasa">
              <thead>
                <tr>
                  <th>No</th>
                  <th class="tgl-edit">Tanggal</th>
                  <th class="dibuat-edit">Dibuat Oleh</th>
                  <th>Jenis Layanan</th>
                  <th>Kategori Jasa</th>
                  <th>Jumlah</th>
                  <th>Harga Satuan</th>
                  <th>Harga Keseluruhan</th>
                  <th>Hapus</th>
                </tr>
              </thead>
              <tbody id="list-selected-jasa"></tbody>
            </table>
          </div>
        </div>
        <div class="col-md-12 m-b-10px">{{-- Barang Rawat Jalan --}}
          <div class="label-detail-div m-b-10px">Barang</div>
          <select id="selectedBarang" class="form-control" style="width: 100%; margin-bottom: 10px" multiple="multiple"></select>
          <div class="table-responsive">
            <table class="table table-striped text-nowrap table-list-barang">
              <thead>
                <tr>
                  <th>No</th>
                  <th class="tgl-edit">Tanggal</th>
                  <th class="dibuat-edit">Dibuat Oleh</th>
                  <th>Nama Barang</th>
                  <th>Kategori Barang</th>
                  <th>Satuan Barang</th>
                  <th>Jumlah</th>
                  <th>Harga Satuan</th>
                  <th>Harga Keseluruhan</th>
                  <th>Hapus</th>
                </tr>
              </thead>
              <tbody id="list-selected-barang"></tbody>
            </table>
          </div>
        </div>

        <div class="col-md-12 m-b-10px">
          <label for="rawat inap" style="margin-right: 80px;">Rawat Inap</label>
          <span style="padding-right: 51px;"><input type="radio" name="radioRawatInap" value=1 /> Ya</span>
          <span><input type="radio" name="radioRawatInap" value=0 /> Tidak</span>
          <div id="rawatInapErr1" class="validate-error"></div>
        </div>

        <div class="col-md-12 m-b-10px form-deskripsi-kondisi-pasien">
          <div class="label-detail-div">Deskripsi Kondisi Pasien</div>
          <textarea id="descriptionCondPasien" class="form-control" placeholder="Masukan Deskripsi Kondisi Pasien"></textarea>
          <div id="descriptionCondPasienErr1" class="validate-error"></div>
        </div>

        <div class="col-md-12 m-b-10px">
          <div class="table-responsive form-group">
            <table class="table table-striped table-deskripsi-kondisi-pasien">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                  <th>Dibuat Oleh</th>
                  <th>Deskripsi</th>
                </tr>
              </thead>
              <tbody id="list-deskripsi-kondisi-pasien"></tbody>
            </table>
          </div>
        </div>

        <div class="col-md-12 m-b-10px">
          <label for="barang" style="margin-right: 27px;">Status Pemeriksaan</label>
          <span style="padding-right: 23px;"><input type="radio" name="radioStatusPemeriksa" value=1 /> Selesai</span>
          <span><input type="radio" name="radioStatusPemeriksa" value=0 /> Belum</span>
          <div id="statusPemeriksaErr1" class="validate-error"></div>
        </div>
      </form>
    </div>

    <div class="col-md-12 m-t-25px">
      <div id="beErr" class="validate-error"></div>
    </div>

    <div class="col-md-12 m-t-25px">
      <button id="btnSubmitHasilPemeriksaan" type="button" class="btn btn-primary pull-right">Simpan</button>
      <button id="btnKembali" type="button" class="btn btn-default pull-right m-r-10px">Kembali</button>
    </div>
  </div>
  
</div>

@component('layout.message-box') @endcomponent
@component('layout.modal-confirmation') @endcomponent

@endsection
@section('script-content')
  <script src="{{ asset('plugins/dropzone/dropzone.js') }}"></script>
  <script src="{{ asset('main/js/hasil-pemeriksaan/form-hasil-pemeriksaan.js') }}"></script>
@endsection
@section('css-content')
  <link rel="stylesheet" type='text/css' href="{{ asset('plugins/dropzone/dropzone.css') }}">
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/hasil-pemeriksaan.css') }}">
@endsection
@section('vue-content')@endsection