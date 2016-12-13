<?php

Route::post('register', 'Auth\AuthController@register');
Route::post('token', 'Auth\AuthController@token');
Route::post('refresh', 'Auth\AuthController@refreshToken');