<?php

use App\Http\Controllers\Api\admin\SettingController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileController;
use Illuminate\Support\Facades\Route;

Route::post('reset', [AuthController::class, 'reset']);
Route::post('forget', [AuthController::class, 'forget']);

// * Handle settings
Route::apiResource('setting', SettingController::class);

Route::apiResource('user_detail', ProfileController::class);
