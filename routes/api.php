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

Route::post('login', 'Api\AuthController@login');

Route::group(['middleware' => 'jwt.auth'], function () {
    Route::post('logout', 'Api\AuthController@logout');
    Route::post('upload', 'Api\UploadController@upload');
    Route::post('process_file', 'Api\UploadController@processFile');
    Route::get('statistics', 'Api\StatisticsController@getStatistics');
});