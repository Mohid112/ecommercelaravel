<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    // Add a new product
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric',
            'quantity' => 'required|integer',
        ]);

        $product = $this->productService->addProduct($validated);
        return response()->json($product, 201);
    }

    // Get all products
    public function index()
    {
        return response()->json($this->productService->getAllProducts());
    }

    // Search for a product by name
    public function search(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string',
        ]);

        $product = $this->productService->searchProductByName($validated['name']);
        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }
        return response()->json($product);
    }

    // Update a product
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'price' => 'sometimes|numeric',
            'quantity' => 'sometimes|integer',
        ]);

        try {
            $updatedProduct = $this->productService->updateProduct($id, $validated);
            return response()->json($updatedProduct);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    // Delete a product
    public function destroy($id)
    {
        try {
            $this->productService->deleteProduct($id);
            return response()->json(['message' => 'Product deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Product not found'], 404);
        }
    }

    // Get top-selling products
    public function topSelling($limit)
    {
        return response()->json($this->productService->getTopSellingProducts($limit));
    }
}
