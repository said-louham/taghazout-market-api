<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\SliderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// * Handel Auth
Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);

// * Handle categories
Route::apiResource('category', CategoryController::class);

// * Handle products
Route::apiResource('product', ProductController::class);

// * Handle sliders
Route::apiResource('slider', SliderController::class);

// * Handle  Order
Route::apiResource('order', OrderController::class);

// * Handle coupons
Route::apiResource('coupon', CouponController::class);
Route::post('apply_coupon', [CouponController::class, 'applyCoupon']);
