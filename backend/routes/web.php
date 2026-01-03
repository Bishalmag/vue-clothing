<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;

// Public routes
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// Protected routes (require valid token)
    Route::middleware('auth.token')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/logout', [AuthController::class, 'logout']);

//  route for products
// Route::get('/products', [ProductController::class, 'index']);
// Route::post('/products', [ProductController::class, 'store']);

// route for category
// Route::get('/categories', [CategoryController::class, 'index']);
// Route::post('/categories', [CategoryController::class, 'store']);

// route for admin login
Route::post('/admin/login', [AdminAuthController::class, 'login']);
});