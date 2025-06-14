<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductRepositoryInterface;

class DeleteProductController extends Controller
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
     * Delete a product
     *
     * @param integer $id
     * @return void
     */
    public function __invoke(int $id)
    {
        $product = $this->productRepository->getProduct($id);
        $product->delete();
        return redirect()->route('products.index');
    }
}