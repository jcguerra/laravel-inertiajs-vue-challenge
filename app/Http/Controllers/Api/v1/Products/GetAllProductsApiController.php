<?php

namespace App\Http\Controllers\Api\v1\Products;

use App\Http\Controllers\Controller;
use App\Services\ProductsService;
use App\Traits\ApiResponser;
use App\Http\Resources\ProductResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GetAllProductsApiController extends Controller
{
    use ApiResponser;

    /**
     * Constructor
     *
     * @param ProductsService $productService
     */
    public function __construct(
        private readonly ProductsService $productService
    ){}

    /**
     * Get all products
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function __invoke(Request $request): JsonResponse
    {
        $products = $this->productService->getAllProducts($request);
        return $this->showMessage([
            'products' => ProductResource::collection($products),
            'pagination' => [
                'total' => $products->total(),
                'per_page' => $products->perPage(),
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'from' => $products->firstItem(),
                'to' => $products->lastItem()
            ]
        ]);
    }
}