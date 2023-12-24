<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\CopponController;
use App\Http\Controllers\Api\SliderController;
use App\Http\Controllers\Api\FavoritController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProfileController;
use App\Http\Controllers\Api\RattingController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\admin\SettingController;
use App\Http\Controllers\Api\admin\AdminOrderController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});




Route::group(['middleware' => ['auth:sanctum']], function () {
    // ----------------------------Profile-----------------------------------------------
    Route::get('Profile', [AuthController::class, 'Profile']);
    // ----------------------------LogOut------------------------------------------------
    Route::delete('logout', [AuthController::class, 'logout']);
    // ---------------------------- User Order-------------------------------------------
    Route::resource('order', OrderController::class);
    // ---------------------------- Favorite---------------------------------------------
    Route::resource('favorit', FavoritController::class);
    // ---------------------------- Cart-------------------------------------------------
    Route::resource('cart', CartController::class);
    Route::delete('/deleteCart', [CartController::class, 'destroyUserCart']);
    // ---------------------------- Rating-----------------------------------------------
    Route::resource('rating', RattingController::class);
    Route::post('RateProduct/{product_id}', [RattingController::class, 'RateProduct']);
    // --------------------Change Password-----------------------------------------------
    Route::post('changePassword', [AuthController::class, 'changePassword']);
});
// ---------------------------- Authantification-------------------------------------
Route::get('users', [AuthController::class, 'index']);
Route::post('register', [AuthController::class, 'register']);
Route::post('login', [AuthController::class, 'login']);
Route::get('users/{user}', [AuthController::class, 'show']);
Route::patch('/users/update/{user}', [AuthController::class, 'update']);
Route::delete('users/{user}', [AuthController::class, 'destroy']);
Route::resource('userDetail', ProfileController::class);
Route::post('contactMail', [AuthController::class, 'contactUs']);

// --------------------Forget Password-----------------------------------------------
Route::post('reset', [AuthController::class, 'reset']);
Route::post('forget', [AuthController::class, 'forget']);

// ---------------------------- Category-----------------------------------------------
Route::resource('category', CategoryController::class);


// ---------------------------- Product------------------------------------------------
Route::resource('product', ProductController::class);
Route::patch('UpdateProductImage/{image_id}', [ProductController::class, 'UpdateProductImage']);
Route::post('disroyImage/{image_id}', [ProductController::class, "disroyImage"]);


// ---------------------------- Admin Orders-------------------------------------------
Route::resource('orderAdmin', AdminOrderController::class);
Route::get('order/{order_id}/SendEmail', [AdminOrderController::class, "SendEmail"]);


// ---------------------------- setting------------------------------------------------
Route::resource('setting', SettingController::class);


// ---------------------------- slider-------------------------------------------------
Route::resource('slider', SliderController::class);


// ---------------------------- Coupon-------------------------------------------------
Route::resource('coupon', CopponController::class);
Route::post('applyCoupon', [CopponController::class, "applyCoupon"]);
Route::post('validateCoupon', [CopponController::class, "validateCoupon"]);
Route::delete('DeleteCoupon', [CopponController::class, "DeleteCoupon"]);
