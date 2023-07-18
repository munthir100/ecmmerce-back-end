<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Http\Controllers\AdminController;
use Modules\Admin\Http\Controllers\AuthController;
use Modules\Admin\Http\Controllers\BrandController;
use Modules\Admin\Http\Controllers\CaptainController;
use Modules\Admin\Http\Controllers\CategoryController;
use Modules\Admin\Http\Controllers\CustomerController;
use Modules\Admin\Http\Controllers\ProductController;

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


Route::post('admin/register', [AuthController::class, 'register'])->name('admin.register');
Route::post('admin/login', [AuthController::class, 'login'])->name('admin.login');
Route::post('admin/logout', [AuthController::class, 'logout'])->name('admin.logout')->middleware('auth:sanctum');


Route::middleware(['auth:sanctum'])
    ->prefix('admin')->name('admin.')->group(function () {
        Route::apiResource('product', ProductController::class);
        Route::apiResource('category', CategoryController::class);
        Route::apiResource('brand', BrandController::class);
        Route::apiResource('customer', CustomerController::class);
        Route::apiResource('captain', CaptainController::class);
        Route::patch('/products/{product}/quantities', [ProductController::class, 'updateQuantities']);
    });
