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

Route::post('auth/signup', 'Auth\AuthController@signup')->middleware('api');
Route::post('auth/token', 'Auth\AuthController@token');
Route::post('auth/refresh', 'Auth\AuthController@refresh');

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::resource('categories', 'Category\CategoryController');
Route::resource('categories/{id}/types', 'Category\TypeController');

/*Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');*/
