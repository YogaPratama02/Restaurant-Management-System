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

Route::post('/cashier/order', 'CashierController@getOrder');
// Route::post('/cashier/order', 'CashierController@getOrder');
Route::post('/cashier/increase-quantity', 'CashierController@increaseQuantity');
Route::post('/cashier/decrease-quantity', 'CashierController@decreaseQuantity');
Route::get('/report/dataTable', 'ReportController@dataTable')->name('report.dataTable');
Route::get('/report/detail/{id}', 'ReportController@detail')->name('report.detail');

Route::get('/month', 'ReportController@month')->name('report.month');

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});
