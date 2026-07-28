<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class TasksImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @xlreturn \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Task([
            'title'                 => $row['title'] ?? null,                  // タスク名
            'due_date'              => $row['due_date'] ?? null,               // 期日
            'description'           => $row['description'] ?? null,            // 詳細内容
            'maintenance_system_id' => $row['maintenance_system_id'] ?? null,  // 保全管理システム紐付けID
            'document_url'          => $row['document_url'] ?? null,           // 図書URL
            'current_step'          => 1,                                      // 初期ステップ
            'status'                => 'pending',                              // ステータス
            'user_id'               => auth()->id() ?? null,                   // 登録者ID
        ]);
    }
}