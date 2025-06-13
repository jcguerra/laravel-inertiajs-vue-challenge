<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductEditRequest;
use App\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use Illuminate\Support\Facades\Log;
use App\Models\Product;

class UpdateProductController extends Controller
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
     * Update a product
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function __invoke(ProductEditRequest $request, int $id)
    {
        $validatedData = $request->validated();
        
        $product = $this->productRepository->updateProduct($id, $validatedData);
        return redirect()->route('products.edit', $id)->with('success', 'Product updated successfully');
    }
}