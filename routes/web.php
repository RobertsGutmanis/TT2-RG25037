<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;

Route::get('/', function () {
    return view('welcome');
})->name("home");

Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::get('/register', [AuthController::class, 'registerPage'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');


Route::middleware('auth')->group(function () {
    Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin-panel/products', [AdminController::class, 'store'])->name('admin.store');
    Route::delete('/admin-panel/products/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::get('/admin-panel/products/{id}/specs', [AdminController::class, 'editSpecs'])->name('admin.specs');
    Route::post('/admin-panel/products/{id}/specs', [AdminController::class, 'updateSpecs'])->name('admin.specs.update');
});