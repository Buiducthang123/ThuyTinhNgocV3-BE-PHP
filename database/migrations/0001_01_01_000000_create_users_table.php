<?php

use App\Enums\AccountStatus;
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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('role_id')->constrained('roles')->onDelete('cascade')->comment('ID của role');
            $table->string('full_name',255)->comment('Họ và tên');
            $table->string('email')->unique()->comment('Email');
            $table->string('avatar')->nullable()->comment('Ảnh đại diện');
            $table->string('google_id')->nullable()->comment('ID của google');
            $table->enum('status', AccountStatus::getValues())->default(AccountStatus::NOT_ACTIVE)->comment('Trạng thái tài khoản');
            $table->timestamp('email_verified_at')->nullable()->comment('Thời gian xác thực email');
            $table->string('password')->comment('Mật khẩu');
            $table->rememberToken();
            $table->softDeletes()->comment('Thời gian xóa mềm');
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
