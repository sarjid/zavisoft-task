<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOrderRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductVariation;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request)
    {
        $data = $request->validated();
        $items = $data['items'];
        $order = null;

        DB::transaction(function () use (&$order, $data, $items) {
            $order = Order::create([
                'customer_name' => $data['name'],
                'phone' => $data['phone'],
                'address' => $data['address'],
                'notes' => $data['notes'] ?? null,
                'subtotal' => 0,
                'total' => 0,
                'status' => 'pending',
            ]);

            $subtotal = 0;

            foreach ($items as $item) {
                $quantity = (int) $item['quantity'];
                $product = Product::query()->lockForUpdate()->find($item['product_id']);
                if (!$product) {
                    throw ValidationException::withMessages([
                        'items' => ['Product not found.'],
                    ]);
                }

                $variant = null;
                if (!empty($item['product_variation_id'])) {
                    $variant = ProductVariation::query()
                        ->lockForUpdate()
                        ->where('id', $item['product_variation_id'])
                        ->where('product_id', $product->id)
                        ->first();
                    if (!$variant) {
                        throw ValidationException::withMessages([
                            'items' => ['Product variant not found.'],
                        ]);
                    }
                    if ($variant->quantity < $quantity) {
                        throw ValidationException::withMessages([
                            'items' => ['Insufficient variant stock for ' . $product->name . '.'],
                        ]);
                    }
                }

                if ($product->current_stock < $quantity) {
                    throw ValidationException::withMessages([
                        'items' => ['Insufficient stock for ' . $product->name . '.'],
                    ]);
                }

                $unitPrice = $variant ? $variant->current_price : $product->current_price;
                $lineTotal = round($unitPrice * $quantity, 2);
                $subtotal += $lineTotal;

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'product_variation_id' => $variant?->id,
                    'product_name' => $product->name,
                    'sku' => $variant?->sku ?? $product->sku,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'total' => $lineTotal,
                    'options' => $item['options'] ?? [],
                ]);

                $product->decrement('current_stock', $quantity);
                if ($variant) {
                    $variant->decrement('quantity', $quantity);
                }
            }

            $order->update([
                'subtotal' => $subtotal,
                'total' => $subtotal,
            ]);
        });

        return $this->successResponse([
            'order_id' => $order->id,
        ], 'Order placed successfully', 201);
    }
}
