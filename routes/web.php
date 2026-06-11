<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController; 
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;

Route::get('/language/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'lv'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('language.switch');

Route::get('/', function () {
    $featured = \App\Models\Product::with('category')->latest('id')->take(4)->get();
    return view('welcome', compact('featured'));
})->name("home");

Route::get('/login', [AuthController::class, 'loginPage'])->name('login');
Route::get('/register', [AuthController::class, 'registerPage'])->name('auth.register');
Route::post('/login', [AuthController::class, 'login'])->name('auth.login.post');
Route::post('/register', [AuthController::class, 'register'])->name('auth.register.post');

Route::middleware('auth')->group(function () {
    Route::get('/account', [AccountController::class, 'index'])->name('account.index');
    Route::post('/account', [AccountController::class, 'update'])->name('account.update');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{productId}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{id}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/update/{id}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');


Route::middleware('auth')->group(function () {
    Route::get('/admin-panel', [AdminController::class, 'index'])->name('admin.index');
    Route::post('/admin-panel/products', [AdminController::class, 'store'])->name('admin.store');
    Route::delete('/admin-panel/products/{id}', [AdminController::class, 'destroy'])->name('admin.destroy');
    Route::get('/admin-panel/products/{id}/specs', [AdminController::class, 'editSpecs'])->name('admin.specs');
    Route::post('/admin-panel/products/{id}/specs', [AdminController::class, 'updateSpecs'])->name('admin.specs.update');
    Route::post('/admin-panel/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.order.status');
});