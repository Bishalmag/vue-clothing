<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product_variation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);

        $query = Order::with(['items.product.category', 'items.variation'])
            ->where('u_id', $user->id);

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

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);

        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'status' => 'sometimes|integer|min:0|max:4',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variation_id' => 'sometimes|nullable|integer',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            foreach ($validated['items'] as $item) {
                $variation = !empty($item['variation_id'])
                    ? Product_variation::find($item['variation_id'])
                    : Product_variation::where('product_id', $item['product_id'])->first();

                if (!$variation) {
                    throw new \Exception("Product variation not found for product ID {$item['product_id']}");
                }

                if ($item['quantity'] > $variation->stock) {
                    throw new \Exception("Requested quantity exceeds stock for variation ID {$variation->id}");
                }
            }

            $order = Order::create([
                'u_id' => $user->id,
                'total_amount' => $validated['total_amount'],
                'status' => $validated['status'] ?? 0,
            ]);

            foreach ($validated['items'] as $item) {
                $variation = !empty($item['variation_id'])
                    ? Product_variation::find($item['variation_id'])
                    : Product_variation::where('product_id', $item['product_id'])->first();

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variation_id' => $variation->id ?? null,
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                ]);

                if ($variation) {
                    $variation->stock -= $item['quantity'];
                    $variation->save();
                }
            }

            DB::commit();
            return response()->json($order->load('items'), 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 422);
        }
    }

    public function show(Order $order): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if ($order->u_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->load(['items.variation', 'items.product.category']);

        $items = $order->items->map(function ($item) {
            $imageUrl = $item->product->picture
                ? asset('storage/products/' . basename($item->product->picture))
                : asset('storage/products/placeholder.png');

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
                'image' => $imageUrl,
                'total_price' => $item->total_price,
            ];
        })->toArray();

        $orderArray = $order->toArray();
        $orderArray['items'] = $items;

        return response()->json($orderArray);
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);
        if ($order->u_id !== $user->id) return response()->json(['error' => 'Unauthorized'], 403);

        $validated = $request->validate([
            'total_amount' => 'sometimes|numeric|min:0',
            'status' => 'sometimes|integer|min:0|max:4',
        ]);

        $order->update($validated);
        return response()->json($order);
    }

    public function destroy(Order $order): JsonResponse
    {
        $user = Auth::user();
        if (!$user) return response()->json(['error' => 'Unauthenticated'], 401);
        if ($order->u_id !== $user->id) return response()->json(['error' => 'Unauthorized'], 403);

        $order->delete();
        return response()->json(null, 204);
    }

    
}