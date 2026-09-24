<?php

namespace App\Livewire;

use App\Enums\TaskStatus;
use App\Models\DelayRequest;
use App\Models\Task;
use App\Services\TaskStatusService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TaskDetail extends Component
{
    public Task $task;
    public string $activeTab = 'comments';

    // Comment form
    public string $newComment = '';

    // Delay request form
    public string $delayReason = '';
    public string $delayNewDueDate = '';

    // Delay review (reviewer side)
    public ?int $reviewingDelayId = null;
    public string $reviewComment = '';

    public bool $showEditTaskModal = false;
    public string $editTitle = '';
    public string $editDescription = '';
    public string $editPriority = '';
    public string $editDueDate = '';
    public ?int $editAssigneeId = null;

    public string $reassignReason = '';

    public function mount(Task $task): void
    {
        $this->task = $task;
        $this->delayNewDueDate = $task->due_date->addDays(2)->format('Y-m-d\TH:i');
    }

    #[Computed]
    public function isReviewer(): bool
    {
        return Auth::user()->isReviewerFor($this->task);
    }

    #[Computed]
    public function isAssignee(): bool
    {
        return $this->task->currentAssignee()?->id === Auth::id();
    }

    #[Computed]
    public function availableTransitions()
    {
        $role = $this->isReviewer ? 'reviewer' : 'employee';
        $allowed = $this->task->status->allowedTransitions();

        $targets = [];
        foreach ($allowed as $statusValue => $roles) {
            if (in_array($role, $roles, true) || Auth::user()->hasRole('Super Admin')) {
                $targets[] = TaskStatus::from($statusValue);
            }
        }

        return collect($targets);
    }

    public function changeStatus(string $target, ?string $reason = null, ?string $newDueDate = null): void
    {
        try {
            app(TaskStatusService::class)->transition(
                $this->task,
                TaskStatus::from($target),
                Auth::user(),
                $reason,
                $newDueDate
            );
            $this->task->refresh();
            $this->dispatch('task-updated');
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->addError('status', $e->getMessage());
        }
    }

    public function postComment(): void
    {
        $this->validate(['newComment' => 'required|string|max:2000']);

        $this->task->comments()->create([
            'user_id' => Auth::id(),
            'comment' => $this->newComment,
        ]);

        $this->newComment = '';
        unset($this->comments);
    }

    #[Computed]
    public function comments()
    {
        return $this->task->comments()->with('user')->get();
    }

    public function submitDelayRequest(): void
    {
        Gate::authorize('view', $this->task);

        $this->validate([
            'delayReason' => 'required|string|max:1000',
            'delayNewDueDate' => 'required|date|after:now',
        ]);

        $delayRequest = $this->task->delayRequests()->create([
            'requested_by' => Auth::id(),
            'reason' => $this->delayReason,
            'requested_new_due_date' => $this->delayNewDueDate,
            'original_due_date' => $this->task->due_date,
            'status' => 'pending',
        ]);

        $this->task->creator->notify(new \App\Notifications\DelayRequestSubmittedNotification($delayRequest));

        $oldStatus = $this->task->status;
        $this->task->update(['status' => TaskStatus::Delayed]);
        $this->task->statusHistory()->create([
            'old_status' => $oldStatus->value,
            'new_status' => TaskStatus::Delayed->value,
            'changed_by' => Auth::id(),
            'reason' => 'Delay requested: ' . $this->delayReason,
            'changed_at' => now(),
        ]);

        $this->delayReason = '';
        $this->task->refresh();
        unset($this->delayRequests);
    }

    public function startReview(int $delayRequestId): void
    {
        $this->reviewingDelayId = $delayRequestId;
        $this->reviewComment = '';
    }

    public function decideDelay(string $decision): void
    {
        Gate::authorize('approveDelay', $this->task);

        $delayRequest = DelayRequest::findOrFail($this->reviewingDelayId);

        $delayRequest->update([
            'status' => $decision, // 'approved' | 'rejected'
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now(),
            'review_comment' => $this->reviewComment ?: null,
        ]);

        $delayRequest->requester->notify(new \App\Notifications\DelayRequestReviewedNotification($delayRequest));

        if ($decision === 'approved') {
            $this->task->update(['due_date' => $delayRequest->requested_new_due_date]);
        }

        app(\App\Services\TaskStatusService::class)->restoreWorkingStatus($this->task, Auth::user());

        $this->reviewingDelayId = null;
        $this->task->refresh();
        unset($this->delayRequests);
    }

    #[Computed]
    public function delayRequests()
    {
        return $this->task->delayRequests()->with(['requester', 'reviewer'])->get();
    }

    #[Computed]
    public function activity()
    {
        return $this->task->statusHistory()->with('changedBy')->get();
    }

    public function openEditTask(): void
    {
        if (! $this->isReviewer) {
            abort(403);
        }

        $this->editTitle = $this->task->title;
        $this->editDescription = (string) $this->task->description;
        $this->editPriority = $this->task->priority->value;
        $this->editDueDate = $this->task->due_date->format('Y-m-d\TH:i');
        $this->editAssigneeId = $this->task->currentAssignee()?->id;
        $this->reassignReason = '';
        $this->showEditTaskModal = true;
    }

    #[Computed]
    public function reassignableUsers()
    {
        $actor = Auth::user();

        $query = \App\Models\User::where('is_active', true)
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['Employee', 'Team Lead']));

        if (! $actor->hasRole(['Super Admin', 'Manager'])) {
            $query->where('department_id', $actor->department_id);
        }

        return $query->orderBy('name')->get();
    }

    public function saveTaskEdit(): void
    {
        if (! $this->isReviewer) {
            abort(403);
        }

        $currentAssigneeId = $this->task->currentAssignee()?->id;
        $isReassigning = $currentAssigneeId !== $this->editAssigneeId;

        $this->validate([
            'editTitle' => 'required|string|max:191',
            'editDescription' => 'nullable|string',
            'editPriority' => 'required|in:low,medium,high,critical',
            'editDueDate' => 'required|date',
            'editAssigneeId' => 'required|exists:users,id',
            'reassignReason' => $isReassigning ? 'required|string|max:500' : 'nullable|string|max:500',
        ]);

        $this->task->update([
            'title' => $this->editTitle,
            'description' => $this->editDescription,
            'priority' => $this->editPriority,
            'due_date' => $this->editDueDate,
        ]);

        if ($isReassigning) {
            $oldAssignee = $this->task->currentAssignee();

            $this->task->assignments()->where('is_current', true)->update([
                'is_current' => false,
                'unassigned_at' => now(),
            ]);

            $this->task->assignments()->create([
                'assigned_to' => $this->editAssigneeId,
                'assigned_by' => Auth::id(),
                'assigned_at' => now(),
                'is_current' => true,
            ]);

            $newAssignee = \App\Models\User::find($this->editAssigneeId);

            $this->task->statusHistory()->create([
                'old_status' => $this->task->status->value,
                'new_status' => $this->task->status->value, // status itself doesn't change, just the assignee
                'changed_by' => Auth::id(),
                'reason' => "Reassigned from {$oldAssignee?->name} to {$newAssignee->name}: {$this->reassignReason}",
                'changed_at' => now(),
            ]);

            $newAssignee->notify(new \App\Notifications\TaskAssignedNotification($this->task, Auth::user()->name));
        }

        $this->showEditTaskModal = false;
        $this->task->refresh();
        unset($this->activity);
        $this->dispatch('task-updated');
    }

    public function render()
    {
        return view('livewire.task-detail');
    }
}
