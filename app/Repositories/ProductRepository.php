<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    /**
     * Get all products
     */
    public function getAllProducts()
    {
        return Product::with(['user']); // Return query builder instead of collection
    }

    /**
     * Create a product
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product
    {
        return Product::create($data);
    }

    /**
     * Get a product by its ID
     *
     * @param integer $id
     * @return Product
     */
    public function getProduct(int $id): Product
    {
        return Product::findOrFail($id);
    }
}