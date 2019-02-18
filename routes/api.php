<?php

use Illuminate\Http\Request;


//Route::get('/administrator/users', 'AdminController@user')->name('users');

//Route::get('v1/batch/show-fico/{email}/{array}', 'API\FicoController@batchShow', function ($email, $array) {})->middleware('auth:api');

Route::get('v1/single-batch/return-fico/{email}/{array}', 'API\FicoController@singleBatchShow', function ($email, $array) {})->middleware('auth:api');

Route::post('v1/post/single-batch/return-fico', 'API\FicoController@postBatchShow')->middleware('auth:api');

Route::post('v1/post/test', 'API\FicoController@postTest');
