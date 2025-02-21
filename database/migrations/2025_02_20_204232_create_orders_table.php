<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(table: 'orders', callback: function (Blueprint $table) {
            $table->id();
            $table->foreignId(column: 'customer_id')->constrained(table: 'customers')->onDelete(action: 'cascade');
            $table->decimal(column: 'total_price', total: 10, places: 2)->default(value: 0);
            $table->enum(column: 'status', allowed: ['pending', 'processing', 'shipped', 'delivered', 'cancelled'])->default(value: 'pending');
            $table->date(column: 'order_date')->default(now()); // New order_date column
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(table: 'orders');
    }
};
