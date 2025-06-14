<?php

namespace App\Services;

use App\Repositories\ProductRepository;
use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProductsService
{
    /**
     * Constructor
     *
     * @param ProductRepository $productRepository
     */
    public function __construct(
        private ProductRepository $productRepository
    ){}

    /**
     * Get All Products function
     *
     * @param Request $params
     * @return LengthAwarePaginator
     */
    public function getAllProducts(Request $params): LengthAwarePaginator
    {
        $query = $this->productRepository->getAllProducts();
        $excludedParams = ['page', 'perPage', 'sort_by', 'sort_direction'];
        
        if ($params->has('search') && $params->search !== null && $params->search !== '') {
            $searchTerm = $params->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('id', 'like', "%{$searchTerm}%")
                  ->orWhere('name', 'like', "%{$searchTerm}%")
                  ->orWhere('description', 'like', "%{$searchTerm}%")
                  ->orWhere('price', 'like', "%{$searchTerm}%")
                  ->orWhere('stock', 'like', "%{$searchTerm}%");
            });
        } else {
            foreach ($params->all() as $column => $value) {
                if ($value !== null && $value !== '' && !in_array($column, $excludedParams)) {
                    // Optimization: use indexes for exact matches when possible
                    if (in_array($column, ['price', 'stock', 'is_active'])) {
                        $query->where($column, $value);
                    } else {
                        $query->where($column, 'like', '%' . $value . '%');
                    }
                }
            }
        }
        
        $perPage = $params->input('perPage', 10);
        $sortBy = $params->input('sort_by', 'id');
        $sortDirection = $params->input('sort_direction', 'asc');
        
        // Optimization: ensure sorting uses indexes
        if (in_array($sortBy, ['name', 'price', 'stock'])) {
            $query->orderBy($sortBy, $sortDirection);
        } else {
            $query->orderBy('id', $sortDirection);
        }
        
        return $query->paginate($perPage);
    }

    /**
     * Create a product
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        $imagePath = null;
        
        if (isset($data['image']) && $data['image']) {
            $image = $data['image'];
            $extension = $image->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            $imagePath = $image->storeAs('products', $fileName, 'public');
        }

        $productData = [
            'user_id' => Auth::user()->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $imagePath,
            'stock' => $data['stock'],
            'is_active' => true,
        ]; 

        return $this->productRepository->createProduct($productData);
    }

    /**
     * Update a product
     *
     * @param integer $id
     * @param array $data
     * @return Product
     */
    public function updateProduct(int $id, array $data): Product
    {
        $product = $this->productRepository->getProduct($id);
        
        if (isset($data['image']) && $data['image']) {
            // Delete old image if exists
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            
            // Store new image
            $image = $data['image'];
            $extension = $image->getClientOriginalExtension();
            $fileName = Str::uuid() . '.' . $extension;
            $data['image'] = $image->storeAs('products', $fileName, 'public');
        } else {
            unset($data['image']);
        }

        $product->update($data);
        return $product;
    }
}
