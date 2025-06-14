<?php

use App\Http\Controllers\Api\v1\Auth\AuthController;
use App\Http\Controllers\Api\v1\Products\GetAllProductsApiController;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Route::get('/user', function (Request $request) {
//     return $request->user();
// })->middleware('auth:sanctum');

Route::prefix('v1')->group(function () {

    Route::middleware('jwt')->group(function () {

        Route::prefix('auth')->group(function () {

            Route::post('login', [AuthController::class, 'login'])->withoutMiddleware('jwt');
            Route::post('logout', [AuthController::class, 'logout']);
            Route::post('refresh', [AuthController::class, 'refresh']);
        });

        Route::prefix('products')->group(function () {
            Route::get('/', GetAllProductsApiController::class)->name('products.all');
        });
    });

});

// Health Check
Route::get('/ping', function () {
    return response()->json([
        'success' => true,
        'message' => 'ConnectBuy API is working!'
    ]);
});
