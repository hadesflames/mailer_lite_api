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
| Auth middleware disabled for assignment purposes.
|
*/

Route::prefix('subscriber')->group(function () {
    Route::get('', 'Subscribers@getAll');
    Route::get('{id}', 'Subscribers@get')->where('id', '[0-9]+');
    Route::post('', 'Subscribers@create');
    Route::put('{id}', 'Subscribers@update')->where('id', '[0-9]+');
    Route::put('activate/{id}', 'Subscribers@activate')->where('id', '[0-9]+');
    Route::delete('{id}', 'Subscribers@delete')->where('id', '[0-9]+');
});

Route::prefix('field')->group(function () {
    Route::get('', 'Fields@getAll');
    Route::get('{id}', 'Fields@get')->where('id', '[0-9]+');
    Route::post('', 'Fields@create');
    Route::put('{id}', 'Fields@update')->where('id', '[0-9]+');
    Route::put('activate/{id}', 'Fields@activate')->where('id', '[0-9]+');
    Route::delete('{id}', 'Fields@delete')->where('id', '[0-9]+');
});
