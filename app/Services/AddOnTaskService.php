<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\AddOnTask;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AddOnTaskService
{
    /**
     * Link a newly created add-on task to the task it interrupts, and
     * auto-pause the interrupted task. This is the "urgent work justifies
     * the delay" feature — the whole point of Phase 1's accountability model.
     */
    public function linkInterruption(Task $interruptedTask, Task $addOnTask, User $employee, User $createdBy, ?string $reason = null): AddOnTask
    {
        return DB::transaction(function () use ($interruptedTask, $addOnTask, $employee, $createdBy, $reason) {
            $link = AddOnTask::create([
                'interrupted_task_id' => $interruptedTask->id,
                'add_on_task_id' => $addOnTask->id,
                'employee_id' => $employee->id,
                'interrupted_at' => now(),
                'reason' => $reason,
                'created_by' => $createdBy->id,
            ]);

            $employee->notify(new \App\Notifications\AddOnTaskLinkedNotification($link));

            $oldStatus = $interruptedTask->status;

            $interruptedTask->update(['status' => TaskStatus::OnHold]);

            $interruptedTask->statusHistory()->create([
                'old_status' => $oldStatus->value,
                'new_status' => TaskStatus::OnHold->value,
                'changed_by' => $createdBy->id,
                'reason' => $reason ?? "Auto-paused: employee assigned urgent add-on task \"{$addOnTask->title}\".",
                'changed_at' => now(),
            ]);

            $addOnTask->update(['task_type' => 'add_on', 'parent_interrupted_task_id' => $interruptedTask->id]);

            return $link;
        });
    }

    /**
     * Mark an interruption as resumed and restore the original task to
     * in_progress (unless another interruption is still active).
     */
    public function resume(AddOnTask $link, User $actor): AddOnTask
    {
        return DB::transaction(function () use ($link, $actor) {
            $link->update(['resumed_at' => now()]);

            $task = $link->interruptedTask;
            $stillBlocked = $task->interruptions()->whereNull('resumed_at')->exists();

            if (! $stillBlocked) {
                $oldStatus = $task->status;
                $task->update(['status' => TaskStatus::InProgress]);

                $task->statusHistory()->create([
                    'old_status' => $oldStatus->value,
                    'new_status' => TaskStatus::InProgress->value,
                    'changed_by' => $actor->id,
                    'reason' => 'Resumed after add-on task completed.',
                    'changed_at' => now(),
                ]);
            }

            return $link->refresh();
        });
    }
}
