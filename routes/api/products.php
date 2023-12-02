<?php

use App\Http\Controllers\Api\admin\AdminOrderController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\FavoritController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\RattingController;
use Illuminate\Support\Facades\Route;

// * Handle  Favorite
Route::apiResource('favorite', FavoritController::class);
Route::delete('delete_wishlist', [FavoritController::class, 'deleteWishlist']);

// * Handle  User Cart
Route::apiResource('cart', CartController::class);

// * Handle  Ratings
Route::apiResource('rating', RattingController::class);

Route::patch('Update_product_image/{image_id}', [ProductController::class, 'Update_product_image']);
Route::delete('delete_image/{image_id}', [ProductController::class, 'delete_image']);

// * Handle order admin
Route::apiResource('orderAdmin', AdminOrderController::class);
Route::get('order/{order_id}/SendEmail', [AdminOrderController::class, 'Send_email']);

// * Handle users
Route::get('users', [AuthController::class, 'index']);

// * Handle contact
Route::post('contactMail', [AuthController::class, 'contactUs']);
