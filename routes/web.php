<?php

use Illuminate\Support\Facades\Route;

Route::get('uploaded/{hash_user}/{hash_file}', 'DownloadController@index');

Route::get('/', function () {
    return view('welcome');
});
