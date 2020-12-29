<?php

use Illuminate\Http\Request;
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

        //pasien
        Route::get('pasien', 'PasienController@index');
        Route::get('pasien/detail', 'PasienController@detail');
        Route::post('pasien', 'PasienController@create');
        Route::put('pasien', 'PasienController@update');
        Route::delete('pasien', 'PasienController@delete');
    });
});

// Route::post('register', 'UserController@register');
// Route::post('login', 'UserController@login');
// Route::get('book', 'BookController@book');

// Route::get('bookall', 'BookController@bookAuth')->middleware('jwt.verify');
// Route::get('user', 'UserController@getAuthenticatedUser')->middleware('jwt.verify');