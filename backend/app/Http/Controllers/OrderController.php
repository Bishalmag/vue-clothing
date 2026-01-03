<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product_variation; // use existing model class name
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $query = Order::with('items')->where('u_id', $user->id);

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->latest()->paginate(10);

        return response()->json($orders);
    }

    public function store(Request $request): JsonResponse
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        $validated = $request->validate([
            'total_amount' => 'required|numeric|min:0',
            'status' => 'sometimes|integer|min:0|max:4',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.variation_id' => 'sometimes|nullable|integer', // optional but recommended
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.total_price' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();

        try {
            // 1. Check stock for each item
            foreach ($validated['items'] as $item) {
                // Prefer explicit variation_id if provided
                if (!empty($item['variation_id'])) {
                    $variation = Product_variation::find($item['variation_id']);
                    if (!$variation) {
                        throw new \Exception("Product variation (id {$item['variation_id']}) not found.");
                    }
                } else {
                    // fallback: find a variation that belongs to the product (best-effort)
                    $variation = Product_variation::where('product_id', $item['product_id'])->first();
                    if (!$variation) {
                        throw new \Exception("No product variation found for product ID {$item['product_id']}.");
                    }
                }

                if ($item['quantity'] > $variation->stock) {
                    throw new \Exception("Cannot order more than available stock for product/variation (requested {$item['quantity']}, available {$variation->stock}).");
                }
            }

            // 2. Create order
            $order = Order::create([
                'u_id' => $user->id,
                'total_amount' => $validated['total_amount'],
                'status' => $validated['status'] ?? 0,
            ]);

            // 3. Create order items and reduce stock
            foreach ($validated['items'] as $item) {
                // Use variation_id when available
                $variationId = $item['variation_id'] ?? null;

                // If variationId wasn't provided earlier, try to use the same fallback as before
                if (!$variationId) {
                    $variation = Product_variation::where('product_id', $item['product_id'])->first();
                    $variationId = $variation ? $variation->variation_id : null;
                } else {
                    $variation = Product_variation::find($variationId);
                }

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'variation_id' => $variationId,
                    'quantity' => $item['quantity'],
                    'total_price' => $item['total_price'],
                ]);

                // Reduce stock (only if we have a variation record)
                if ($variation) {
                    $variation->stock -= $item['quantity'];
                    if ($variation->stock < 0) {
                        // additional safety: should not happen due to earlier checks
                        throw new \Exception("Insufficient stock while saving order for variation id {$variation->variation_id}.");
                    }
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

        return response()->json($order->load('items'));
    }

    public function update(Request $request, Order $order): JsonResponse
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if ($order->u_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

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

        if (!$user) {
            return response()->json(['error' => 'Unauthenticated'], 401);
        }

        if ($order->u_id !== $user->id) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $order->delete();

        return response()->json(null, 204);
    }
}