@extends('layout.master')

@section('content')

<div class="box box-info" id="pembagian-harga-app">
  <div class="box-body">
    <div class="nav-tabs-custom">
      <ul class="nav nav-tabs">
        <li class="active"><a href="#tab_daftarBarang" data-toggle="tab" aria-expanded="true">Daftar Barang</a></li>        
        <li class=""><a href="#tab_daftarJasa" data-toggle="tab" aria-expanded="false">Daftar Jasa</a></li>
      </ul>
      <div class="tab-content">
        <div class="tab-pane active" id="tab_daftarBarang">
          @component('gudang.pembagian-harga.harga-barang.index') @endcomponent
        </div>
        <div class="tab-pane" id="tab_daftarJasa">
          @component('gudang.pembagian-harga.harga-jasa.index') @endcomponent
        </div>
      </div>
    </div>
  </div>
</div>

@component('layout.modal-confirmation') @endcomponent
@component('layout.message-box') @endcomponent

@endsection
@section('script-content')
  <script src="{{ asset('main/js/gudang/pembagian-harga/harga-barang.js') }}"></script>
  <script src="{{ asset('main/js/gudang/pembagian-harga/harga-jasa.js') }}"></script>
@endsection
@section('vue-content') @endsection