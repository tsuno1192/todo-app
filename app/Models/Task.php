<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    // 一括代入を許可するカラム
    protected $fillable = [
        'project_id',
        'title',
        'description',
        'due_date',
        'alert_date',
        'priority',
        'status',
        'assigned_user_id',
        'created_by',
        'maintenance_system_id',
        'current_step',
        'document_url',
        'comment',
    ];

    // 承認履歴（ログ）とのリレーション
    public function logs()
    {
        return $this->hasMany(TaskLog::class);
    }

    // プロジェクトとのリレーション
    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    // 担当者とのリレーション
    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_user_id');
    }

    // 登録者とのリレーション
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}