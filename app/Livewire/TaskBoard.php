<?php

namespace App\Livewire;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\TaskPlatform;
use App\Models\User;
use App\Services\AddOnTaskService;
use App\Services\TaskStatusService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Illuminate\Support\Facades\Gate;

class TaskBoard extends Component
{
    public string $view = 'kanban'; // kanban | list
    public string $filter = 'all';  // all | mine | high_priority | due_this_week
    public ?int $filterAssignee = null;
    public ?int $filterAssigner = null;
    public ?string $filterDateFrom = null;
    public ?string $filterDateTo = null;
    public string $filterStatus = 'all';

    // Add Task modal state
    public bool $showAddTaskModal = false;
    public string $newTitle = '';
    public string $newDescription = '';
    public string $newPriority = 'medium';
    public string $newDueDate = '';
    public ?int $newAssigneeId = null;
    public bool $isAddOn = false;
    public ?int $interruptedTaskId = null;
    public string $addOnReason = '';
    public ?int $newClientId = null;
    public array $newPlatforms = [];
    public string $deletingReason = '';
    public ?int $deletingTaskId = null;
    public bool $showEscalatedModal = false;
    public ?int $newContentTypeId = null;
    public ?int $filterClientId = null;


    public function mount(): void
    {
        $this->newDueDate = now()->addDays(3)->format('Y-m-d\TH:i');

        if (Auth::user()->hasRole(['Super Admin', 'Manager', 'Team Lead'])) {
            $this->view = 'list';
        }

        // Auto-escalate anything still pending from before today.
        $candidates = Task::visibleTo(Auth::user())
            ->where('is_archived', false)
            ->whereDate('due_date', '<', now()->toDateString())
            ->where('status', TaskStatus::Pending->value)
            ->get();

        app(TaskStatusService::class)->escalateYesterdaysPending($candidates);
    }

    #[Computed]
    public function availableContentTypes()
    {
        if (empty($this->newPlatforms) || ! $this->newClientId) {
            return collect();
        }

        return \App\Models\ClientPlanItem::where('client_id', $this->newClientId)
            ->whereIn('platform', $this->newPlatforms)
            ->with('contentType')
            ->get()
            ->pluck('contentType')
            ->unique('id')
            ->values();
    }

    #[Computed]
    public function statuses(): array
    {
        return TaskStatus::cases();
    }

    #[Computed]
public function tasks()
{
    $query = Task::query()
        ->visibleTo(Auth::user())
        ->with(['currentAssignment.assignee', 'creator', 'comments', 'platforms', 'client'])
        ->where('is_archived', false);

    match ($this->filter) {
        'mine' => $query->whereHas('currentAssignment', fn ($q) => $q->where('assigned_to', Auth::id())),
        'high_priority' => $query->whereIn('priority', ['high', 'critical']),
        'due_today' => $query->whereDate('due_date', now()->toDateString()),
        'due_this_week' => $query->whereBetween('due_date', [now(), now()->addDays(7)]),
        default => null,
    };

    if ($this->filterStatus !== 'all') {
        $query->where('status', $this->filterStatus);
    }

    if ($this->filterClientId) {
        $query->where('client_id', $this->filterClientId);
    }

    if ($this->filterAssignee) {
        $query->whereHas('currentAssignment', fn ($q) => $q->where('assigned_to', $this->filterAssignee));
    }

    if ($this->filterAssigner) {
        $query->where('created_by', $this->filterAssigner);
    }

    if ($this->filterDateFrom) {
        $query->whereDate('due_date', '>=', $this->filterDateFrom);
    }

    if ($this->filterDateTo) {
        $query->whereDate('due_date', '<=', $this->filterDateTo);
    }

    // Active work always sorts before finished work, then by due date within each group.
    $query->orderByRaw("CASE WHEN status IN ('completed','cancelled') THEN 1 ELSE 0 END ASC")
          ->orderBy('due_date');

    // Cap to 30 rows unless a date range is explicitly filtering results.
    if (! $this->filterDateFrom && ! $this->filterDateTo) {
        $query->limit(30);
    }

    return $query->get();
}

    #[Computed]
    public function filterClientOptions()
    {
        return \App\Models\Client::orderBy('name')->get();
    }

    public function clearFilters(): void
    {
        $this->reset(['filterAssignee', 'filterAssigner', 'filterDateFrom', 'filterDateTo', 'filterStatus', 'filterClientId']);
    }

    #[Computed]
    public function assignerOptions()
    {
        $ids = $this->tasks->pluck('created_by')->unique();

        return \App\Models\User::whereIn('id', $ids)->orderBy('name')->get();
    }

    #[Computed]
    public function kanbanColumns(): array
    {
        return [
            'todo' => ['label' => 'New Task', 'statuses' => [TaskStatus::Pending], 'dropTarget' => TaskStatus::Pending],
            'in_progress' => ['label' => 'In Progress', 'statuses' => [TaskStatus::InProgress], 'dropTarget' => TaskStatus::InProgress],
            'on_hold' => ['label' => 'On Hold', 'statuses' => [TaskStatus::OnHold], 'dropTarget' => TaskStatus::OnHold],
            'delayed' => ['label' => 'Delayed', 'statuses' => [TaskStatus::Delayed], 'dropTarget' => TaskStatus::Delayed],
            'pending_approval' => ['label' => 'Pending Approval', 'statuses' => [TaskStatus::PendingReview], 'dropTarget' => TaskStatus::PendingReview],
            'done' => ['label' => 'Completed', 'statuses' => [TaskStatus::Completed, TaskStatus::Cancelled], 'dropTarget' => TaskStatus::Completed],
        ];
    }

    #[Computed]
    public function tasksByColumn(): array
    {
        $grouped = [];
        foreach ($this->kanbanColumns as $key => $col) {
            $grouped[$key] = $this->tasks->whereIn('status', $col['statuses']);
        }

        return $grouped;
    }

    /** Employees this user is allowed to assign tasks to. */
    #[Computed]
    public function assignableUsers()
    {
        $user = Auth::user();

        $query = User::where('is_active', true)
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['Employee']));

        if (! $user->hasRole(['Super Admin', 'Manager', 'Team Lead'])) {
            // Team Lead: own department only
            $query->where('department_id', $user->department_id);
        }

        return $query->orderBy('name')->get();
    }

    /** Currently in-progress/on-hold tasks belonging to the selected assignee — for the add-on linking picker. */
    #[Computed]
    public function candidateInterruptedTasks()
    {
        if (! $this->newAssigneeId) {
            return collect();
        }

        return Task::whereHas('currentAssignment', fn ($q) => $q->where('assigned_to', $this->newAssigneeId))
            ->whereIn('status', [TaskStatus::InProgress->value, TaskStatus::OnHold->value])
            ->get();
    }

    public function openAddTaskModal(): void
    {
        $this->reset(['newTitle', 'newDescription', 'newPriority', 'newAssigneeId', 'isAddOn', 'interruptedTaskId', 'addOnReason', 'newClientId', 'newPlatforms', 'newContentTypeId']);
        $this->newPriority = 'medium';
        $this->newDueDate = now()->addDays(3)->format('Y-m-d\TH:i');
        $this->showAddTaskModal = true;
    }

    public function updatedNewAssigneeId(): void
    {
        $this->interruptedTaskId = null;
        // Nudge the manager: if the assignee already has active work, suggest marking this as an add-on.
        $this->isAddOn = $this->candidateInterruptedTasks->isNotEmpty() ? $this->isAddOn : false;
    }

    public function createTask(): void
    {
        $this->validate([
            'newTitle' => 'required|string|max:191',
            'newDescription' => 'nullable|string',
            'newPriority' => 'required|in:low,medium,high,critical',
            'newDueDate' => 'required|date',
            'newAssigneeId' => 'required|exists:users,id',
            'interruptedTaskId' => 'nullable|required_if:isAddOn,true|exists:tasks,id',
            'addOnReason' => 'nullable|string|max:500',
            'newClientId' => 'nullable|exists:clients,id',
            'newPlatforms' => 'nullable|array',
            'newPlatforms.*' => 'string|max:100',
            'newContentTypeId' => 'nullable|required_with:newPlatforms|exists:content_types,id'
        ]);

        $actor = Auth::user();
        $isExtra = ! empty($this->quotaWarnings);

        DB::transaction(function () use ($actor, $isExtra) {
            $task = Task::create([
                'title' => $this->newTitle,
                'description' => $this->newDescription,
                'created_by' => $actor->id,
                'department_id' => $actor->department_id,
                'priority' => $this->newPriority,
                'status' => TaskStatus::Pending->value,
                'task_type' => $this->isAddOn ? 'add_on' : 'normal',
                'due_date' => $this->newDueDate,
                'client_id' => $this->newClientId,
                'content_type_id' => $this->newContentTypeId,
                'is_extra_delivery' => $isExtra,
            ]);

            $task->assignments()->create([
                'assigned_to' => $this->newAssigneeId,
                'assigned_by' => $actor->id,
                'assigned_at' => now(),
                'is_current' => true,
            ]);

            $assignee = User::find($this->newAssigneeId);
            $assignee->notify(new \App\Notifications\TaskAssignedNotification($task, $actor->name));

            $task->statusHistory()->create([
                'old_status' => null,
                'new_status' => TaskStatus::Pending->value,
                'changed_by' => $actor->id,
                'reason' => 'Task created.',
                'changed_at' => now(),
            ]);

            foreach ($this->newPlatforms as $platform) {
                $task->platforms()->create(['platform' => $platform]);
            }

            if ($this->isAddOn && $this->interruptedTaskId) {
                $interrupted = Task::find($this->interruptedTaskId);
                $employee = User::find($this->newAssigneeId);

                app(AddOnTaskService::class)->linkInterruption(
                    interruptedTask: $interrupted,
                    addOnTask: $task,
                    employee: $employee,
                    createdBy: $actor,
                    reason: $this->addOnReason ?: null,
                );
            }
        });

        $this->showAddTaskModal = false;
        unset($this->tasks, $this->tasksByColumn);
        $this->dispatch('task-created');
    }

    public function availableTransitionsFor(Task $task): \Illuminate\Support\Collection
    {
        $actor = Auth::user();
        $role = $actor->isReviewerFor($task) ? 'reviewer' : 'employee';
        $allowed = $task->status->allowedTransitions();

        $targets = [];
        foreach ($allowed as $statusValue => $roles) {
            if (in_array($role, $roles, true) || $actor->hasRole('Super Admin')) {
                $targets[] = TaskStatus::from($statusValue);
            }
        }

        return collect($targets);
    }

    /**
     * Called from the list view's inline status dropdown (reviewers only:
     * Super Admin, Manager, Team Lead). Reuses the same validated
     * transition path as the Kanban drag and the detail page buttons.
     */
    public function changeStatusFromList(int $taskId, string $targetStatus, ?string $reason = null, ?string $newDueDate = null): void
    {
        $task = Task::findOrFail($taskId);
        $target = TaskStatus::from($targetStatus);

        try {
            app(TaskStatusService::class)->transition($task, $target, Auth::user(), $reason, $newDueDate);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('task-move-rejected', taskId: $taskId, message: $e->getMessage());
        }

        unset($this->tasks, $this->tasksByColumn);
    }

    /**
     * Called from the Kanban drag-drop JS bridge when a card is dropped
     * into a new column. Validates the transition server-side before
     * committing — if invalid, the frontend re-fetches and the card snaps back.
     */
    public function moveTask(int $taskId, string $targetStatus, ?string $reason = null): void
    {
        $task = Task::findOrFail($taskId);
        $target = TaskStatus::from($targetStatus);

        try {
            app(TaskStatusService::class)->transition($task, $target, Auth::user(), $reason);
            $this->dispatch('task-moved', taskId: $taskId, status: $targetStatus);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('task-move-rejected', taskId: $taskId, message: $e->getMessage());
        }

        unset($this->tasks, $this->tasksByColumn);
    }

    #[Computed]
    public function selectedClientPlatforms()
    {
        if (! $this->newClientId) {
            return collect();
        }

        $planItems = \App\Models\Client::find($this->newClientId)?->planItems ?? collect();

        return $planItems->unique('platform')->values();
    }

    #[Computed]
    public function clientOptions()
    {
        return \App\Models\Client::visibleTo(Auth::user())->where('status', 'active')->orderBy('name')->get();
    }

    public function updatedNewClientId(): void
    {
        $this->newPlatforms = [];
        $this->newContentTypeId = null;
    }

    #[Computed]
    public function quotaWarnings(): array
    {
        if (! $this->newClientId || ! $this->newContentTypeId || empty($this->newPlatforms)) {
            return [];
        }

        $client = \App\Models\Client::find($this->newClientId);
        $warnings = [];

        foreach ($this->newPlatforms as $platform) {
            if ($client->wouldExceedQuota($platform, $this->newContentTypeId)) {
                $warnings[] = $platform;
            }
        }

        return $warnings;
    }

    public function confirmDeleteTask(int $taskId): void
    {
        $task = Task::findOrFail($taskId);
        Gate::authorize('delete', $task);

        $this->deletingTaskId = $taskId;
        $this->deletingReason = '';
    }

    public function deleteTask(): void
    {
        $task = Task::findOrFail($this->deletingTaskId);
        Gate::authorize('delete', $task);

        $this->validate(['deletingReason' => 'required|string|max:500']);

        $task->update([
            'deleted_by' => Auth::id(),
            'deletion_reason' => $this->deletingReason,
        ]);
        $task->delete(); // soft delete

        $this->deletingTaskId = null;
        $this->deletingReason = '';
        unset($this->tasks, $this->tasksByColumn);
        $this->dispatch('task-deleted');
    }

    #[Computed]
    public function escalatedTasks()
    {
        return $this->tasks
            ->where('priority_auto_escalated', true)
            ->whereNotIn('status', [TaskStatus::Completed, TaskStatus::Cancelled])
            ->values();
    }

    public function markPendingComplete(int $taskId): void
    {
        if (! Auth::user()->hasRole(['Super Admin', 'Manager', 'Team Lead'])) {
            abort(403);
        }

        $task = Task::findOrFail($taskId);

        if ($task->status !== TaskStatus::Pending) {
            return; // already moved on since the modal opened; nothing to do
        }

        app(TaskStatusService::class)->quickCompleteFromPending($task, Auth::user());

        unset($this->tasks, $this->tasksByColumn);
    }

    public function render()
    {
        return view('livewire.task-board');
    }
}
