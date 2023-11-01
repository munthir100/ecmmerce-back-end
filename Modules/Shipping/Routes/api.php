<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Shipping\Http\Controllers\ShippingController;

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

Route::get('/cities', [ShippingController::class, 'cities']); // temp
Route::get('/countries', [ShippingController::class, 'countries']); // temp