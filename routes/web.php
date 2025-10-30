<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\SalesTransactionController;
use App\Http\Controllers\CategoryProductController;
use App\Http\Controllers\SupplierController;

// Redirect root ke halaman login
Route::get('/', function () {
    return redirect()->route('login');
});

// ROUTE AUTHENTICATION
Route::get('/login', [AuthController::class, 'loginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.process');

Route::get('/register', [AuthController::class, 'registerForm'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.process');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


// ROUTE YANG DILINDUNGI LOGIN (AUTH MIDDLEWARE)
Route::middleware(['auth'])->group(function () {

// Route untuk Dashboard
Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

// Resource Routes untuk CRUD
Route::resource('/products', ProductController::class);
Route::resource('/transactions', SalesTransactionController::class);
Route::resource('/category_products', CategoryProductController::class);
Route::resource('/suppliers', SupplierController::class);
});