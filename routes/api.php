<?php

use Illuminate\Http\Request;

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


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/administrator/users', 'AdminController@user')->name('users');

//Route::get('v1/batch/show-fico/{email}/{array}', 'API\FicoController@batchShow', function ($email, $array) {})->middleware('auth:api');

Route::get('v1/single-batch/return-fico/{email}/{array}', 'API\FicoController@singleShow', function ($email, $array) {})->middleware('auth:api');