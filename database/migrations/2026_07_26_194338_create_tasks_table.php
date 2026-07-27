<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained()->cascadeOnDelete(); // プロジェクトID
            $table->string('title');                                            // 予定（タイトル）
            $table->text('description')->nullable();                            // 詳細内容（何をどうするか）
            $table->date('due_date');                                           // 期日
            $table->date('alert_date')->nullable();                             // アラート日
            $table->enum('priority', ['H', 'N', 'L'])->default('N');            // レベル設定: H(緊急), N(通常), L(低め)
            $table->string('status')->default('pending');                       // ステータス（未着手、進行中、完了など）
            $table->foreignId('assigned_user_id')->nullable()->constrained('users')->nullOnDelete(); // 担当者
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();                // 登録者
            $table->string('maintenance_system_id')->nullable();                // ⑭ 保全管理システムとの紐付けID/コード
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
