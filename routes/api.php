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
        Route::get('gudang', 'WarehouseController@index');
        Route::post('gudang', 'WarehouseController@create');
        Route::post('gudang/update', 'WarehouseController@update');
        Route::delete('gudang', 'WarehouseController@delete');

        Route::get('gudang/template', 'WarehouseController@download_template_excel');
        Route::get('gudang/generate', 'WarehouseController@download_report_excel');
        Route::post('gudang/upload', 'WarehouseController@upload_excel');

        Route::get('daftar-barang-batas', 'WarehouseController@index_limit');

        //Payment
        Route::get('payment', 'PaymentController@index');
        Route::post('payment', 'PaymentController@create');
        Route::delete('payment', 'PaymentController@delete');

        Route::get('payment/filteritem', 'PaymentController@filter_item');
        Route::get('payment/generate', 'PaymentController@download_report_excel');
        Route::get('payment/printreceipt', 'PaymentController@print_receipt');

        Route::get('daily-finance-report', 'DailyFinancialReportController@index');
        Route::get('daily-finance-report/generate', 'DailyFinancialReportController@download_report');

        Route::get('weekly-finance-report', 'WeeklyFinancialReportController@index');
        Route::get('weekly-finance-report/generate', 'WeeklyFinancialReportController@download_report');

        Route::get('monthly-finance-report', 'MonthlyFinancialReportController@index');
        Route::get('monthly-finance-report/generate', 'MonthlyFinancialReportController@download_report');
    });
});
