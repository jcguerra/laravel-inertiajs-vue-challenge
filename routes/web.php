<?php

use App\Http\Controllers\Products\CreateProductController;
use App\Http\Controllers\Products\DeleteProductController;
use App\Http\Controllers\Products\EditProductController;
use App\Http\Controllers\Products\GetAllProductsController;
use App\Http\Controllers\Products\GetProductDetailsController;
use App\Http\Controllers\Products\UpdateProductController;
use App\Http\Controllers\Products\StoreProductController;
use App\Http\Controllers\Images\ImageController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('products', GetAllProductsController::class)->name('products.index');
    Route::get('products/create', CreateProductController::class)->name('products.create');
    Route::post('products', StoreProductController::class)->name('products.store');
    Route::get('products/{id}', GetProductDetailsController::class)->name('products.show');
    Route::get('products/{id}/edit', EditProductController::class)->name('products.edit');
    Route::put('products/{id}', UpdateProductController::class)->name('products.update');
    Route::delete('products/{id}', DeleteProductController::class)->name('products.destroy');
});

Route::get('/asset/{path}', function ($path) {
    $path = storage_path('app/public/' . $path);
    if (!file_exists($path)) {
        abort(404);
    }
    return response()->file($path);
})->where('path', '.*');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
