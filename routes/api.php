<?php

use App\Http\Controllers\superAdmin\StoreController;
use App\Http\Controllers\superAdmin\UserController;
use Stripe\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Modules\Admin\Entities\SubscriptionPlan;
use Modules\Admin\Http\Controllers\SubscriptionController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('subscriptionPlans',[SubscriptionController::class,'index']);
Route::prefix('superAdmin')->group(function(){
    Route::get('users',[UserController::class,'index']); 
    Route::get('stores',[StoreController::class,'index']); 
});