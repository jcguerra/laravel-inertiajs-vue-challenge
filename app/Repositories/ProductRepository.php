<?php

namespace App\Repositories;

use App\Interfaces\ProductRepositoryInterface;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProductRepository implements ProductRepositoryInterface
{
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

        return Product::create([
            'user_id' => Auth::user()->id,
            'name' => $data['name'],
            'description' => $data['description'],
            'price' => $data['price'],
            'image' => $imagePath,
            'stock' => $data['stock'],
            'is_active' => true,
        ]);
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

    /**
     * Update a product
     *
     * @param integer $id
     * @param array $data
     * @return Product
     */
    public function updateProduct(int $id, array $data): Product
    {
        // dd("llego al repository product", $data);
        $product = Product::findOrFail($id);
        
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

    /**
     * Delete a product
     *
     * @param integer $id
     * @return void
     */
    public function deleteProduct(int $id): void
    {
        $product = Product::findOrFail($id);
        $product->delete();
        
    }
}