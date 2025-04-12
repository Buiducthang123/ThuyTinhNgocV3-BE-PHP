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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->foreignId('promotion_id')->nullable()->constrained('promotions')->nullOnDelete();
            $table->string('title');
            $table->string('slug')->nullable()->unique()->comment('Slug');
            $table->string('cover_image', 255)->nullable()->comment('Ảnh bìa');
            $table->json('thumbnail')->nullable()->comment('Ảnh minh họa');
            $table->text('short_description')->nullable()->comment('Mô tả ngắn');
            $table->longText('description')->nullable()->comment('Mô tả ngắn');
            $table->boolean('is_sale')->default(false)->comment('Có bán không');
            $table->decimal(('price'), 10, 2)->nullable()->comment('Giá bán ra');
            $table->decimal('discount', 5, 2)->nullable()->comment('Giảm giá');
            $table->float('weight')->comment('Trọng lượng')->nullable();
            $table->float('height')->comment('Chiều cao')->nullable();
            $table->float('dimension_length')->comment('Chiều dài')->nullable();
            $table->float('dimension_width')->comment('Chiều rộng')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
