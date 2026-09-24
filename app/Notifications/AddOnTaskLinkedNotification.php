<?php

namespace App\Notifications;

use App\Models\AddOnTask;
use Illuminate\Notifications\Notification;

class AddOnTaskLinkedNotification extends Notification
{
    public function __construct(public AddOnTask $link)
    {
    }

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $interrupted = $this->link->interruptedTask;
        $addOn = $this->link->addOnTask;

        return [
            'type' => 'add_on_linked',
            'task_id' => $interrupted->id,
            'task_title' => $interrupted->title,
            'message' => "\"{$interrupted->title}\" was paused — you were assigned an urgent task: \"{$addOn->title}\".",
            'url' => route('tasks.show', $interrupted),
        ];
    }
}
