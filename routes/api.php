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
        Route::delete('user', 'UserController@delete');

        Route::get('user/dokter', 'UserController@doctor');

        Route::post('user/upload-image', 'ProfileController@upload_photo_profile');
        Route::get('user/profile', 'ProfileController@get_data_user');
        Route::put('user/profile', 'ProfileController@update_data_user');

        //Warehouse
        Route::post('gudang','WarehouseController@create');
        Route::put('gudang','WarehouseController@update');
        Route::delete('gudang','WarehouseController@delete');

        Route::get('gudang/template','WarehouseController@download_template_excel');

        //cat food
        Route::get('gudang/cat-food','CatFoodController@index');
        Route::post('gudang/cat-food/upload','CatFoodController@upload');

        //dog food
        Route::get('gudang/dog-food','DogFoodController@index');
        Route::post('gudang/dog-food/upload','DogFoodController@upload');

        //animal food
        Route::get('gudang/animal-food','AnimalFoodController@index');
        Route::post('gudang/animal-food/upload','AnimalFoodController@upload');

    });
});
