<?php

// * AUTH API
use App\Http\Controllers\Api\AuthController;
use Illuminate\Support\Facades\Route;

//Route::get('init', [AuthController::class, 'user']);

Route::post('logout', [AuthController::class, 'logout']);

Route::post('register', [AuthController::class, 'register']);
