<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::resource('/products', \App\Http\Controllers\ProductController::class);

Route::resource('/category_products', \App\Http\Controllers\CategoryProductController::class);

Route::resource('/transactions', \App\Http\Controllers\SalesTransactionController::class);