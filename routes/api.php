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

Route::resource('categories', 'Category\CategoryController');
Route::resource('categories/{id}/types', 'Category\TypeController');

Route::get('/user', function (Request $request) {
    dd(123);
    return $request->user();
})->middleware('auth:api');
