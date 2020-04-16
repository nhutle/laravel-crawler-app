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

// Authentication:
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

// Statistic:
Route::get('/{statistics?}', 'StatisticsController@index')
    ->where('statistics', '(statistics)')
    ->name('statistics');

Route::get('/statistics/{id}/html_code', 'StatisticsController@htmlCode')->name('statistic_content');

// Import CSV:
Route::get('/upload', 'UploadController@index')->name('upload');
Route::post('/parse_file', 'UploadController@parseFile')->name('parse_file');
Route::post('/process_file', 'UploadController@processFile')->name('process_file');