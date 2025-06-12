<?php

use App\Http\Controllers\Products\GetAllProductsController;
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
});


// Route::get('dashboard', function () {
//     return Inertia::render('Dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

// Route::get('products', GetAllProductsController::class)->name('products.index');

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
