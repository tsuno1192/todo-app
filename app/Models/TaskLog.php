<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TaskLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'task_id',
        'user_id',
        'step',
        'action',
        'comment',
    ];

    // 操作したユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 対象タスクとのリレーション
    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
