<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\CategoryService;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
    protected $categoryService;

    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Display a listing of categories.
     */
    public function index()
    {
        return response()->json($this->categoryService->getAllCategories(), Response::HTTP_OK);
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
        ]);

        $category = $this->categoryService->addCategory($validatedData);

        return response()->json($category, Response::HTTP_CREATED);
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        return response()->json($this->categoryService->getCategoryById($id), Response::HTTP_OK);
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'name' => 'sometimes|required|string|max:255|unique:categories,name,' . $id,
        ]);

        $category = $this->categoryService->updateCategory($id, $validatedData);

        return response()->json($category, Response::HTTP_OK);
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id)
    {
        $this->categoryService->deleteCategory($id);

        return response()->json(['message' => 'Category deleted successfully'], Response::HTTP_OK);
    }
}
