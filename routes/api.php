<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [SignupController::class, 'register']);
Route::post('/verify-otp',[SignupController::class,'verifyOtp']);
Route::post('/login',[LoginController::class,'login']);
