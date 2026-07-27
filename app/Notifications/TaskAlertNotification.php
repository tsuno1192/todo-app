<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TaskAlertNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        ->subject('【アラート】期日が近づいているタスクがあります: ' . $this->task->title)
        ->greeting($notifiable->name . ' さん')
        ->line('以下のタスクのアラート日が設定されています。内容を確認してください。')
        ->line('予定: ' . $this->task->title)
        ->line('詳細: ' . $this->task->description)
        ->line('期日: ' . $this->task->due_date)
        ->line('緊急度レベル: ' . $this->task->priority)
        ->action('タスクを確認する', url('/tasks/' . $this->task->id));
    }

    // Microsoft Teams (Incoming Webhook) へ送信する処理の例
    public function sendToTeams()
    {
        $webhookUrl = config('services.teams.webhook_url'); // config/services.php に設定
        if (!$webhookUrl) return;

        Http::post($webhookUrl, [
            '@type' => 'MessageCard',
            '@context' => 'http://schema.org/extensions',
            'summary' => 'タスクアラート通知',
            'title' => '🚨 【タスクアラート】 ' . $this->task->title,
            'text' => "期日: {$this->task->due_date}\n優先度: {$this->task->priority}\n詳細: {$this->task->description}",
            'potentialAction' => [
                [
                    '@type' => 'OpenUri',
                    'name' => 'タスクを開く',
                    'targets' => [
                        ['os' => 'default', 'uri' => url('/tasks/' . $this->task->id)]
                    ]
                ]
            ]
        ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
