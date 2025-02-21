<?php

namespace App\Services;

use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Carbon\Carbon;

class ReportService
{
    /**
     * Get all products in a given category.
     */
    public function getProductsByCategory(int $categoryId): Collection
    {
        return Product::where(column: 'category_id', operator: $categoryId)->get();
    }

    /**
     * Get all orders within a specific date range.
     */
    public function getOrdersByDateRange(string $startDate, string $endDate): Collection
    {
        return Order::whereBetween(column: 'created_at', values: [$startDate, $endDate])
            ->with(relations: ['customer', 'orderItems.product'])
            ->get();
    }

    /**
     * Get top-selling products.
     */
    public function getTopSellingProducts(int $limit = 10): Collection
    {
        return OrderItem::select('product_id', DB::raw('SUM(quantity) as total_quantity'))
            ->groupBy('product_id')
            ->orderByDesc('total_quantity')
            ->with('product')
            ->limit($limit)
            ->get();
    }
}
