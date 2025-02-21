<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'status', 'total_price', 'order_date'];

    /**
     * Relationship with Customer (Many-to-One).
     */
    public function customer(): BelongsTo
    {
        return $this->belongsTo(related: Customer::class);
    }

    /**
     * Relationship with OrderItem (One-to-Many).
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(related: OrderItem::class);
    }
}