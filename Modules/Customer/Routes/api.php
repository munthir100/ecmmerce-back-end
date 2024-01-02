<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Customer\Http\Controllers\AuthController;
use Modules\Customer\Http\Controllers\CartController;
use Modules\Customer\Http\Controllers\CheckoutController;
use Modules\Customer\Http\Controllers\CustomerController;
use Modules\Customer\Http\Controllers\CustomerLocationsController;
use Modules\Customer\Http\Controllers\OrderController;
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
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/register', [AuthController::class, 'register']);


    Route::middleware(['auth:sanctum','is_customer'])->group(function () {
        Route::post('logout', [AuthController::class, 'logout']);

        Route::prefix('cart')->group(function () {
            Route::get('/', [ShoppingCartController::class, 'getCartByCustomer']);
            Route::post('product/{product}/add-to-cart', [ShoppingCartController::class, 'addProductToCart']);
            Route::post('product/featured/{product}/add-to-cart', [ShoppingCartController::class, 'addFeaturedProductToCart']);
            Route::delete('remove/{product}', [ShoppingCartController::class, 'removeProductFromCart']);
            Route::put('update/{product}', [ShoppingCartController::class, 'updateProductQuantity']);
            Route::put('featured-product/{productId}/update-quantity', [ShoppingCartController::class,'updateFeaturedProductQuantity']);
        });

        Route::apiResource('locations', CustomerLocationsController::class);


        Route::post('/checkout', [CheckoutController::class, 'checkout']);

        Route::get('/orders', [OrderController::class, 'index']);
        
        Route::post('/send-message', [CustomerController::class, 'sendMessage']);
    });
});
