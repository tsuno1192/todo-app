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
            
            // ▼ ここに追加カラムを配置します
            $table->unsignedInteger('current_step')->default(1);
            
            $table->foreignId('project_id')->constrained()->cascadeOnDelete(); // プロジェクトID
            $table->string('title');                                            // 予定（タイトル）
            $table->text('description')->nullable();                            // 詳細内容（何をどうするか）
            $table->date('due_date');                                           // 期日
            $table->date('alert_date')->nullable();                             // アラート日
            $table->enum('priority', ['H', 'N', 'L'])->default('N');            // レベル設定: H(緊急), N(通常), L(低め)
            
            // 注意：すでに 'status' が定義されていますが、仕様に合わせる場合はそのまま、
            // もしくは承認フロー用として既存のものを上書き・調整してください
            $table->string('status')->default('pending');                       // ステータス（申請中、承認済みなど）
            
            // ▼ 成果物URLとコメントもここに追加
            $table->text('document_url')->nullable();                           // 図書URL
            $table->text('comment')->nullable();                              // 差し戻しや承認時のコメント

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