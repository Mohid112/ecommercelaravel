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
        Schema::create(table: 'customers', callback: function (Blueprint $table): void {
            $table->id();
            $table->string(column: 'name', length: 255);
            $table->string(column: 'email')->nullable()->change();
            $table->string(column: 'phone', length: 20)->nullable();
            $table->text(column: 
            'address')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
