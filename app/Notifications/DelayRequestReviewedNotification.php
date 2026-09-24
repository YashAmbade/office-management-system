<?php

namespace App\Notifications;

use App\Models\DelayRequest;
use Illuminate\Notifications\Notification;

class DelayRequestReviewedNotification extends Notification
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
        $decision = $this->delayRequest->status; // approved | rejected
        $reviewerName = $this->delayRequest->reviewer->name;

        return [
            'type' => 'delay_request_reviewed',
            'task_id' => $task->id,
            'task_title' => $task->title,
            'message' => "{$reviewerName} {$decision} your delay request on \"{$task->title}\".",
            'url' => route('tasks.show', $task),
        ];
    }
}
