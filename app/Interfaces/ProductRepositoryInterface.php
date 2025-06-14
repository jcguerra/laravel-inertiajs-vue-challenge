<?php

namespace App\Interfaces;

use App\Models\Product;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    /**
     * Get all products
     */
    public function getAllProducts();

    /**
     * Create a product
     *
     * @param array $data
     * @return Product
     */
    public function createProduct(array $data): Product;

    /**
     * Get a product by its ID
     *
     * @param integer $id
     * @return Product
     */
    public function getProduct(int $id): Product;
}