<?php

use App\Http\Controllers\ApiProductController;
use Illuminate\Support\Facades\Route;

Route::get('products', [ApiProductController::class, 'index']);
Route::get('products/{id}', [ApiProductController::class, 'show']);
