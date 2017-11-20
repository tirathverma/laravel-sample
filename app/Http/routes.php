<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

 

Route::auth();





Route::get('/', function () {
    //return view('welcome');
    return redirect('invoices');
});


Route::post('dologin', 'Auth\LoginController@dologin');
Route::get('/change-password', 'UserController@changePassword');
Route::post('/store-change-password', 'UserController@storeChangePassword');

Route::auth();
 

Route::get('/dashboard', 'InvoiceController@index');
Route::resource('employee', 'EmployeeController');
Route::resource('settings', 'SettingController');
Route::resource('customers', 'CustomerController');


Route::get('stocks/import-form', 'StockController@importForm');
Route::post('stocks/save-import', 'StockController@saveImport');
Route::get('stocks/export', 'StockController@export');
Route::get('stocks/sample-export', 'StockController@sampleExport');
Route::get('stocks/history/{id}', 'StockController@history');

Route::resource('stocks', 'StockController');


Route::post('invoices/paid-status', 'InvoiceController@paidStatus'); 
Route::get('invoices/voided/{id}', 'InvoiceController@voided'); 
Route::get('invoices/search', 'InvoiceController@search');
Route::get('invoices/customer-search', 'InvoiceController@customerSearch');
Route::get('invoices/search-company', 'InvoiceController@companySearch');
Route::get('invoices/invoice-print/{id}', 'InvoiceController@invoicePrint');
Route::resource('invoices', 'InvoiceController');


Route::get('reports/export-sales', 'ReportController@exportSales');
Route::get('reports/export-customers', 'ReportController@exportCustomers');
Route::get('reports/export-stocks', 'ReportController@exportStocks');

Route::get('reports/search', 'ReportController@search');
Route::get('reports/customer-search', 'ReportController@customerSearch');
Route::get('reports/stocks', 'ReportController@stocks');
Route::get('reports/customers', 'ReportController@customers');
Route::get('reports/sales', 'ReportController@sales');
Route::resource('reports', 'ReportController');


Route::group(['namespace' => 'api','prefix' => 'api'], function () {
    	Route::post('users/login', 'UserController@authenticate');
		Route::resource('users', 'UserController');
		Route::resource('customers', 'CustomerController');
		Route::resource('stocks', 'StockController');
		

		Route::post('invoices/change-status', 'InvoiceController@changeStatus');
		Route::get('invoices/settings', 'InvoiceController@settings');
		Route::get('invoices/search', 'InvoiceController@search');
		Route::post('invoices/destroy/{id}', 'InvoiceController@destroy');
		Route::resource('invoices', 'InvoiceController');
});	


 
