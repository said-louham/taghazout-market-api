<?php

use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CopponController;
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
Route::resource('product', ProductController::class);

// * Handle sliders
Route::apiResource('slider', SliderController::class);

// * Handle coupons
Route::apiResource('coupon', CopponController::class);
Route::post('applyCoupon', [CopponController::class, 'applyCoupon']);
Route::post('validateCoupon', [CopponController::class, 'validateCoupon']);
Route::delete('DeleteCoupon', [CopponController::class, 'DeleteCoupon']);
