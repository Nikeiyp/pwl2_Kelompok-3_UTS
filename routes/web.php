<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\SupplierController;

Route::get('/', function () {
    return view('welcome');
});

// Route untuk Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Resource Routes untuk CRUD
Route::resource('/products', ProductController::class);
Route::resource('/transactions', SalesTransactionController::class);
Route::resource('/category_products', CategoryProductController::class);
Route::resource('/suppliers', SupplierController::class);