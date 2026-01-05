<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Admin; // Add this import
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class AdminOrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        // Check if admin is authenticated using the 'admin' guard
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        \Log::info('Admin accessing orders:', [
            'admin_id' => $admin->id,
            'username' => $admin->username,
            'email' => $admin->email
        ]);

        $query = Order::with(['user', 'items.product.category', 'items.variation']);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->get();

        $orders->transform(function ($order) {
            $order->items->transform(function ($item) {
                return [
                    'order_item_id' => $item->order_item_id,
                    'product_id' => $item->product_id,
                    'variation_id' => $item->variation_id,
                    'name' => $item->product->name ?? 'Unknown Product',
                    'category' => $item->product->category->name ?? '',
                    'price' => $item->product->price ?? 0,
                    'quantity' => $item->quantity,
                    'size' => $item->variation->size ?? null,
                    'color' => $item->variation->color ?? null,
                    'image' => $item->product->picture 
                        ? asset('storage/products/' . $item->product->picture)
                        : asset('storage/products/placeholder.png'),
                    'total_price' => $item->total_price,
                    'created_at' => $item->created_at,
                    'updated_at' => $item->updated_at,
                ];
            });
            
            return [
                'id' => $order->id,
                'u_id' => $order->u_id,
                'total_amount' => $order->total_amount,
                'status' => $order->status,
                'created_at' => $order->created_at,
                'updated_at' => $order->updated_at,
                'user' => $order->user,
                'items' => $order->items
            ];
        });

        return response()->json(['data' => $orders]);
    }

    // Update other methods similarly...
    public function show(Order $order): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        $order->load(['items.product.category', 'items.variation', 'user']);

        $items = $order->items->map(function ($item) {
            return [
                'order_item_id' => $item->order_item_id,
                'product_id' => $item->product_id,
                'variation_id' => $item->variation_id,
                'name' => $item->product->name ?? 'Unknown Product',
                'category' => $item->product->category->name ?? '',
                'price' => $item->product->price ?? 0,
                'quantity' => $item->quantity,
                'size' => $item->variation->size ?? null,
                'color' => $item->variation->color ?? null,
                'image' => $item->product->picture 
                    ? asset('storage/products/' . $item->product->picture)
                    : asset('storage/products/placeholder.png'),
                'total_price' => $item->total_price,
                'created_at' => $item->created_at,
                'updated_at' => $item->updated_at,
            ];
        })->toArray();

        $orderArray = $order->toArray();
        $orderArray['items'] = $items;

        return response()->json($orderArray);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        $validated = $request->validate([
            'status' => 'sometimes|integer|min:0|max:2',
            'total_amount' => 'sometimes|numeric|min:0',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $admin = Auth::guard('admin')->user();
        
        if (!$admin) {
            return response()->json(['error' => 'Unauthorized. Admin access required.'], 403);
        }

        $order->delete();
        return response()->json(null, 204);
    }
}