<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');                      // ユーザー名
            $table->string('email')->unique();           // メールアドレス
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->foreignId('superior_id')->nullable()->constrained('users')->nullOnDelete(); // 直属の上司ID (階層構造用)
            $table->unsignedBigInteger('department_id')->nullable(); // 組織・課のID
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};