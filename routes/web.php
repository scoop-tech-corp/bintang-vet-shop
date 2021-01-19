<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
	return view('index');
});

Route::get('/masuk', function () {
	return view('auth.login');
});

Route::get('/user', function () {
	return view('user.index');
});

Route::get('/cabang', function () {
	return view('cabang.index');
});

Route::get('/pasien', function () {
	return view('pasien.index');
});

Route::get('/kategori-barang', function () {
	return view('gudang.kategori-barang.index');
});

Route::get('/satuan-barang', function () {
	return view('gudang.satuan-barang.index');
});

Route::get('/daftar-barang', function () {
	return view('gudang.daftar-barang.index');
});

Route::get('/pembagian-harga-barang', function () {
	return view('gudang.pembagian-harga.index');
});

Route::get('/kategori-jasa', function () {
	return view('layanan.kategori-jasa.index');
});

Route::get('/daftar-jasa', function () {
	return view('layanan.daftar-jasa.index');
});

Route::get('/pembagian-harga-jasa', function () {
	return view('layanan.pembagian-harga.index');
});

// Route::get('/rawat-inap', function () {
// 	return view('pendaftaran.rawat-inap.index');
// });

Route::get('/pendaftaran', function () {
	return view('pendaftaran.rawat-jalan.index');
});

Route::get('/dokter', function () {
	return view('dokter.rawat-jalan.index');
});

// Route::get('/dokter-rawat-inap', function () {
// 	return view('dokter.rawat-inap.index');
// });


// Route::get('/cabang','BranchController@index');

// Route::get('/register', function () {
// 	return view('/auth/register');
// });

// Route::get('/getDataBranch','BranchController@getDataBranch');

// Route::get('/cabang/tambah','BranchController@tambah');

// Route::post('/cabang/store','BranchController@store');

// Route::get('/cabang/edit/{id}', 'BranchController@edit');

// Route::post('/cabang/update', 'BranchController@update');

// Route::post('/cabang/hapus', 'BranchController@delete');

// Route::get('/dokter', function () {
// 	return view('dokter.index');
// });

// Route::get('/periksa', function () {
// 	return view('periksa.index');
// });

// Route::get('/tindakan', function () {
// 	return view('tindakan.index');
// });

// Route::get('/pembayaran', function () {
// 	return view('pembayaran.index');
// });

// Route::get('/kunjungan', function () {
// 	return view('kunjungan.index');
// });
