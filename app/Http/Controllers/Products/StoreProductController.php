<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductCreateRequest;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StoreProductController extends Controller
{
    /**
     * Constructor
     *
     * @param ProductRepositoryInterface $productRepository
     */
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
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
        $product = $this->productRepository->createProduct($productData);
        return Inertia::render('products/EditProduct', [
            'product' => $product
        ]);
    }
}