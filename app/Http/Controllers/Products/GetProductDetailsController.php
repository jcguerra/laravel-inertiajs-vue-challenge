<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductRepositoryInterface;
use Inertia\Inertia;

class GetProductDetailsController extends Controller
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
     * Get the product details
     *
     * @param integer $id
     * @return void
     */
    public function __invoke(int $id)
    {
        $product = $this->productRepository->getProduct($id);
        return Inertia::render('products/ProductDetails', [
            'product' => $product
        ]);
    }
}