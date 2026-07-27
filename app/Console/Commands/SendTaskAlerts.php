<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Task;
use App\Notifications\TaskAlertNotification;
use Illuminate\Support\Facades\Log;

class SendTaskAlerts extends Command
{
    protected $signature = 'tasks:send-alerts';
    protected $description = 'アラート日が本日の未完了タスクについて、本人と上司に通知を送信する';

    public function handle()
    {
        $today = now()->toDateString();

        // アラート日が本日であり、かつ完了していないタスクを抽出
        $tasks = Task::with(['assignedUser.superior', 'createdBy.superior'])
            ->where('alert_date', $today)
            ->where('status', '!=', 'completed')
            ->get();

        foreach ($tasks as $task) {
            $recipients = [];

            // 1. 担当者（assigned_user_id）へ通知
            if ($task->assignedUser) {
                $recipients[] = $task->assignedUser;
                
                // 直属の上司へも通知（担当者の上司）
                if ($task->assignedUser->superior) {
                    $recipients[] = $task->assignedUser->superior;
                }
            }

            // 2. 登録者（created_by）へ通知（担当者と重複する場合は除外）
            if ($task->createdBy && !in_array($task->createdBy->id, array_column($recipients, 'id'))) {
                $recipients[] = $task->createdBy;
                
                // 登録者の上司へも通知
                if ($task->createdBy->superior) {
                    $recipients[] = $task->createdBy->superior;
                }
            }

            // 重複を排除して通知を送信
            $uniqueRecipients = collect($recipients)->unique('id');

            foreach ($uniqueRecipients as $recipient) {
                try {
                    $recipient->notify(new TaskAlertNotification($task));
                } catch (\Exception $e) {
                    Log::error("アラート通知送信失敗 (Task ID: {$task->id}, User ID: {$recipient->id}): " . $e->getMessage());
                }
            }
        }

        $this->info('アラート通知の処理が完了しました。送信対象件数: ' . $tasks->count());
    }
}