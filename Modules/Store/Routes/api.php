<?php

use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\StoreBrandController;
use Modules\Store\Http\Controllers\StoreCaptainController;
use Modules\Store\Http\Controllers\StoreCategoryController;
use Modules\Store\Http\Controllers\StoreController;
use Modules\Store\Http\Controllers\StoreProductController;

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

Route::prefix('{storeLink}')->middleware('active_store')->group(function () {

    Route::get('/categories', [StoreCategoryController::class, 'categories']);

    Route::prefix('products')->group(function () {
        Route::get('/', [StoreProductController::class, 'products']);
        Route::get('featured', [StoreProductController::class, 'featuredProducts']);
        Route::get('category/{category}', [StoreProductController::class, 'categorizedProducts']);
        Route::get('details/{productId}', [StoreProductController::class, 'productDetails']);
        Route::post('rate/{productId}', [StoreProductController::class, 'rateProduct']);
    });

    Route::get('/brands', [StoreBrandController::class, 'brands']);

    Route::get('/captains', [StoreCaptainController::class, 'captains']);
    Route::get('/cities', [StoreController::class,'cities']);
    Route::get('ratings', [StoreController::class, 'ratings']);
    Route::post('rate', [StoreController::class, 'rate']);
});
