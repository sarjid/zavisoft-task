<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderListResource;
use App\Http\Resources\OrderResource;
use App\Models\Order;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage') ?: 10;
        $search = $request->input('search');

        $orders = Order::query()
            ->withCount('items')
            ->when($search, function ($query) use ($search) {
                $query->where('customer_name', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('id', $search);
            })
            ->orderByDesc('id')
            ->paginate($perPage)
            ->withQueryString();

        return OrderListResource::collection($orders);
    }

    public function show(Order $order)
    {
        $order->load('items');

        return $this->successResponse([
            'order' => new OrderResource($order),
        ]);
    }
}
