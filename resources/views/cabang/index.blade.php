@extends('layout.master')

@section('content')
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.2.1/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
  <div class="container">
      <div class="card mt-5">
          <div class="card-body">
              <a href="/cabang/tambah" class="btn btn-primary">Input Cabang Baru</a>
              <br/>
              <br/>
              <table class="table table-bordered table-hover table-striped">
                  <thead>
                      <tr>
                          <th>Kode Cabang</th>
                          <th>Nama Cabang</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach($branch as $b)
                      <tr>
                          <td>{{ $b->BranchCode }}</td>
                          <td>{{ $b->BranchName }}</td>
                          <td>
                              <a href="/cabang/edit/{{ $b->id }}" class="btn btn-warning">Edit</a>
                              <a href="/cabang/hapus/{{ $b->id }}" class="btn btn-danger">Hapus</a>
                          </td>
                      </tr>
                      @endforeach
                  </tbody>
              </table>
          </div>
      </div>
  </div>
</body>
@endsection
@section('script-content')
  
@endsection
@section('vue-content')
  
@endsection
