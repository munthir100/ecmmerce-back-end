<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Store\Http\Controllers\StoreController;

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
    Route::get('/products', [StoreController::class, 'products']);
    Route::get('/products/featured', [StoreController::class, 'GetFeaturedProducts']);
    Route::get('/categories', [StoreController::class, 'categories']);
    Route::get('/products/{category}', [StoreController::class, 'categorizedProducts']);

    Route::get('/brands', [StoreController::class, 'brands']);
    Route::get('/captains', [StoreController::class, 'captains']);
    Route::get('/product/{productId}/details', [StoreController::class, 'productDetails']);

    
    Route::post('/add-location', [StoreController::class,'addLocation']);
    Route::post('/delete-location', [StoreController::class,'deleteLocation']);


});