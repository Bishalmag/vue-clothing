<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Products;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index()
    {
        $products = Products::with(['category:id,name', 'variations'])->latest()->get();
        return response()->json($products, 200);
    }

    public function show($id)
    {
        $product = Products::with(['category:id,name', 'variations'])->find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        return response()->json($product, 200);
    }

    public function store(Request $request)
    {
        if ($request->has('variations')) {
            $request->merge(['variations' => json_decode($request->input('variations'), true)]);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'picture' => 'nullable|image|max:2048',
            'variations' => 'required|array|min:1',
            'variations.*.color' => 'required|string|max:100',
            'variations.*.size' => 'required|string|max:50',
            'variations.*.stock' => 'required|integer|min:0',
        ]);

        if ($request->hasFile('picture')) {
            $validated['picture'] = $request->file('picture')->store('products', 'public');
        }

        $product = Products::create([
            'name' => $validated['name'],
            'price' => $validated['price'],
            'category_id' => $validated['category_id'],
            'description' => $validated['description'] ?? null,
            'picture' => $validated['picture'] ?? null
        ]);

        foreach ($validated['variations'] as $variation) {
            $product->variations()->create($variation);
        }

        return response()->json($product->load('variations'), 201);
    }

   public function update(Request $request, $id)
{
    $product = Products::find($id);
    if (!$product) {
        return response()->json(['message' => 'Product not found'], 404);
    }

    if ($request->has('variations') && is_string($request->variations)) {
        $request->merge([
            'variations' => json_decode($request->variations, true)
        ]);
    }

    $validated = $request->validate([
        'name' => 'sometimes|required|string|max:255',
        'price' => 'sometimes|required|numeric',
        'category_id' => 'sometimes|required|exists:categories,id',
        'description' => 'nullable|string',
        'picture' => 'nullable|image|max:2048',
        'variations' => 'nullable|array',
        'variations.*.color' => 'required_with:variations|string|max:100',
        'variations.*.size' => 'required_with:variations|string|max:50',
        'variations.*.stock' => 'required_with:variations|integer|min:0',
    ]);

    if ($request->hasFile('picture')) {
        if ($product->picture) {
            Storage::disk('public')->delete($product->picture);
        }
        $validated['picture'] = $request->file('picture')->store('products', 'public');
    }

    $product->update($validated);

    if (isset($validated['variations'])) {
        $product->variations()->delete();
        foreach ($validated['variations'] as $variation) {
            $product->variations()->create($variation);
        }
    }

    $product->load(['category:id,name', 'variations']);

    return response()->json($product, 200);
}



    public function destroy($id)
    {
        $product = Products::find($id);
        if (!$product) return response()->json(['message' => 'Product not found'], 404);

        if ($product->picture) Storage::disk('public')->delete($product->picture);
        $product->delete();

        return response()->json(['message' => 'Product deleted successfully'], 200);
    }
}
