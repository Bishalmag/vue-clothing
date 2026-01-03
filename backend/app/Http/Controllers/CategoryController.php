<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categories;

class CategoryController extends Controller
{
    /**
     * GET /api/categories
     * Return all categories
     */
    public function index()
    {
        $categories = Categories::all();

        return response()->json($categories, 200);
    }

    /**
     * POST /api/categories
     * Store a new category
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ]);

        $category = Categories::create($validated);

        return response()->json([
            'message' => 'Category created successfully',
            'data'    => $category
        ], 201);
    }

    /**
     * GET /api/categories/{id}
     * Show a single category
     */
    public function show($id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        return response()->json($category, 200);
    }

    /**
     * PUT /api/categories/{id}
     * Update a category
     */
    public function update(Request $request, $id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        $validated = $request->validate([
            'name'        => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json([
            'message' => 'Category updated successfully',
            'data'    => $category
        ], 200);
    }

    /**
     * DELETE /api/categories/{id}
     * Delete a category
     */
    public function destroy($id)
    {
        $category = Categories::find($id);

        if (!$category) {
            return response()->json([
                'message' => 'Category not found'
            ], 404);
        }

        $category->delete();

        return response()->json([
            'message' => 'Category deleted successfully'
        ], 200);
    }
}
