<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

//route resource for products
Route::resource('/products', \App\Http\Controllers\ProductController::class);
// Route resource BARU untuk Kategori Produk
Route::resource('/category_products', \App\Http\Controllers\CategoryProductController::class);
// Route resource BARU untuk Transaksi
Route::resource('/transactions', \App\Http\Controllers\SalesTransactionController::class);