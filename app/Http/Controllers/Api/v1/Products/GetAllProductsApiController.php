<?php

namespace App\Http\Controllers\Api\v1\Products;

use App\Http\Controllers\Controller;
use App\Services\PaginationService;
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
     * @param PaginationService $paginationService
     */
    public function __construct(
        private readonly ProductsService $productService,
        private readonly PaginationService $paginationService
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
            'pagination' => $this->paginationService->getPagination($products)
        ]);
    }
}