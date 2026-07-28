<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\TaskLog;
use Illuminate\Http\Request;

class ApprovalController extends Controller
{
    // STEP UP（承認・次段階への上程）処理
    public function stepUp(Request $request, Task $task)
    {
        $maxStep = 3; // 最終ステップ数

        if ($task->current_step < $maxStep) {
            $task->current_step += 1;
            $task->status = 'pending';
            $action = 'step_up';
        } else {
            // 最終ステップ完了
            $task->status = 'approved';
            $action = 'approved';
        }
        
        $task->comment = $request->input('comment');
        $task->save();

        // ▼ 承認履歴（ログ）の保存
        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'step'    => $task->current_step,
            'action'  => $action,
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('success', '次のステップへ上程しました。');
    }

    // 差し戻し処理
    public function remand(Request $request, Task $task)
    {
        $task->current_step = max(1, $task->current_step - 1);
        $task->status = 'remanded';
        $task->comment = $request->input('comment');
        $task->save();

        // ▼ 差し戻し履歴（ログ）の保存
        TaskLog::create([
            'task_id' => $task->id,
            'user_id' => auth()->id(),
            'step'    => $task->current_step,
            'action'  => 'remand',
            'comment' => $request->input('comment'),
        ]);

        return redirect()->back()->with('error', '申請を差し戻しました。');
    }
}