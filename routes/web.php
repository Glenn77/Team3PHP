<?php


/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'IndexViewController@show');
Route::post('/', 'IndexViewController@getDienstRegeling');

Route::get('/stations', 'StationsViewController@show');
Route::post('/stations', 'StationsViewController@getStation');

Route::get('/trains', 'TrainsViewController@show');
Route::post('/trains', 'TrainsViewController@getTrain');

Route::get('/cron-jobs', function() {
    Artisan::call('indexStations:xml');
});