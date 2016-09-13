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

use App\Http\Modules\YahooOAuth2;


Route::get('/','PageController@home');
Route::get('/new','PageController@new');
Route::get('/update','PageController@update');
Route::get('/test','PageController@test');
Route::get('/{id?}/objects','PageController@objects');
Route::get('/{id?}/{object?}/sync','PageController@sync');
