<?php

use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\ForgotPasswrodController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetNewPasswrodController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Frontend\ProductCategoryController as FrontendProductCategoryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/
Route::prefix('')->group(function () {
    Route::post('/login', [LoginController::class, 'login']);
    Route::post('/register', [SignupController::class, 'register']);
    Route::post('/verify-otp', [SignupController::class, 'verifyOtp']);
    Route::post('/resent-otp', [SignupController::class, 'resentOtp']);
    Route::post('/forgot-password', [ForgotPasswrodController::class, 'forgotPassword']);
    Route::post('/reset-password', [ResetNewPasswrodController::class, 'resetPassword']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [LoginController::class, 'logout']);
    });
});

/*
|--------------------------------------------------------------------------
| Profile / User Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Admin Routes (Role & Permission Protected)
|--------------------------------------------------------------------------
*/
Route::prefix('admin')->middleware(['auth:sanctum', 'role:Admin'])->group(function () {

    // Role Management
    Route::prefix('role')->group(function () {
        Route::get('/', [RoleController::class, 'index'])->middleware('permission:view_roles');
        Route::post('/', [RoleController::class, 'store'])->middleware('permission:create_roles');
        Route::match(['put', 'patch'], '/{role}', [RoleController::class, 'update'])->middleware('permission:edit_roles');
        Route::delete('/{role}', [RoleController::class, 'destroy'])->middleware('permission:delete_roles');
    });

    // Permission Management
    Route::prefix('permission')->group(function () {
        Route::get('/{role}', [PermissionController::class, 'index'])->middleware('permission:view_permissions');
        Route::match(['put', 'patch'], '/{role}', [PermissionController::class, 'update'])->middleware('permission:edit_permissions');
    });

    // Admin Product Categories (Controlled by 'settings' permission in Controller)
    Route::prefix('setting')->name('setting.')->group(function (){


        Route::prefix('product-category')->group(function () {
        Route::get('/', [ProductCategoryController::class, 'index']);
        Route::get('/depth-tree', [ProductCategoryController::class, 'depthTree']);
        Route::get('/show/{productCategory}', [ProductCategoryController::class, 'show']);
        Route::post('/', [ProductCategoryController::class, 'store']);
        Route::match(['post', 'put', 'patch'], '/{productCategory}', [ProductCategoryController::class, 'update']);
        Route::delete('/{productCategory}', [ProductCategoryController::class, 'destroy']);
        Route::get('/tree', [ProductCategoryController::class, 'tree']);
        Route::get('/export', [ProductCategoryController::class, 'export']);
        Route::get('/download-attachment/{fileName}', [ProductCategoryController::class, 'downloadAttechment']);
        Route::post('/import', [ProductCategoryController::class, 'import']);
    });
    });
});

/*
|--------------------------------------------------------------------------
| Frontend Routes (Publicly Accessible)
|--------------------------------------------------------------------------
*/
Route::prefix('frontend')->group(function () {
    Route::prefix('product-category')->group(function () {
        Route::get('/', [FrontendProductCategoryController::class, 'index']);
        Route::get('/ancestors-and-self/{productCategory:slug}', [FrontendProductCategoryController::class, 'ancestorsAndSelf']);
        Route::get('/tree', [FrontendProductCategoryController::class, 'tree']);
        Route::get('/show/{productCategory:slug}', [FrontendProductCategoryController::class, 'show']);
    });
});
