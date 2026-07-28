<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    public function stepUp(Request $request, Task $task)
    {
        $user = Auth::user();
        // --- ここから権限チェック ---`
        if ($task->current_step === 1 && !$user->isLeader()) {
            return back()->with('error', 'ステップ1の承認はリーダー層のみ可能です。');
        }

        if ($task->current_step === 2 && !$user->isManager()) {
            return back()->with('error', 'ステップ2の承認はマネージャー層のみ可能です。');
        }
        
        if ($task->current_step === 3 && !$user->isExecutive()) {
            return back()->with('error', 'ステップ3の承認は役員層のみ可能です。');
        }
        // --- ここまで ---

       // --- ここから下が実際にステップを進める処理 ---
        $previousStep = $task->current_step;
        $task->current_step += 1;
        $task->save();
        
        return redirect()->route('tasks.index')->with('success', 'ステップ' . $previousStep . 'からステップ' . $task->current_step . 'に進めました。');
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