<?php

namespace App\Http\Controllers\Products;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\ProductEditRequest;
use App\Services\ProductsService;

class UpdateProductController extends Controller
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
     * Update a product
     *
     * @param Request $request
     * @param integer $id
     * @return void
     */
    public function __invoke(ProductEditRequest $request, int $id)
    {
        $validatedData = $request->validated();
        
        $product = $this->productsService->updateProduct($id, $validatedData);
        return redirect()->route('products.edit', $id)->with('success', 'Product updated successfully');
    }
}