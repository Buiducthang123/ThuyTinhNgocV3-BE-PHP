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
        Schema::create('promotions', function (Blueprint $table) {
            $table->id();
            $table->string('title')->comment('Tiêu đề');
            $table->string('slug')->nullable()->unique()->comment('Đường dẫn thân thiện');
            $table->decimal('discount', 5, 2)->default(0)->comment('Giảm giá');
            $table->string('image')->nullable()->comment('Hình ảnh');
            $table->text('description')->nullable()->comment('Mô tả');
            $table->dateTime('start_date')->comment('Ngày bắt đầu');
            $table->dateTime('end_date')->comment('Ngày kết thúc');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('promotions');
    }
};
