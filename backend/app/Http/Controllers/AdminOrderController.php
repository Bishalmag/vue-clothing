<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    // List all orders (with filters)
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $query = Order::with(['items.product.category', 'items.variation', 'user']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        $orders->getCollection()->transform(function ($order) {
            $order->items->transform(function ($item) {
                return [
                    'id' => $item->order_item_id,
                    'product_id' => $item->product_id,
                    'variation_id' => $item->variation_id,
                    'name' => $item->product->name,
                    'category' => $item->product->category->name ?? '',
                    'price' => $item->product->price,
                    'quantity' => $item->quantity,
                    'size' => $item->variation->size ?? null,
                    'color' => $item->variation->color ?? null,
                    'image' => $item->product->picture
                        ? asset('storage/products/' . $item->product->picture)
                        : asset('storage/products/placeholder.png'),
                    'total_price' => $item->total_price,
                ];
            });
            return $order;
        });

        return response()->json($orders);
    }

    // Show a single order
    public function show(Order $order): JsonResponse
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->load(['items.product.category', 'items.variation', 'user']);

        $items = $order->items->map(function ($item) {
            return [
                'id' => $item->order_item_id,
                'order_id' => $item->order_id,
                'product_id' => $item->product_id,
                'variation_id' => $item->variation_id,
                'name' => $item->product->name,
                'category' => $item->product->category->name ?? '',
                'price' => $item->product->price,
                'quantity' => $item->quantity,
                'size' => $item->variation->size ?? null,
                'color' => $item->variation->color ?? null,
                'image' => $item->product->picture
                    ? asset('storage/products/' . $item->product->picture)
                    : asset('storage/products/placeholder.png'),
                'total_price' => $item->total_price,
            ];
        })->toArray();

        $orderArray = $order->toArray();
        $orderArray['items'] = $items;

        return response()->json($orderArray);
    }

    // Update an order (status, total_amount)
    public function update(Request $request, Order $order): JsonResponse
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|integer|min:0|max:2', // 0 = pending, 1 = shipped, 2 = cancelled
            'total_amount' => 'sometimes|numeric|min:0',
        ]);

        $order->update($validated);

        return response()->json($order);
    }

    // Delete an order
    public function destroy(Order $order): JsonResponse
    {
        $user = Auth::user();
        if (!$user || $user->role !== 'admin') {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->delete();
        return response()->json(null, 204);
    }
}