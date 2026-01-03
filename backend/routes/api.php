<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\OrderController;

// Public / Auth routes (user-facing)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

//product route for users
Route::get('/products', [ProductController::class, 'index']);     
Route::get('/products/{id}', [ProductController::class, 'show']); 

// Protected user routes (require api_token)
Route::middleware('auth.token')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);        // GET /api/me
    Route::post('/logout', [AuthController::class, 'logout']);
    
    //order route for users
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
});

// Admin auth (public)
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// Admin routes (prefix + admin middleware)
Route::prefix('admin')->middleware('auth.admin')->group(function () {
    Route::get('/admindashboard', [AdminAuthController::class, 'index']);
    Route::get('/me', [AdminAuthController::class, 'me']);   
    Route::get('/logout', [AdminAuthController::class, 'logout']);

    // admin categories CRUD 
    Route::get('/categories', [CategoryController::class, 'index']);
    Route::post('/categories', [CategoryController::class, 'store']);
    Route::get('/categories/{id}', [CategoryController::class, 'show']);
    Route::put('/categories/{id}', [CategoryController::class, 'update']);
    Route::delete('/categories/{id}', [CategoryController::class, 'destroy']);

    // admin products CRUD 
    Route::get('/products', [ProductController::class, 'index']);
    Route::post('/products', [ProductController::class, 'store']);
    Route::get('/products/{id}', [ProductController::class, 'show']);
    Route::put('/products/{id}', [ProductController::class, 'update']);
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);

    // admin users management 
    Route::get('/users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::get('/users/{id}', [UserController::class, 'show']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);

    //admin orders CRUD
    Route::get('/orders', [OrderController::class, 'index']);
    Route::post('/orders', [OrderController::class, 'store']);
    Route::get('/orders/{id}', [OrderController::class, 'show']);
    Route::put('/orders/{id}', [OrderController::class, 'update']);
    Route::delete('/orders/{id}', [OrderController::class, 'destroy']);
});