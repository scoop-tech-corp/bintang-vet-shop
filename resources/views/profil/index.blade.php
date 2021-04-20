@extends('layout.master')

@section('content')
<div class="row">
  <div class="col-md-3">
    <!-- Profile Image -->
    <div class="box box-info">
      <div class="box-body box-profile">
        <img class="profile-user-img img-responsive img-circle" src="{{ asset('assets/image/avatar-default.svg') }}" alt="User profile picture">
        <h3 class="profile-username text-center">Nina Mcintire</h3>
        <p class="text-muted text-center">Software Engineer</p>
        <ul class="list-group list-group-unbordered">
          <li class="list-group-item">
            <b>Followers</b> <a class="pull-right">1,322</a>
          </li>
          <li class="list-group-item">
            <b>Following</b> <a class="pull-right">543</a>
          </li>
          <li class="list-group-item">
            <b>Friends</b> <a class="pull-right">13,287</a>
          </li>
        </ul>

        <a href="#" class="btn btn-primary btn-block"><b>Follow</b></a>
      </div>
      <!-- /.box-body -->
    </div>
  </div>
  <div class="col-md-9">
    <div class="box box-info">
      <div class="box-header">
        {{-- <h3 class="box-title">Pembayaran</h3> --}}
      </div>
    
      <div class="box-body">
        KANAN
      </div>
    </div>
  </div>
</div>
@endsection

@section('script-content')
  {{-- <script src="{{ asset('main/js/profil/profil.js') }}"></script> --}}
@endsection
@section('css-content')
  {{-- <link rel="stylesheet" type='text/css' href="{{ asset('main/css/profil.css') }}"> --}}
@endsection
@section('vue-content')@endsection