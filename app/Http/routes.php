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

Route::get('/', 'HomeController@index');

Route::get('/home', 'HomeController@index');

Route::get('/test_login', 'HomeController@test_login');

Route::get('main/stock', 'MainStockController@index');
Route::post('main/stock/objectData', 'MainStockController@objectData');

Route::get('main/search', 'MainSearchController@index');
Route::post('main/search/objectData', 'MainSearchController@objectData');
Route::get('main/search/detail/{id}','MainSearchController@detail');

Route::get('main/import', 'MainImportController@index');
Route::post('main/import/add_stock', 'MainImportController@store');

Route::get('main/export', 'MainExportController@index');
Route::get('main/export/detail/{id}','MainExportController@show');
Route::post('main/export/cut_stock/', 'MainExportController@store');
Route::get('main/export/stock_log/{id}','MainExportController@show_log');
Route::get('main/export/get_device/{id}','MainExportController@get_device');
Route::post('main/export/objectData', 'MainExportController@objectData');
Route::post('main/export/import_excel','MainExportController@store_import_excel');

Route::get('main/role','RoleController@index');
Route::post('main/role/objectData', 'RoleController@objectData');
Route::post('main/role/add_role', 'RoleController@add_role');
Route::get('main/role/detail/{id}','RoleController@show');
Route::get('main/role/delete/{id}','RoleController@delete_role');
Route::get('role_permission','RoleController@role_permission');
Route::post('role_permission/store','RoleController@role_permission_store');

Route::get('main/user','UserController@index');
Route::post('main/user/objectData', 'UserController@objectData');
Route::post('main/user/add_user', 'UserController@add_user');
Route::get('main/user/detail/{id}','UserController@show');

Route::get('masterdata/type_product', 'MasterDataTypeProductController@index');
Route::post('masterdata/add_type_product', 'MasterDataTypeProductController@store');
Route::get('masterdata/type_product/destroy/{id}','MasterDataTypeProductController@destroy');
Route::get('masterdata/type_product/detail/{id}','MasterDataTypeProductController@show');

Route::get('masterdata/treasury', 'MasterDataTreasuryController@index');
Route::post('masterdata/add_treasury', 'MasterDataTreasuryController@store');
Route::get('masterdata/treasury/destroy/{id}','MasterDataTreasuryController@destroy');
Route::get('masterdata/treasury/detail/{id}','MasterDataTreasuryController@show');