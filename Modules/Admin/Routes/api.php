<?php

use Illuminate\Http\Request;
use Modules\Admin\Entities\Bank;
use Modules\Admin\Entities\Language;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\BrandController;
use Modules\Admin\Http\Controllers\OrderController;
use Modules\Admin\Http\Controllers\CouponController;
use Modules\Admin\Http\Controllers\CaptainController;
use Modules\Admin\Http\Controllers\ProductController;
use Modules\Admin\Http\Controllers\CategoryController;
use Modules\Admin\Http\Controllers\CustomerManagementController;
use Modules\Admin\Http\Controllers\BankAccountController;
use Modules\Admin\Http\Controllers\StoreCitiesController;
use Modules\Admin\Http\Controllers\StoreDesignController;
use Modules\Admin\Http\Controllers\StoreSettingsController;
use Modules\Admin\Http\Controllers\StoreCountriesController;
use Modules\Admin\Http\Controllers\SellerManagementController;
use Modules\Admin\Http\Controllers\Settings\ProfileController;
use Modules\Admin\Http\Controllers\StoreAdditionalSettingsController;

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
        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('customers', CustomerManagementController::class);
        Route::apiResource('captains', CaptainController::class);
        Route::apiResource('orders', OrderController::class);
        Route::apiResource('coupons', CouponController::class);
        Route::post('orders/{order}/change-status', [OrderController::class, 'changeStatus']);
        Route::get('store-cities', [StoreCitiesController::class, 'index']);

        Route::prefix('settings')->group(function () {
            Route::apiResource('countries', StoreCountriesController::class)->except('show', 'update');
            Route::put('countries/{countryId}/default', [StoreCountriesController::class, 'setAsDefault']);
            Route::put('countries/{countryId}/toggle-activation', [StoreCountriesController::class, 'toggleActivation']);




            Route::get('profile', [ProfileController::class, 'index']);
            Route::put('profile/update', [ProfileController::class, 'update']);
            Route::put('profile/change-password', [ProfileController::class, 'changePassword']);


            Route::apiResource('BankAccounts', BankAccountController::class)->except('show');

            
            Route::apiResource('sellers', SellerManagementController::class);


            Route::prefix('store/update')->group(function () {
                Route::put('basic-information', [StoreSettingsController::class, 'updateBasicInformation']);
                Route::post('logo', [StoreSettingsController::class, 'updateStoreLogo']);
                Route::post('icon', [StoreSettingsController::class, 'updateStoreIcon']);
                Route::put('city', [StoreSettingsController::class, 'updateStoreCity']);



                Route::put('commercial-registration', [StoreAdditionalSettingsController::class, 'updateCommercialRegistration']);
                Route::put('status', [StoreAdditionalSettingsController::class, 'updateStatus']);
                Route::put('language', [StoreAdditionalSettingsController::class, 'updateStoreLanguage']);
                Route::put('colors', [StoreAdditionalSettingsController::class, 'updateColors']);

                Route::prefix('design')->group(function () {
                    Route::put('navbar', [StoreDesignController::class, 'updateNavbar']);
                    Route::put('theme', [StoreDesignController::class, 'updateTheme']);
                });
            });


            Route::get('banks', function () {
                return Bank::all();
            });
            Route::get('languages', function () {
                return Language::all();
            });
        });
    });
