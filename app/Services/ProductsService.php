<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

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
        $query = Product::query();
        
        // Excluir parámetros de paginación y ordenamiento
        $excludedParams = ['page', 'perPage', 'sort_by', 'sort_direction'];
        
        // Si hay un término de búsqueda, buscar en todas las columnas
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
            // Búsqueda normal por columnas específicas
            foreach ($params->all() as $column => $value) {
                if ($value !== null && $value !== '' && !in_array($column, $excludedParams)) {
                    $query->where($column, 'like', '%' . $value . '%');
                }
            }
        }
        
        $perPage = $params->input('perPage', 10);
        $sortBy = $params->input('sort_by', 'id');
        $sortDirection = $params->input('sort_direction', 'asc');
        $query->orderBy($sortBy, $sortDirection);
        
        return $query->paginate($perPage);
    }
}
