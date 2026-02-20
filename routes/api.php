<?php

use App\Http\Controllers\Auth\ForgotPasswrodController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetNewPasswrodController;
use App\Http\Controllers\Auth\SignupController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/register', [SignupController::class, 'register']);
Route::post('/verify-otp',[SignupController::class,'verifyOtp']);
Route::post('/login',[LoginController::class,'login']);
Route::post('/resent-otp',[SignupController::class,'resentOtp']);
Route::post('/forgotPassword',[ForgotPasswrodController::class,'forgotPassword']);
Route::post('/reset-password', [ResetNewPasswrodController::class, 'resetPassword']);
