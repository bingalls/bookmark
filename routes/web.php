<?php

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
    return view('item');
});

Route::post('item/add', 'ItemController@add')->name('item.add');
Route::get('item/get', 'ItemController@get')->name('item.get');
Route::get('item/update', 'ItemController@update')->name('item.update');
Route::get('item/action', 'ItemController@itemAction')->name('item.action');
Route::get('item/delete', 'ItemController@delete')->name('item.delete');
