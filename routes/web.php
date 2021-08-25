<?php

use Illuminate\Support\Facades\Route;
use Mike42\Escpos\Printer;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Illuminate\Http\Request;

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

Route::get('/gudang/cat-food', function () {
	return view('gudang.cat-food.index');
});

Route::get('/gudang/dog-food', function () {
	return view('gudang.dog-food.index');
});

Route::get('/gudang/animal-food', function () {
	return view('gudang.animal-food.index');
});

Route::get('/gudang/vitamin', function () {
	return view('gudang.vitamin.index');
});

Route::get('/gudang/pet-care', function () {
	return view('gudang.pet-care.index');
});

Route::get('/gudang/kandang', function () {
	return view('gudang.kandang.index');
});

Route::get('/gudang/aksesoris', function () {
	return view('gudang.aksesoris.index');
});

Route::get('/gudang/lain-lain', function () {
	return view('gudang.lain-lain.index');
});

Route::get('/pembayaran', function () {
	return view('pembayaran.index');
});

Route::get('/pembayaran/tambah', function () {
	return view('pembayaran.pembayaran-tambah');
});

Route::get('/pembayaran/edit/{id}', function () {
	return view('pembayaran.pembayaran-edit');
});

Route::get('/pembayaran/detail/{id}', function () {
	return view('pembayaran.pembayaran-detail');
});

Route::get('/laporan-keuangan/harian', function () {
	return view('laporan-keuangan.harian.index');
});

Route::get('/laporan-keuangan/harian/detail/{id}', function () {
	return view('laporan-keuangan.harian.detail-harian');
});

Route::get('/laporan-keuangan/mingguan', function () {
	return view('laporan-keuangan.mingguan.index');
});

Route::get('/laporan-keuangan/mingguan/detail/{id}', function () {
	return view('laporan-keuangan.mingguan.detail-mingguan');
});

Route::get('/laporan-keuangan/bulanan', function () {
	return view('laporan-keuangan.bulanan.index');
});

Route::get('/laporan-keuangan/bulanan/detail/{id}', function () {
	return view('laporan-keuangan.bulanan.detail-bulanan');
});

Route::get('/profil/{id}', function () {
	return view('profil.index');
});

Route::get('payment/printreceipt/{master_payment_id}', 'PaymentController@print_receipt');

Route::get('/unauthorized', function () {
	return view('errors.unauthorized');
});
