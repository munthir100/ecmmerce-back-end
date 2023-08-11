<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Entities\Bank;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\BrandController;
use Modules\Admin\Http\Controllers\OrderController;
use Modules\Admin\Http\Controllers\CaptainController;
use Modules\Admin\Http\Controllers\ProductController;
use Modules\Admin\Http\Controllers\CategoryController;
use Modules\Admin\Http\Controllers\CustomerController;
use Modules\Admin\Http\Controllers\BankAccountController;
use Modules\Admin\Http\Controllers\CouponController;
use Modules\Admin\Http\Controllers\SellerManagementController;
use Modules\Admin\Http\Controllers\StoreCountriesController;
use Modules\Admin\Http\Controllers\Settings\ProfileController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('admin/register', [AuthController::class, 'register']);
Route::post('admin/login', [AuthController::class, 'login']);
Route::post('admin/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])
    ->prefix('admin')->group(function () {
        Route::apiResource('product', ProductController::class);
        Route::apiResource('category', CategoryController::class);
        Route::apiResource('brand', BrandController::class);
        Route::apiResource('customer', CustomerController::class);
        Route::apiResource('captain', CaptainController::class);
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('coupons', CouponController::class);
        Route::apiResource('settings/countries', StoreCountriesController::class)->except('show', 'update');
        Route::apiResource('settings/profile', ProfileController::class)->only('index','update');
        Route::apiResource('settings/sellers', SellerManagementController::class);
        Route::apiResource('settings/BankAccounts', BankAccountController::class);
        
        Route::get('settings/banks', function(){return Bank::all();});

        Route::put('settings/profile/updatePassword', [ProfileController::class, 'updatePassword']);
        Route::post('orders/{order}/change-status', [OrderController::class, 'changeStatus']);
        Route::put('settings/countries/{countryId}/default', [StoreCountriesController::class, 'setAsDefault']);
        Route::put('settings/countries/{countryId}/toggle', [StoreCountriesController::class, 'toggleActivation']);
        
        


    });
