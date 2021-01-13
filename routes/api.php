<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
 */

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['middleware' => ['api']], function () {

    Route::post('masuk', 'UserController@login');

    Route::group(['middleware' => ['jwt.auth']], function () {

        Route::post('keluar', 'UserController@logout');

        //cabang
        Route::get('cabang', 'CabangController@index');
        Route::post('cabang', 'CabangController@create');
        Route::put('cabang', 'CabangController@update');
        Route::delete('cabang', 'CabangController@delete');

        //user management
        Route::get('user', 'UserController@index');
        Route::post('user', 'UserController@register');
        Route::put('user', 'UserController@update');

        Route::get('user/dokter', 'UserController@doctor');

        //pasien
        Route::get('pasien', 'PasienController@index');
        Route::post('pasien', 'PasienController@create');
        Route::put('pasien', 'PasienController@update');
        Route::delete('pasien', 'PasienController@delete');

        //kategori barang
        Route::get('kategori-barang', 'KategoriBarangController@index');
        Route::post('kategori-barang', 'KategoriBarangController@create');
        Route::put('kategori-barang', 'KategoriBarangController@update');
        Route::delete('kategori-barang', 'KategoriBarangController@delete');

        //satuan barang
        Route::get('satuan-barang', 'SatuanBarangController@index');
        Route::post('satuan-barang', 'SatuanBarangController@create');
        Route::put('satuan-barang', 'SatuanBarangController@update');
        Route::delete('satuan-barang', 'SatuanBarangController@delete');

        //daftar barang
        Route::get('daftar-barang', 'DaftarBarangController@index');
        Route::post('daftar-barang', 'DaftarBarangController@create');
        Route::put('daftar-barang', 'DaftarBarangController@update');
        Route::delete('daftar-barang', 'DaftarBarangController@delete');

        Route::get('daftar-barang/download-template', 'DaftarBarangController@download_template');

        //kategori jasa
        Route::get('kategori-jasa', 'KategoriJasaController@index');
        Route::post('kategori-jasa', 'KategoriJasaController@create');
        Route::put('kategori-jasa', 'KategoriJasaController@update');
        Route::delete('kategori-jasa', 'KategoriJasaController@delete');

        //daftar jasa
        Route::get('daftar-jasa', 'DaftarJasaController@index');
        Route::post('daftar-jasa', 'DaftarJasaController@create');
        Route::put('daftar-jasa', 'DaftarJasaController@update');
        Route::delete('daftar-jasa', 'DaftarJasaController@delete');

        //pembagian harga jasa
        Route::get('pembagian-harga-jasa', 'HargaJasaController@index');
        Route::post('pembagian-harga-jasa', 'HargaJasaController@create');
        Route::put('pembagian-harga-jasa', 'HargaJasaController@update');
        Route::delete('pembagian-harga-jasa', 'HargaJasaController@delete');
        Route::get('pembagian-harga-jasa/kategori-jasa', 'HargaJasaController@service_category');
        Route::get('pembagian-harga-jasa/nama-jasa', 'HargaJasaController@service_name');

        //pembagian harga barang
        Route::get('pembagian-harga-barang', 'HargaBarangController@index');
        Route::post('pembagian-harga-barang', 'HargaBarangController@create');
        Route::put('pembagian-harga-barang', 'HargaBarangController@update');
        Route::delete('pembagian-harga-barang', 'HargaBarangController@delete');
        Route::get('pembagian-harga-barang/kategori-barang', 'HargaBarangController@item_category');
        Route::get('pembagian-harga-barang/nama-barang', 'HargaBarangController@item_name');

        //rawat jalan
        Route::get('rawat-jalan', 'RawatJalanController@index');
        Route::post('rawat-jalan', 'RawatJalanController@create');
        Route::put('rawat-jalan', 'RawatJalanController@update');
        Route::delete('rawat-jalan', 'RawatJalanController@delete');

        //rawat inap
        Route::get('rawat-inap', 'RawatInapController@index');
        Route::post('rawat-inap', 'RawatInapController@create');
        Route::put('rawat-inap', 'RawatInapController@update');
        Route::delete('rawat-inap', 'RawatInapController@delete');

        //dokter rawat jalan
        Route::get('dokter-rawat-jalan', 'DokterRawatJalanController@index');
        Route::get('dokter-rawat-jalan/terima', 'DokterRawatJalanController@accept');
        Route::get('dokter-rawat-jalan/tolak', 'DokterRawatJalanController@decline');

        //dokter rawat inap
        Route::get('dokter-rawat-inap', 'DokterRawatInapController@index');
        Route::get('dokter-rawat-inap/terima', 'DokterRawatInapController@accept');
        Route::get('dokter-rawat-inap/tolak', 'DokterRawatInapController@decline');
    });
});

// Route::post('register', 'UserController@register');
// Route::post('login', 'UserController@login');
// Route::get('book', 'BookController@book');

// Route::get('bookall', 'BookController@bookAuth')->middleware('jwt.verify');
// Route::get('user', 'UserController@getAuthenticatedUser')->middleware('jwt.verify');
