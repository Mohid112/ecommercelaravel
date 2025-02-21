<?php

namespace App\Services;

use App\Models\Product;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService
{
    /**
     * Add a new product
     */
    public function addProduct($data)
    {
        return Product::create($data);
    }

    /**
     * Get all products
     */
    public function getAllProducts()
    {
        return Product::all();
    }

    /**
     * Search for a product by name
     */
    public function searchProductByName($name)
    {
        return Product::where('name', 'LIKE', "%$name%")->first();
    }

    /**
     * Update product details
     */
    public function updateProduct($id, $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    /**
     * Delete a product
     */
    public function deleteProduct($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return true;
    }

    /**
     * Get top-selling products
     */
    public function getTopSellingProducts($limit)
    {
        return Product::orderBy('quantity_sold', 'desc')->take($limit)->get();
    }
}

