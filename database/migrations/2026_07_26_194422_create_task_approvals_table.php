<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('task_approvals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('task_id')->constrained()->cascadeOnDelete();     // 対象タスク
            $table->integer('step_number')->default(1);                         // 現在のステップ（1〜10）
            $table->foreignId('approver_id')->constrained('users')->cascadeOnDelete(); // 承認者ID
            $table->string('status')->default('pending');                       // ステータス（未承認 / 承認済 / 差し戻し）
            $table->text('document_url')->nullable();                           // ⑨ 図書のリンク先
            $table->text('comment')->nullable();                                // 承認・差し戻しコメント
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('task_approvals');
    }
};