<?php

// * AUTH API
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ProfileSettingController;
use Illuminate\Support\Facades\Route;

Route::post('logout', [AuthController::class, 'logout']);

// * Profile settings
Route::prefix('profile')->controller(ProfileSettingController::class)->group(function () {
    Route::put('change-password', 'updatePassword');
});
