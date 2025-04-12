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
        Schema::create('shipping_addresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->comment('ID của user');
            $table->string('receiver_name', length: 255)->comment('Tên người nhận');
            $table->string('receiver_phone_number', 11)->comment('Số điện thoại người nhận');
            $table->json('province')->comment('Tỉnh/Thành phố');
            $table->json('district')->comment('Quận/Huyện');
            $table->json('ward')->comment('Phường/Xã');
            $table->string('specific_address', 255)->comment('Địa chỉ cụ thể');
            $table->boolean('is_default')->default(false)->comment('Địa chỉ mặc định');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_addresses');
    }
};
