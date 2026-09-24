<?php

namespace App\Notifications;

use App\Models\DelayRequest;
use Illuminate\Notifications\Notification;

class DelayRequestSubmittedNotification extends Notification
{
    public function __construct(public DelayRequest $delayRequest)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $task = $this->delayRequest->task;
        $requesterName = $this->delayRequest->requester->name;

        return [
            'type' => 'delay_request_submitted',
            'task_id' => $task->id,
            'task_title' => $task->title,
            'message' => "{$requesterName} requested a delay on \"{$task->title}\".",
            'url' => route('tasks.show', $task),
        ];
    }
}
