<?php

Route::resource('categories', 'Category\CategoryController');
Route::resource('categories/{id}/types', 'Category\TypeController');