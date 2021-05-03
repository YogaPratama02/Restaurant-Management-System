<?php

use Illuminate\Support\Facades\Route;
use App\SaleDetail;
use Facade\FlareClient\Http\Response;

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

Auth::routes(['register' => true, 'verify' => true]);

// Route::get()

// Route::get('/home', 'HomeController@index')->name('dashboard');
Route::group(['middleware' => ['role:members|cashier|super admin|admin', 'verified']], function () {
    // Cashier
    Route::get('/cashier', 'CashierController@index')->name('cashier.index');
    Route::get('/cashier/getTable', 'CashierController@getTables');
    Route::get('/cashier/getMenu/{category_id}', 'CashierController@getMenu');
    Route::post('/cashier/order', 'CashierController@getOrder');
    Route::get('/cashier/getSaleDetailsByTable/{table_id}', 'CashierController@getSaleDetailsByTable');
    Route::post('/cashier/confirmOrder', 'CashierController@confirmOrder');
    Route::post('/cashier/confirmAgain', 'CashierController@confirmAgain');
    Route::post('/cashier/decrease-quantity', 'CashierController@decreaseQuantity');
    Route::get('/cashier/note/{id}', 'CashierController@notes')->name('cashier.note');
    Route::post('/cashier/update', 'CashierController@requestNotes')->name('cashier.update');
    Route::post('/cashier/store/', 'CashierController@mejaPindah')->name('cashier.store');
    Route::post('/cashier/updateTable', 'CashierController@updateTable')->name('cashier.updatetable');
    Route::post('/cashier/voucher', 'CashierController@voucher');
    Route::post('/cashier/mejaPindah', 'CashierController@mejaPindah');
});
Route::group(['middleware' => ['role:cashier|super admin|admin', 'verified']], function () {
    // Cashier
    Route::post('/cashier/savePayment', 'CashierController@savePayment');
    Route::get('/cashier/showReceipt/{saleID}', 'CashierController@showReceipt');
    Route::get('/cashier/pdf/{saleID}', 'CashierController@pdf');

    //roombooking
    Route::get('/bookingroom', 'RoomBookingController@index')->name('roombooking.index');
    Route::get('/bookingroom/data', 'RoomBookingController@dataTable')->name('roombooking.data');
    Route::get('/bookingroom/create', 'RoomBookingController@create')->name('roombooking.create');
    Route::post('/bookingroom/store', 'RoomBookingController@store')->name('roombooking.store');
    Route::get('/bookingroom/edit/{id}', 'RoomBookingController@edit')->name('roombooking.edit');
    Route::put('/bookingroom/update/{id}', 'RoomBookingController@update')->name('roombooking.update');
    Route::delete('/bookingroom/delete/{id}', 'RoomBookingController@destroy')->name('roombooking.destroy');

    // kitchen
    Route::get('/kitchen', 'KitchenController@index')->name('kitchen.index');
    Route::get('/kitchen/update/{id}', function ($id) {
        $saledetail = SaleDetail::find($id);
        $saledetail->status = 'waiting';
        $saledetail->save();
    });

    Route::get('/kitchen/again/{id}', function ($id) {
        $saledetail = SaleDetail::find($id);
        $saledetail->status = 'finish';
        $saledetail->save();
    });
});

Route::group(['middleware' => ['role:super admin|admin', 'verified']], function () {
    Route::get('/management', function () {
        return view('pages.management.index');
    });
    Route::resource('category', 'CategoryController');
    Route::get('/category', 'CategoryController@index')->name('category.index');
    Route::get('/management/create', 'CategoryController@dataTable')->name('pages.management.category');

    // User
    Route::get('/user', 'UserController@index')->name('user.index');
    Route::get('/management/user/show', 'UserController@DataTable')->name('user.coba');
    Route::get('/management/user/create', 'UserController@create')->name('user.create');
    Route::post('/management/user/store', 'UserController@store')->name('user.store');
    Route::get('/management/user/edit/{id}', 'UserController@edit')->name('user.edit');
    Route::put('/management/user/update/{id}', 'UserController@update')->name('user.update');
    Route::delete('/management/user/destroy/{id}', 'UserController@destroy')->name('user.destroy');

    // Menu
    Route::get('/menu', 'MenuController@index')->name('menu.index');
    Route::get('/table/menu', 'MenuController@dataTable')->name('table.menu');
    Route::get('/menu/create', 'MenuController@create')->name('menu.create');
    Route::post('/menu/store', 'MenuController@store')->name('menu.store');
    Route::get('/edit/{id}', 'MenuController@edit')->name('menu.edit');
    Route::put('update/{id}', 'MenuController@update')->name('menu.update');
    Route::delete('delete/{id}', 'MenuController@destroy')->name('menu.destroy');

    // table
    Route::get('/table', 'TableController@index')->name('table.index');
    Route::get('/table/haha', 'TableController@dataTable')->name('table.coba');
    Route::get('/table/create', 'TableController@create')->name('table.create');
    Route::post('/table/store', 'TableController@store')->name('table.store');
    Route::get('/table/edit/{id}', 'TableController@edit')->name('table.edit');
    Route::put('/table/update/{id}', 'TableController@update')->name('table.update');
    Route::delete('table/delete/{id}', 'TableController@destroy')->name('table.destroy');

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

    //inventory
    Route::get('/inventory', 'InventoryController@index')->name('inventory.index');
    Route::get('/inventory/data', 'InventoryController@dataTable')->name('inventory.data');
    Route::get('/inventory/create', 'InventoryController@create')->name('inventory.create');
    Route::post('/inventory/store', 'InventoryController@store')->name('inventory.store');
    Route::get('/inventory/edit/{id}', 'InventoryController@edit')->name('inventory.edit');
    Route::put('/inventory/update/{id}', 'InventoryController@update')->name('inventory.update');
    Route::delete('/inventory/delete/{id}', 'InventoryController@destroy')->name('inventory.destroy');
    Route::get('/inventoryReport', 'InventoryController@reportInventory')->name('inventory.report');

    // voucher
    Route::get('/voucher', 'VoucherController@index')->name('voucher.index');
    Route::get('/voucher/create', 'VoucherController@create')->name('voucher.create');
    Route::get('/voucher/data', 'VoucherController@dataTable')->name('voucher.data');
    Route::post('/voucher/store', 'VoucherController@store')->name('voucher.store');
    Route::get('/voucher/edit/{id}', 'VoucherController@edit')->name('voucher.edit');
    Route::put('/voucher/update/{id}', 'VoucherController@update')->name('voucher.update');
    Route::delete('/voucher/delete/{id}', 'VoucherController@destroy')->name('voucher.destroy');
});

Route::group(['middleware' => ['role:super admin', 'verified']], function () {
    // report
    Route::get('/report', 'ReportController@index')->name('report.index');
    Route::get('/report/show', 'ReportController@show')->name('report.showReport');
    Route::get('/report/dataTable', 'ReportController@dataTable')->name('report.dataTable');
    Route::get('/report/dataDaily', 'ReportController@dataDaily')->name('report.dataDaily');
    Route::get('/report/typeDaily', 'ReportController@typeDaily')->name('report.typeDaily');
    Route::get('/report/resume', 'ReportController@resumeDaily')->name('report.resumeDaily');
    Route::get('/report/month', 'ReportController@month')->name('report.indexmonth');
    Route::get('/report/datamonth', 'ReportController@dataMonth')->name('report.dataMonth');
    Route::get('/report/menumonth', 'ReportController@menuMonth')->name('report.menuMonth');
    Route::get('/month/chart', 'ReportController@month')->name('report.chart');

    // report employe
    Route::get('/employee', 'ReportController@indexEmployee')->name('report.employee');
    Route::get('/employee/data', 'ReportController@employee')->name('report.employeeData');

    // report purchase
    Route::get('/report/purchase', 'ReportController@indexPurchase')->name('purchase.index');
    Route::get('report/datapurchase', 'ReportController@purchase')->name('purchase.data');

    // export excel
    Route::get('/report/show/export', 'ReportController@reportExcel')->name('report.excel');
    Route::get('/report/day/export', 'ReportController@dayExcel')->name('report.dayexcel');

    // customer report
    Route::get('/customer', 'ReportController@customers')->name('customer.index');

    // member report
    Route::get('/member', 'ReportController@member')->name('member.index');
});
