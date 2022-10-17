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

Route::group(['prefix' => 'v1', 'as' => 'api.', 'namespace' => 'App\Http\Controllers',], function () {
    Route::get('properties/get', 'PropertyController@get');
    Route::post('properties/create', 'PropertyController@create');
    Route::post('properties/update', 'PropertyController@update');
    Route::get('properties/delete', 'PropertyController@delete');

  });
  