<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete(); // 対象のタスク
            $table->foreignId('user_id')->constrained()->cascadeOnDelete(); // 操作したユーザー
            $table->unsignedInteger('step');                                // 当時のステップ
            $table->string('action');                                       // アクション（step_up, remand など）
            $table->text('comment')->nullable();                            // コメント・理由
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_logs');
    }
};
