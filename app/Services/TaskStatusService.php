<?php

namespace App\Services;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class TaskStatusService
{
    /**
     * Attempt a manual status transition. Throws if not allowed for this actor/task combo.
     */
    public function transition(Task $task, TaskStatus $target, User $actor, ?string $reason = null, ?string $newDueDate = null): Task
    {
        $current = $task->status;
        $isSuperAdmin = $actor->hasRole('Super Admin');
        $role = $actor->isReviewerFor($task) ? 'reviewer' : 'employee';

        // Managers (and Super Admin) may pull a task back from Completed to
        // any status — reopening finished work is a deliberate override, not
        // a normal workflow transition, so it bypasses the state machine here.
        $canReopenCompleted = $current === TaskStatus::Completed && $actor->hasRole('Manager');
        $bypassesStateMachine = $isSuperAdmin || $canReopenCompleted;

        if (! $bypassesStateMachine && ! $current->canManuallyTransitionTo($target, $role)) {
            throw ValidationException::withMessages([
                'status' => "Cannot move task from \"{$current->label()}\" to \"{$target->label()}\" as {$role}.",
            ]);
        }

        $requiresReason = ! $bypassesStateMachine && (
            in_array($target, [TaskStatus::OnHold, TaskStatus::Cancelled], true)
            || ($current === TaskStatus::PendingReview && $target === TaskStatus::InProgress)
        );
        if ($requiresReason && blank($reason)) {
            throw ValidationException::withMessages([
                'reason' => 'A reason is required for this status change.',
            ]);
        }

        $isSendBack = $current === TaskStatus::PendingReview && $target === TaskStatus::InProgress;

        $updateData = [
            'status' => $target,
            'started_at' => $target === TaskStatus::InProgress && ! $task->started_at ? now() : $task->started_at,
            'completed_at' => $target === TaskStatus::Completed ? now() : $task->completed_at,
        ];

        if ($isSendBack) {
            $updateData['priority'] = 'high';
            $updateData['priority_auto_escalated'] = false;
            if ($newDueDate) {
                $updateData['due_date'] = $newDueDate;
            }
        }

        $task->update($updateData);

        if ($isSendBack) {
            $assignee = $task->currentAssignee();
            if ($assignee && $assignee->id !== $actor->id) {
                $assignee->notify(new \App\Notifications\TaskSentBackNotification($task, $actor->name, $reason ?? 'No reason provided.'));
            }
        }

        $task->update($updateData);

        $task->statusHistory()->create([
            'old_status' => $current->value,
            'new_status' => $target->value,
            'changed_by' => $actor->id,
            'reason' => $reason ?: match (true) {
                $isSuperAdmin => 'Manually overridden by Super Admin.',
                $canReopenCompleted => 'Task reopened from Completed by Manager.',
                default => null,
            },
            'changed_at' => now(),
        ]);

        // Auto-approve: if the assignee is a trusted employee, skip straight past
        // manual review the moment they submit — no reviewer action needed.
        if ($target === TaskStatus::PendingReview) {
            $assignee = $task->currentAssignee();

            if ($assignee && $assignee->auto_approve_tasks) {
                $task->update([
                    'status' => TaskStatus::Completed,
                    'completed_at' => now(),
                ]);

                $task->statusHistory()->create([
                    'old_status' => TaskStatus::PendingReview->value,
                    'new_status' => TaskStatus::Completed->value,
                    'changed_by' => null,
                    'reason' => "Auto-approved — {$assignee->name} is a trusted employee.",
                    'changed_at' => now(),
                ]);
            }
        }

        return $task->refresh();
    }

    /**
     * Restore a task's working status after a delay request is resolved,
     * or an add-on interruption is resumed — accounting for whichever
     * blocking condition is still active.
     */
    public function restoreWorkingStatus(Task $task, User $actor): Task
    {
        $target = $task->activeInterruption() ? TaskStatus::OnHold : TaskStatus::InProgress;

        $task->update(['status' => $target]);

        $task->statusHistory()->create([
            'old_status' => $task->getOriginal('status'),
            'new_status' => $target->value,
            'changed_by' => $actor->id,
            'reason' => 'Auto-restored after delay/interruption resolution.',
            'changed_at' => now(),
        ]);

        return $task->refresh();
    }

    /**
     * Any task still open whose due date was before today gets bumped to
     * High priority (unless already Critical), flagged as auto-escalated.
     * Idempotent — safe to call on every page load, won't re-bump tasks
     * already escalated or downgrade a manually-set Critical priority.
     */
    public function escalateYesterdaysPending(\Illuminate\Support\Collection $tasks): \Illuminate\Support\Collection
    {
        $escalated = collect();

        foreach ($tasks as $task) {
            $isStillPending = $task->status === TaskStatus::Pending;
            $wasDueBeforeToday = $task->due_date->lt(now()->startOfDay());
            $alreadyHigh = in_array($task->priority->value, ['high', 'critical'], true);

            if ($isStillPending && $wasDueBeforeToday && ! $alreadyHigh) {
                $oldPriority = $task->priority->value;

                $task->update([
                    'priority' => 'high',
                    'priority_auto_escalated' => true,
                ]);

                $task->statusHistory()->create([
                    'old_status' => $task->status->value,
                    'new_status' => $task->status->value,
                    'changed_by' => null,
                    'reason' => "Priority auto-escalated from {$oldPriority} to high — was still pending as of yesterday.",
                    'changed_at' => now(),
                ]);

                $escalated->push($task);
            }
        }

        return $escalated;
    }

    /**
     * Quick-resolve action from the overdue-pending review modal: marks a
     * still-Pending task as Completed directly, bypassing the normal
     * workflow (no In Progress → Pending Review steps). Intentionally
     * narrow — only for this specific "clear out neglected pending work"
     * action, restricted to reviewers at the call site.
     */
    public function quickCompleteFromPending(Task $task, User $actor): Task
    {
        $oldStatus = $task->status;

        $task->update([
            'status' => TaskStatus::Completed,
            'completed_at' => now(),
        ]);

        $task->statusHistory()->create([
            'old_status' => $oldStatus->value,
            'new_status' => TaskStatus::Completed->value,
            'changed_by' => $actor->id,
            'reason' => "Marked complete directly from Pending by {$actor->name}, via overdue-tasks review.",
            'changed_at' => now(),
        ]);

        return $task->refresh();
    }
}
