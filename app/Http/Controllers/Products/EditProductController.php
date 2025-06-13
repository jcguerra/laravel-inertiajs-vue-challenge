<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductRepositoryInterface;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class EditProductController extends Controller
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
     * Edit a product
     *
     * @param integer $id
     * @return void
     */
    public function __invoke(int $id)
    {
        $product = $this->productRepository->getProduct($id);
        return Inertia::render('products/EditProduct', [
            'product' => $product
        ]);
    }
}