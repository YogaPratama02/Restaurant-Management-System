<?php

use App\Http\Controllers\CategoryController;
use Illuminate\Support\Facades\Route;
use Carbon\Carbon;
use App\SaleDetail;

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

Route::get('/', 'HomeController@show');

Auth::routes(['register' => true, 'reset' => false]);

// Route::get('/home', 'HomeController@index')->name('dashboard');
Route::middleware(['auth'])->group(function () {
    // Cashier
    Route::get('/cashier/index', 'CashierController@index')->name('cashier.index');
    Route::get('/cashier/getTable', 'CashierController@getTables');
    Route::get('/cashier/getMenu/{category_id}', 'CashierController@getMenu');
    Route::post('/cashier/order', 'CashierController@getOrder');
    Route::get('/cashier/getSaleDetailsByTable/{table_id}', 'CashierController@getSaleDetailsByTable');
    Route::post('/cashier/confirmOrder', 'CashierController@confirmOrder');
    Route::post('/cashier/confirmAgain', 'CashierController@confirmAgain');
    // Route::post('/cashier/increase-quantity', 'CashierController@increaseQuantity');
    Route::post('/cashier/decrease-quantity', 'CashierController@decreaseQuantity');
    Route::post('/cashier/savePayment', 'CashierController@savePayment');
    Route::get('/cashier/showReceipt/{saleID}', 'CashierController@showReceipt');
    Route::get('/cashier/jsonReceipt/{saleID}', 'CashierController@jsonReceipt');
});

Route::middleware(['auth', 'VerifyAdmin'])->group(function () {
    Route::get('/management', function () {
        return view('pages.management.index');
    });

    Route::resource('category', 'CategoryController');
    Route::get('/management/category', 'CategoryController@index');
    Route::get('/management/create', 'CategoryController@dataTable')->name('pages.management.category');

    // User
    Route::get('/management/user', 'UserController@index');
    Route::get('/management/user/show', 'UserController@DataTable')->name('user.coba');
    Route::get('/management/user/create', 'UserController@create')->name('user.create');
    Route::post('/management/user/store', 'UserController@store')->name('user.store');
    Route::get('/management/user/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::put('/management/user/update/{id}', 'UserController@update')->name('user.update');
    Route::delete('/management/user/destroy/{id}', 'UserController@destroy')->name('user.destroy');

    // Menu
    Route::get('/management/menu', 'MenuController@index')->name('pages.menu.index');
    Route::get('/table/menu', 'MenuController@dataTable')->name('table.menu');
    Route::get('/menu/create', 'MenuController@create')->name('menu.create');
    Route::post('/menu/store', 'MenuController@store')->name('menu.store');
    Route::get('/edit/{id}', 'MenuController@edit')->name('menu.edit');
    Route::put('update/{id}', 'MenuController@update')->name('menu.update');
    Route::delete('delete/{id}', 'MenuController@destroy')->name('menu.destroy');

    // kitchen
    Route::get('/kitchen', 'KitchenController@index')->name('kitchen.index');
    Route::get('/kitchen/update/{id}', function ($id) {
        $saledetail = SaleDetail::find($id);
        // dd($saledetail);
        $saledetail->status = 'waiting';
        $saledetail->save();
    });

    Route::get('/kitchen/again/{id}', function ($id) {
        $saledetail = SaleDetail::find($id);
        $saledetail->status = 'finish';
        $saledetail->save();
    });

    // table
    Route::get('/management/table', 'TableController@index')->name('table.index');
    Route::get('/table/haha', 'TableController@dataTable')->name('table.coba');
    Route::get('/table/create', 'TableController@create')->name('table.create');
    Route::post('/table/store', 'TableController@store')->name('table.store');
    Route::get('/table/edit/{id}', 'TableController@edit')->name('table.edit');
    Route::put('/table/update/{id}', 'TableController@update')->name('table.update');
    Route::delete('table/delete/{id}', 'TableController@destroy')->name('table.destroy');

    // report
    Route::get('/report', 'ReportController@index')->name('report.index');
    Route::get('/report/show', 'ReportController@show')->name('report.showReport');
    Route::get('/report/dataTable', 'ReportController@dataTable')->name('report.dataTable');
    Route::get('/report/detail/{id}', 'ReportController@detail')->name('report.detail');
    Route::get('/report/resume', 'ReportController@resume')->name('report.resume');
    Route::get('/month', 'ReportController@month')->name('report.month');
    Route::get('/employee', 'ReportController@employee')->name('report.employee');

    // report purchase
    Route::get('/purchase', 'ReportController@purchase')->name('purchase.index');
    Route::get('/totalpurchase', 'ReportController@purchaseTotal')->name('purchase.total');

    // Route::get('/report/charts', 'ReportController@charts')->name('report.showReportCharts');

    // export excel
    Route::get('/report/show/export', 'ReportController@reportExcel');

    //inventory
    Route::get('/inventory', 'InventoryController@index')->name('inventory.index');
    Route::get('/inventory/data', 'InventoryController@dataTable')->name('inventory.data');
    Route::get('/inventory/create', 'InventoryController@create')->name('inventory.create');
    Route::post('/inventory/store', 'InventoryController@store')->name('inventory.store');
    Route::get('/inventory/edit/{id}', 'InventoryController@edit')->name('inventory.edit');
    Route::put('/inventory/update/{id}', 'InventoryController@update')->name('inventory.update');
    Route::delete('/inventory/delete/{id}', 'InventoryController@destroy')->name('inventory.destroy');
    Route::get('/inventoryReport', 'InventoryController@reportInventory')->name('inventory.report');

    //PPN
    Route::get('/ppn', 'PpnController@index')->name('ppn.index');
    Route::get('/ppn/data', 'PpnController@dataTable')->name('ppn.data');
    Route::get('/ppn/create', 'PpnController@create')->name('ppn.create');
    Route::post('/ppn/store', 'PpnController@store')->name('ppn.store');
    Route::get('/ppn/edit/{id}', 'PpnController@edit')->name('ppn.edit');
    Route::put('/ppn/update/{id}', 'PpnController@update')->name('ppn.update');
    Route::delete('/ppn/delete/{id}', 'PpnController@destroy')->name('ppn.destroy');

    //purchase
    Route::get('/supplier', 'SupplierController@index')->name('supplier.index');
    Route::get('/supplier/data', 'SupplierController@dataTable')->name('supplier.data');
    Route::get('/supplier/create', 'SupplierController@create')->name('supplier.create');
    Route::post('/supplier/store', 'SupplierController@store')->name('supplier.store');
    Route::get('/supplier/edit/{id}', 'SupplierController@edit')->name('supplier.edit');
    Route::put('/supplier/update/{id}', 'SupplierController@update')->name('supplier.update');
    Route::delete('/supplier/delete/{id}', 'SupplierController@destroy')->name('supplier.destroy');

    // inventorymenu
    Route::get('/inventmenu', 'InventoryMenuController@index')->name('inventmenu.index');
    Route::get('/inventmenu/data', 'InventoryMenuController@dataTable')->name('inventmenu.data');
    Route::get('/inventmenu/create', 'InventoryMenuController@create')->name('inventmenu.create');
    Route::post('/inventmenu/store', 'InventoryMenuController@store')->name('inventmenu.store');
    Route::get('/inventmenu/edit/{id}', 'InventoryMenuController@edit')->name('inventmenu.edit');
    Route::put('/inventmenu/update/{id}', 'InventoryMenuController@update')->name('inventmenu.update');
    Route::delete('/inventmenu/delete/{id}', 'InventoryMenuController@destroy')->name('inventmenu.destroy');

    //roombooking
    Route::get('/bookingroom', 'RoomBookingController@index')->name('roombooking.index');
    Route::get('/bookingroom/data', 'RoomBookingController@dataTable')->name('roombooking.data');
    Route::get('/bookingroom/create', 'RoomBookingController@create')->name('roombooking.create');
    Route::post('/bookingroom/store', 'RoomBookingController@store')->name('roombooking.store');
    Route::get('/bookingroom/edit/{id}', 'RoomBookingController@edit')->name('roombooking.edit');
    Route::put('/bookingroom/update/{id}', 'RoomBookingController@update')->name('roombooking.update');
    Route::delete('/bookingroom/delete/{id}', 'RoomBookingController@destroy')->name('roombooking.destroy');
});
