<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CopponController;
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
// * Handle categories
Route::apiResource('category', CategoryController::class);

// * Handle sliders
Route::apiResource('slider', SliderController::class);

// * Handle coupons
Route::apiResource('coupon', CopponController::class);
Route::post('applyCoupon', [CopponController::class, 'applyCoupon']);
Route::post('validateCoupon', [CopponController::class, 'validateCoupon']);
Route::delete('DeleteCoupon', [CopponController::class, 'DeleteCoupon']);
