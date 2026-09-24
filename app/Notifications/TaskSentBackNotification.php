<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Notifications\Notification;

class TaskSentBackNotification extends Notification
{
    public function __construct(public Task $task, public string $reviewerName, public string $reason)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'task_sent_back',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'message' => "{$this->reviewerName} sent \"{$this->task->title}\" back for changes: {$this->reason}",
            'url' => route('tasks.show', $this->task),
        ];
    }
}