<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductsService
{
    /**
     * Get All Products function
     *
     * @param Request $params
     * @return LengthAwarePaginator
     */
    public function getAllProducts(Request $params): LengthAwarePaginator
    {
        $query = Product::with(['user']); // Eager loading user relationship
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
}
