<?php

namespace App\Imports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\ToModel;

class TasksImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Task([
            'title'                 => $row['title'] ?? null,                 // タスク名
            'due_date'              => $row['due_date'] ?? null,              // 期日
            'description'           => $row['description'] ?? null,           // 詳細内容
            'maintenance_system_id' => $row['maintenance_system_id'] ?? null, // 保全管理システム紐付けID
            // 必要に応じて担当者IDなどのマッピングを追加
        ]);
    }
}
