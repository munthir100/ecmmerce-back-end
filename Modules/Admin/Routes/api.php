<?php

use Illuminate\Http\Request;
use Modules\Admin\Entities\Bank;
use Modules\Admin\Entities\Language;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\TaxController;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\BrandController;
use Modules\Admin\Http\Controllers\OrderController;
use Modules\Admin\Http\Controllers\CouponController;
use Modules\Admin\Http\Controllers\CaptainController;
use Modules\Admin\Http\Controllers\ProductController;
use Modules\Admin\Http\Controllers\CategoryController;
use Modules\Admin\Http\Controllers\BankAccountController;
use Modules\Admin\Http\Controllers\StoreCitiesController;
use Modules\Admin\Http\Controllers\StoreDesignController;
use Modules\Admin\Http\Controllers\StoreSettingsController;
use Modules\Admin\Http\Controllers\DefinitionPageController;
use Modules\Admin\Http\Controllers\StoreCountriesController;
use Modules\Admin\Http\Controllers\ContactMessagesController;
use Modules\Admin\Http\Controllers\SellerManagementController;
use Modules\Admin\Http\Controllers\Settings\ProfileController;
use Modules\Admin\Http\Controllers\AdminNotificationController;
use Modules\Admin\Http\Controllers\CustomerManagementController;
use Modules\Admin\Http\Controllers\StoreAdditionalSettingsController;
use Modules\Admin\Http\Controllers\SubscriptionsPlansController;

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


// need update social media links
Route::middleware(['auth:sanctum', 'can_manage'])
    ->prefix('admin')->group(function () {
        Route::apiResource('products', ProductController::class);
        Route::apiResource('categories', CategoryController::class);
        Route::apiResource('brands', BrandController::class);
        Route::apiResource('customers', CustomerManagementController::class);
        Route::apiResource('captains', CaptainController::class);
        Route::apiResource('orders', OrderController::class)->except('update');
        Route::post('orders/{order}/change-status', [OrderController::class, 'changeStatus']);
        Route::apiResource('coupons', CouponController::class);
        Route::apiResource('taxes', TaxController::class);
        Route::apiResource('definitionPages', DefinitionPageController::class);

        Route::apiResource('contactMessages', ContactMessagesController::class)->only('index', 'destroy'); // only admin


        Route::get('store-cities', [StoreCitiesController::class, 'index']);

        Route::prefix('settings')->group(function () {
            Route::apiResource('subscriptionsPlans', SubscriptionsPlansController::class)->only('index', 'show');
            Route::apiResource('countries', StoreCountriesController::class)->except('show', 'update');
            Route::put('countries/{countryId}/default', [StoreCountriesController::class, 'setAsDefault']);
            Route::put('countries/{countryId}/toggle-activation', [StoreCountriesController::class, 'toggleActivation']);




            Route::get('profile', [ProfileController::class, 'index']);
            Route::put('profile/update', [ProfileController::class, 'update']);
            Route::put('profile/change-password', [ProfileController::class, 'changePassword']);


            Route::apiResource('BankAccounts', BankAccountController::class)->except('show'); // only admin


            Route::apiResource('sellers', SellerManagementController::class); // only admin


            Route::prefix('store/update')->group(function () {
                Route::put('basic-information', [StoreSettingsController::class, 'updateBasicInformation']);
                Route::post('logo', [StoreSettingsController::class, 'updateStoreLogo']);
                Route::post('icon', [StoreSettingsController::class, 'updateStoreIcon']);
                Route::put('city', [StoreSettingsController::class, 'updateStoreCity']);



                Route::put('commercial-registration', [StoreAdditionalSettingsController::class, 'updateCommercialRegistration']);
                Route::put('status', [StoreAdditionalSettingsController::class, 'updateStatus']);
                Route::put('language', [StoreAdditionalSettingsController::class, 'updateStoreLanguage']);

                Route::prefix('design')->group(function () {
                    Route::put('navbar', [StoreDesignController::class, 'updateNavbar']);
                    Route::delete('navbar', [StoreDesignController::class, 'deleteNavbar']);
                    Route::put('theme', [StoreDesignController::class, 'updateTheme']);
                    Route::put('colors', [StoreDesignController::class, 'updateColors']);
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

Route::middleware(['auth:sanctum', 'is_admin'])
    ->prefix('admin')->group(function () {
        Route::apiResource('notifications', AdminNotificationController::class)->only('index', 'destroy');
    });
