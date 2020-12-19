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

Route::get('/login', function () {
	return view('/auth/login');
});

Route::get('/register', function () {
	return view('/auth/register');
});

Route::get('/cabang', function () {
	return view('cabang');
});

Route::get('/user', function () {
	return view('user');
});

Route::get('/pasien', function () {
	return view('pasien');
});

Route::get('/dokter', function () {
	return view('dokter');
});

Route::get('/periksa', function () {
	return view('periksa');
});

Route::get('/tindakan', function () {
	return view('tindakan');
});

Route::get('/gudang1', function () {
	return view('gudang1');
});

Route::get('/gudang2', function () {
	return view('gudang2');
});

Route::get('/pembayaran', function () {
	return view('pembayaran');
});

Route::get('/kunjungan', function () {
	return view('kunjungan');
});
