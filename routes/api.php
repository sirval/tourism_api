<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::get('user/booking/payment/callback', [App\Http\Controllers\PaymentController::class, 'handlePaymentCallback']);
Route::group(['middleware' => 'api'], function(){
    Route::prefix('auth')->group(function(){
        Route::post('/login', [\App\Http\Controllers\AuthController::class, 'login']);
        Route::post('register', [\App\Http\Controllers\AuthController::class, 'register']);
        Route::post('logout', [\App\Http\Controllers\AuthController::class, 'logout']);
        Route::post('refresh', [\App\Http\Controllers\AuthController::class, 'refresh']);
    });

    //ADMIN PROTECTED ROUTES
    Route::group(['middleware' => 'acl:admin', 'prefix' => 'admin'], function() {
        Route::apiResource('travel-options', App\Http\Controllers\TravelOptionController::class);
        Route::post('booking/cancel/{id}', [App\Http\Controllers\BookingController::class, 'cancelBooking']);
    });


    Route::prefix('user')->group(function(){
        Route::apiResource('search-options', App\Http\Controllers\SearchOptionController::class);
        Route::get('travel-options/filter', [App\Http\Controllers\SearchOptionController::class, 'filter']);
    });

    //USER PROTECTED ROUTES
    Route::group(['middleware' => 'acl:user', 'prefix' => 'user'], function() {
        Route::apiResource('booking', App\Http\Controllers\BookingController::class);
        Route::post('booking/cancel/{id}', [App\Http\Controllers\BookingController::class, 'cancelBooking']);
        Route::post('booking/make-payment', [App\Http\Controllers\PaymentController::class, 'pay']);
    });

});
