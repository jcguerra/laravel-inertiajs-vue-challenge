<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use Inertia\Inertia;

class CreateProductController extends Controller
{
    /**
     * Create a product
     *
     * @return void
     */
    public function __invoke()
    {
        return Inertia::render('products/CreateProduct');
    }
}