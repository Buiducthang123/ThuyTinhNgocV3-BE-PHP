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
            $table->string('company_name',255)->nullable()->comment('Tên công ty');
            $table->string('company_address')->nullable()->comment('Địa chỉ công ty');
            $table->string('company_phone_number',11)->nullable()->comment('Số điện thoại công ty');
            $table->string('company_tax_code',15)->nullable()->comment('Mã số thuế công ty');
            $table->string('contact_person_name',255)->nullable()->comment('Tên người liên hệ');
            $table->string('representative_id_card',length: 12)->nullable()->comment('Số CMND người đại diện');
            $table->string('representative_id_card_date',10)->nullable()->comment('Ngày cấp CMND');
            $table->string('contact_person_position',255)->nullable()->comment('Chức vụ người liên hệ');
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
