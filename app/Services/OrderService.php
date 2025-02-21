<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function getAllOrders()
    {
        return Order::with('customer', 'orderItems.product')->get();
    }

    public function getOrderById($id)
    {
        return Order::with('customer', 'orderItems.product')->findOrFail($id);
    }

    public function createOrder($data)
    {
        return DB::transaction(function () use ($data) {
            $totalPrice = 0; // Ensure total_price is initialized properly
            
            $order = Order::create([
                'customer_id' => $data['customer_id'],
                'total_price' => $totalPrice, // Initialize correctly
                'status' => 'pending'
            ]);

            foreach ($data['items'] as $item) {
                $product = Product::findOrFail($item['product_id']);
                $subtotal = $product->price * $item['quantity'];

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                    'subtotal' => $subtotal
                ]);

                $totalPrice += $subtotal;
            }

            $order->update(['total_price' => $totalPrice]);

            return $order;
        });
    }

    public function updateOrderStatus($id, $status)
    {
        $order = Order::findOrFail($id);
        $order->update(['status' => $status]);
        return $order;
    }

    public function deleteOrder($id)
    {
        $order = Order::findOrFail($id);
        return $order->delete();
    }
}
