<?php

use Illuminate\Support\Facades\Route;

//аунтетификация
Route::group(['prefix' => 'auth'], function () {
    Route::post('login', 'AuthController@login');
    Route::post('signup', 'AuthController@signup');
    Route::group(['middleware' => 'auth:api'], function() {
        Route::get('logout', 'AuthController@logout');
    });
});

// POST /files создание экземплячра файла, передаем метаданные, в ответе получаем id
// уже возможен запрос GET /files/{id} получение метаданных файла по его id
// POST /files/{id}/data заливка файла по его id
// GET /files/{id}/data получение содержимого самого файла

//закачка файлов доступна для всех пользователей
Route::prefix('files')->group(function () {
    Route::post('/', 'Api\UserFilesController@store');
    Route::post('data/{id}/{chunk_id}', 'Api\UserFilesController@upload');
});
//только для прошедших аунтетификацию
Route::prefix('files')->middleware('auth:api')->group(function () {
    Route::get('chunk/{take?}/{skip?}/{name?}/{data?}', 'Api\UserFilesController@chunk');
    Route::get('{id}', 'Api\UserFilesController@show');
    Route::put('{id}', 'Api\UserFilesController@update');
    Route::delete('{id}', 'Api\UserFilesController@destroy');
});

