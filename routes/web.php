<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;

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