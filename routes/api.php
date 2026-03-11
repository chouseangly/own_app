<?php

use App\Http\Controllers\Admin\BarcodeController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductBrandController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Auth\ForgotPasswrodController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ResetNewPasswrodController;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Admin\ProductCategoryController;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\UnitController;
use App\Http\Controllers\Frontend\SliderController as FrontendSliderController;
use App\Http\Controllers\Frontend\ProductCategoryController as FrontendProductCategoryController;
use App\Http\Controllers\Frontend\ProductBrandController as FrontendProductBrandController;
use App\Http\Controllers\Frontend\ProfileController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Authentication Routes
|--------------------------------------------------------------------------
*/

Route::prefix('auth')->group(function () {
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

Route::prefix('profile')->name('profile.')->middleware(['installed', 'apiKey', 'auth:sanctum', 'localization'])->group(function () {
    Route::get('/', [ProfileController::class, 'profile']);
    Route::match(['post', 'put', 'patch'], '/', [ProfileController::class, 'update']);
    Route::match(['put', 'patch'], '/change-password', [ProfileController::class, 'changePassword']);
    Route::post('/change-image', [ProfileController::class, 'changeImage']);
});
// Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
//     return $request->user();
// });

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
    Route::prefix('setting')->name('setting.')->group(function () {


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

        Route::prefix('slider')->name('slider.')->group(function () {
            Route::get('/', [SliderController::class, 'index']);
            Route::get('/show/{slider}', [SliderController::class, 'show']);
            Route::post('/', [SliderController::class, 'store']);
            Route::match(['post', 'put', 'patch'], '/{slider}', [SliderController::class, 'update']); // Changed from {id}
            Route::delete('/{slider}', [SliderController::class, 'destory']); // Changed from {id}
        });

        Route::prefix('product-brand')->name('product-brand.')->group(function(){
            Route::get('/',[ProductBrandController::class,'index']);
            Route::get('/show/{productBrand}',[ProductBrandController::class,'show']);
            Route::post('/',[ProductBrandController::class,'store']);
            Route::match(['post', 'put','patch'], '/{productBrand}',[ProductBrandController::class,'update']);
            Route::delete('/{productBrand}',[ProductBrandController::class,'destroy']);
        });

        Route::prefix('unit')->name('unit.')->group(function(){
            Route::get('/',[UnitController::class,'index']);
            Route::get('/show/{unit}',[UnitController::class,'show']);
            Route::post('/',[UnitController::class,'store']);
            Route::match(['post', 'put', 'patch'], '/{unit}', [UnitController::class,'update']);
            Route::delete('/{unit}',[UnitController::class,'destroy']);
        });

        Route::prefix('barcode')->name('barcode.')->group(function(){
            Route::get('/',[BarcodeController::class,'index']);
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

    Route::prefix('slider')->name('slider.')->group(function () {
        Route::get('/', [FrontendSliderController::class, 'index']);
    });
     Route::prefix('product-brand')->name('product-brand.')->group(function () {
        Route::get('/', [FrontendProductBrandController::class, 'index']);
    });


});
