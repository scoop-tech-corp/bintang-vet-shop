<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Vet Dashboard | Register</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.7 -->
  <link rel="stylesheet" href="{{ asset('bootstrap/dist/css/bootstrap.min.css') }}">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{ asset('bower_components/font-awesome/css/font-awesome.min.css') }}">
  <!-- Ionicons -->
  <link rel="stylesheet" href="{{ asset('bower_components/Ionicons/css/ionicons.min.css') }}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{ asset('dist/css/AdminLTE.min.css') }}">
  <!-- iCheck -->
  <link rel="stylesheet" href="{{ asset('plugins/iCheck/square/blue.css') }}">
  <!-- bootstrap datepicker -->
  <link rel="stylesheet" href="{{ asset('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
  <!-- jQuery 3 -->
  <script src="{{ asset('bower_components/jquery/dist/jquery.min.js') }}"></script>

  <!-- Vue JS -->
  <script src="{{ asset('vuejs/vue.js') }}"></script>

  <!-- Axios  -->
  <script src="{{ asset('vuejs/axios.js') }}"></script>

  <!-- Google Font -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">

  <!-- Register Custom Css -->
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/register.css') }}">
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/alert-custom.css') }}">
  <link rel="stylesheet" type='text/css' href="{{ asset('main/css/input-custom.css') }}">
</head>
<body class="hold-transition register-page">
<div id="register-app" class="register-box">
  <input ref="baseUrl" type="hidden" value="{{ url('/') }}"/>
  <div class="register-logo">
    <a href="#"><b>R</b>EGISTER</a>
  </div>
  <div v-if="showAlert" class="alert alert-dismissible"
    v-bind:class="{ 'alert-success': isSuccess, 'alert-danger': !isSuccess }">
    <button type="button" @click="showAlert = false" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h4><i class="icon fa" v-bind:class="{ 'fa-check': isSuccess, 'fa-ban': !isSuccess }"></i> Alert!</h4>
    <div v-html="message"></div>
  </div>
  <div class="register-box-body">
    <p class="login-box-msg">Register a new account admin</p>

    <form>
      <div class="form-group has-feedback">
        <input type="text" class="form-control" placeholder="Username" v-model="form.username">
        <span class="glyphicon glyphicon-user form-control-feedback"></span>
      </div>
      <div class="row form-group has-feedback">
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="First Name" v-model="form.firstname">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="col-md-6">
          <input type="text" class="form-control" placeholder="Last Name" v-model="form.lastname">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
      </div>
      <div class="form-group has-feedback container-datepicker">
        <input type="text" class="form-control txt-datepicker" placeholder="Date Of Birth">
        <span class="glyphicon glyphicon-calendar form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="email" class="form-control" placeholder="Email" v-model="form.email">
        <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Password" v-model="form.password">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="password" class="form-control" placeholder="Confirm Password" v-model="form.confpassword">
        <span class="glyphicon glyphicon-lock form-control-feedback"></span>
      </div>
      <div class="form-group has-feedback">
        <input type="number" min="0" maxlength="8" class="form-control" placeholder="Phone Number" v-model="form.phone">
        <span class="glyphicon glyphicon-earphone form-control-feedback"></span>
      </div>
      <div class="form-group">
        <label for="photo">Photo</label>
        <input type="file" id="input-photo" @change="onFileChange">

        <p class="help-block">validation text in here.</p>
      </div>
      <div class="row form-group">
        <div class="col-md-12">
          <button type="button" class="btn btn-primary btn-block btn-flat" @click="onSubmit">Register</button>
        </div>
      </div>
    </form>

    <div class="row">
      <div class="col-xs-12">
        <a href="{{ url('/login') }}" class="text-center">I already have account admin</a>
      </div>
    </div>
  </div>

  <!-- /.form-box -->
</div>
<!-- /.register-box -->

<!-- Bootstrap 3.3.7 -->
<script src="{{ asset('bootstrap/dist/js/bootstrap.min.js') }}"></script>
<!-- bootstrap datepicker -->
<script src="{{ asset('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<!-- iCheck -->
<script src="{{ asset('plugins/iCheck/icheck.min.js') }}"></script>
<script>
  $(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' /* optional */
    });

    //Date picker set comment because datepicker declare in vuejs mounted
    // $('.txt-datepicker').datepicker();
  });
</script>

<script src="{{ asset('main/js/auth/register-vue.js') }}"></script>
</body>
</html>
