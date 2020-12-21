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
                <a href="/cabang" class="btn btn-primary">Kembali</a>
                <br/>
                <br/>
                

                <form method="post" action="/cabang/update/{{ $branch->id }}">

                    {{ csrf_field() }}
                    {{ method_field('PUT') }}

                    <div class="form-group">
                        <label>Kode Cabang</label>
                        <input type="text" name="KodeCabang" class="form-control" placeholder="Kode Cabang .." value=" {{ $branch->BranchCode }}">

                        @if($errors->has('KodeCabang'))
                            <div class="text-danger">
                                {{ $errors->first('KodeCabang')}}
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <label>Nama Cabang</label>
                        <textarea name="NamaCabang" class="form-control" placeholder="Nama Cabang .."> {{ $branch->BranchName }} </textarea>

                         @if($errors->has('NamaCabang'))
                            <div class="text-danger">
                                {{ $errors->first('NamaCabang')}}
                            </div>
                        @endif

                    </div>

                    <div class="form-group">
                        <input type="submit" class="btn btn-success" value="Simpan">
                    </div>

                </form>

            </div>
        </div>
    </div>
</body>
@endsection
@section('script-content')
  
@endsection
@section('vue-content')
  
@endsection
