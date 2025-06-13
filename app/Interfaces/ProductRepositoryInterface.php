<?php

namespace App\Interfaces;

use App\Models\Product;

interface ProductRepositoryInterface
{
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

    /**
     * Update a product
     *
     * @param integer $id
     * @param array $data
     * @return Product
     */
    public function updateProduct(int $id, array $data): Product;

    /**
     * Delete a product
     *
     * @param integer $id
     * @return void
     */
    public function deleteProduct(int $id): void;
}