<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    protected $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    // Get all orders
    public function index()
    {
        return response()->json($this->orderService->getAllOrders());
    }

    // Get a single order by ID
    public function show($id)
    {
        try {
            $order = $this->orderService->getOrderById($id);
            return response()->json($order);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }

    // Create a new order
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|exists:customers,id',
            'items' => 'required|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        try {
            $order = $this->orderService->createOrder($validated);
            return response()->json($order, 201);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to create order'], 500);
        }
    }

    // Update order status
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:pending,processing,completed,canceled',
        ]);

        try {
            $order = $this->orderService->updateOrderStatus($id, $validated['status']);
            return response()->json($order);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }

    // Delete an order
    public function destroy($id)
    {
        try {
            $this->orderService->deleteOrder($id);
            return response()->json(['message' => 'Order deleted successfully']);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json(['message' => 'Order not found'], 404);
        }
    }
}

