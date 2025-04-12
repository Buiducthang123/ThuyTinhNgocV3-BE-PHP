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
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->integer('quantity')->default(1)->comment('Số lượng');
            $table->decimal('price', 10, 2)->comment('Giá');
            // % giảm giá
            $table->decimal('discount', 5, 2)->default(0)->comment('Giảm giá');
            // $table->decimal('discount_amount', 10, 2)->default(0)->comment('Số tiền giảm giá');
            // $table->decimal('final_amount', 15, 2)->nullable()->comment('Tổng tiền');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
