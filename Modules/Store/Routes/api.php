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

Route::prefix('{storeLink}')->name('store.')->group(function () {
    Route::get('/products', [StoreController::class, 'products'])->name('products');
    Route::get('/categories', [StoreController::class, 'categories'])->name('categories');
    Route::get('/products/{category}', [StoreController::class, 'categorizedProducts'])->name('categorized-products');

    Route::get('/brands', [StoreController::class, 'brands'])->name('brands');
    Route::get('/product/{productId}/details', [StoreController::class, 'productDetails'])->name('product-details');

    
    Route::post('/add-location', [StoreController::class,'addLocation'])->name('addLocation');
    Route::post('/delete-location', [StoreController::class,'deleteLocation'])->name('deleteLocation');

    Route::post('/checkout', [StoreController::class,'checkout'])->name('checkout');

});