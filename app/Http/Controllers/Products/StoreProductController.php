<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Services\ProductsService;
use Inertia\Inertia;

class StoreProductController extends Controller
{
    /**
     * Constructor
     *
     * @param ProductsService $productsService
     */
    public function __construct(
        private readonly ProductsService $productsService
    ){}

    /**
     * Store a product
     *
     * @param Request $request
     * @return void
     */
    public function __invoke(ProductCreateRequest $request)
    {
        $productData = $request->validated();
        $product = $this->productsService->createProduct($productData);
        return Inertia::render('products/EditProduct', [
            'product' => $product
        ]);
    }
}