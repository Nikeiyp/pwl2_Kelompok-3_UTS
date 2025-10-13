<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;



Route::get('/', function () {
    return view('welcome');
});

//route resource for products
Route::resource('/products', \App\Http\Controllers\ProductController::class);

//route resource for suppliers
Route::resource('suppliers', \App\Http\Controllers\SupplierController::class);

//route resource for product category
Route::resource('/category_products', \App\Http\Controllers\CategoryProductController::class);

//route resource for sales transactions

Route::resource('/transactions', \App\Http\Controllers\SalesTransactionController::class);


//route resource for dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
