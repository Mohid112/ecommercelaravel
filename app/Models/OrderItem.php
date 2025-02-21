<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = ['order_id', 'product_id', 'quantity', 'price'];

    /**
     * Relationship with Order (Many-to-One).
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(related: Order::class);
    }

    /**
     * Relationship with Product (Many-to-One).
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(related: Product::class);
    }
}

