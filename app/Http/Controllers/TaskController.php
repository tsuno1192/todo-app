<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    // タスク一覧表示（インポートされたデータを確認する画面）
    public function index()
    {
        $tasks = Task::latest()->paginate(10);
        return view('tasks.index', compact('tasks'));
    }

    // 編集画面の表示
    public function edit(Task $task)
    {
        return view('tasks.edit', compact('task'));
    }

    // データの更新処理（担当者、詳細、maintenance_system_idなどの追加・変更）
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title'                 => 'required|string|max:255',
            'due_date'              => 'nullable|date',
            'description'           => 'nullable|string',
            'maintenance_system_id' => 'nullable|string|max:255',
            'assignee_id'           => 'nullable|integer', // 必要に応じて担当者IDのバリデーション
        ]);

        $task->update($request->all());

        return redirect()->route('tasks.index')->with('success', 'タスクの情報を更新しました。');
    }
}