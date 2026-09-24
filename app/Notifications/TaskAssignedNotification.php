<?php

namespace App\Notifications;

use App\Models\Task;
use Illuminate\Notifications\Notification;

class TaskAssignedNotification extends Notification
{
    public function __construct(public Task $task, public string $assignerName)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        return [
            'type' => 'task_assigned',
            'task_id' => $this->task->id,
            'task_title' => $this->task->title,
            'message' => "{$this->assignerName} assigned you to \"{$this->task->title}\".",
            'url' => route('tasks.show', $this->task),
        ];
    }
}
