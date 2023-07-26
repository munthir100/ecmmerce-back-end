<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\AuthController;
use Modules\Customer\Http\Controllers\CartController;
use Modules\Customer\Http\Controllers\CheckoutController;
use Modules\Customer\Http\Controllers\CustomerLocationsController;
use Modules\Customer\Http\Controllers\ShoppingCartController;

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

Route::prefix('{storeLink}')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/product/{product}/add-to-cart/', [CartController::class, 'addToCart'])->name('addToCart');
    
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth:sanctum');
        
        
        Route::get('/cart', [ShoppingCartController::class, 'getCartByCustomer']);
        Route::post('/product/{product}/add-to-cart', [ShoppingCartController::class, 'addProductToCart']);
        Route::post('/product/featured/{product}/add-to-cart', [ShoppingCartController::class, 'addFeaturedProductToCart']);
        Route::delete('/cart/remove/{product}', [ShoppingCartController::class, 'removeProductFromCart']);
        Route::put('/cart/update/{product}', [ShoppingCartController::class, 'updateProductQuantity']);
        
        Route::apiResource('locations', CustomerLocationsController::class);


        Route::post('/checkout', [CheckoutController::class, 'checkout']);

        
    });

});

