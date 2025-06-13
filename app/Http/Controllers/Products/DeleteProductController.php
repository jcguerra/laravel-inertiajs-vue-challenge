<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;

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
        $this->productRepository->deleteProduct($id);
        return redirect()->route('products.index');
    }
}