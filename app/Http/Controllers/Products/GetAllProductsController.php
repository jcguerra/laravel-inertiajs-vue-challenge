<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Services\ProductsService;
use Inertia\Inertia;
use Illuminate\Http\Request;

class GetAllProductsController extends Controller
{
    /**
     * Get All Products constructor
     * @param ProductService $productService
     */
    public function __construct(
        private readonly ProductsService $productService
    ){}
    

    /**
     * Get All Products function
     * @param Request $request
     * @return \Inertia\Response
     */
    public function __invoke(Request $request): \Inertia\Response
    {
        $products = $this->productService->getAllProducts($request);

        return Inertia::render('products/AllProducts', [
            'products' => $products
        ]);
    }
}