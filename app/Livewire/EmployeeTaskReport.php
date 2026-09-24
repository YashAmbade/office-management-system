<?php

namespace App\Livewire;

use App\Models\Task;
use App\Models\TaskStatusHistory;
use App\Models\User;
use Illuminate\Support\Facades\Gate;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Carbon\Carbon;


class EmployeeTaskReport extends Component
{
    public ?int $selectedEmployeeId = null;
    public string $statusFilter = 'all'; // drives BOTH which statuses show AND which date column is used

    public string $dateMode = 'single'; // 'single' or 'range'
    public ?string $selectedDate = null;
    public ?string $fromDate = null;
    public ?string $toDate = null;

    public ?int $expandedTaskId = null;

    public function mount(): void
    {
        Gate::authorize('viewAny', User::class);
        $this->selectedDate = now()->toDateString();
    }

    /** Convert a single IST calendar date into its UTC start/end boundaries for querying. */
    private function istDayToUtcRange(string $date): array
    {
        $start = Carbon::parse($date, 'Asia/Kolkata')->startOfDay()->setTimezone('UTC');
        $end = Carbon::parse($date, 'Asia/Kolkata')->endOfDay()->setTimezone('UTC');
    
        return [$start, $end];
    }
    
    /** Convert an IST from/to range into UTC boundaries for querying. */
    private function istRangeToUtcRange(?string $from, ?string $to): array
    {
        $start = $from ? Carbon::parse($from, 'Asia/Kolkata')->startOfDay()->setTimezone('UTC') : null;
        $end = $to ? Carbon::parse($to, 'Asia/Kolkata')->endOfDay()->setTimezone('UTC') : null;
    
        return [$start, $end];
    }
        
        #[Computed]
    public function employees()
    {
        return User::where('department_id', 4) // Social Media
            ->where('is_active', 1)
            ->whereHas('roles', fn ($q) => $q->where('name', 'Employee'))
            ->withCount(['taskAssignments as tasks_count' => fn ($q) => $q->where('is_current', 1)])
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function selectedEmployee()
    {
        return $this->selectedEmployeeId ? User::find($this->selectedEmployeeId) : null;
    }

    /** Task IDs where this employee moved the task to pending_review or completed within the date filter. */
    private function employeeCompletedTaskIds()
    {
        return TaskStatusHistory::query()
            ->whereIn('new_status', ['pending_review', 'completed'])
            ->where('changed_by', $this->selectedEmployeeId)
            ->when($this->dateMode === 'single' && $this->selectedDate, function ($q) {
                [$start, $end] = $this->istDayToUtcRange($this->selectedDate);
                $q->whereBetween('changed_at', [$start, $end]);
            })
            ->when($this->dateMode === 'range' && ($this->fromDate || $this->toDate), function ($q) {
                [$start, $end] = $this->istRangeToUtcRange($this->fromDate, $this->toDate);
                if ($start) $q->where('changed_at', '>=', $start);
                if ($end) $q->where('changed_at', '<=', $end);
            })
            ->pluck('task_id')
            ->unique();
    }
    
        /** True once any date filter is actually active. */
        private function hasDateFilter(): bool
        {
            return ($this->dateMode === 'single' && $this->selectedDate)
                || ($this->dateMode === 'range' && ($this->fromDate || $this->toDate));
        }
    
        #[Computed]
    public function tasks()
    {
        if (! $this->selectedEmployeeId) {
            return collect();
        }
    
        $isCompletionStatus = in_array($this->statusFilter, ['pending_review', 'completed'], true);
    
        $query = Task::with(['platforms', 'statusHistory', 'client', 'activeAssignment'])
            ->whereHas('activeAssignment', fn ($q) => $q->where('assigned_to', $this->selectedEmployeeId));
    
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
    
        if ($this->hasDateFilter()) {
            if ($isCompletionStatus) {
                // Pending Review / Completed: date checks WHEN the employee submitted/finished it.
                $query->whereIn('id', $this->employeeCompletedTaskIds());
            } else {
                // Any / other statuses: date checks WHEN it was assigned.
                $query->whereHas('activeAssignment', function ($q) {
                    if ($this->dateMode === 'single' && $this->selectedDate) {
                        [$start, $end] = $this->istDayToUtcRange($this->selectedDate);
                        $q->whereBetween('assigned_at', [$start, $end]);
                    }
                    if ($this->dateMode === 'range' && ($this->fromDate || $this->toDate)) {
                        [$start, $end] = $this->istRangeToUtcRange($this->fromDate, $this->toDate);
                        if ($start) $q->where('assigned_at', '>=', $start);
                        if ($end) $q->where('assigned_at', '<=', $end);
                    }
                });
            }
        }
    
        return $query->get()
            ->sortByDesc(fn ($task) => $isCompletionStatus
                ? ($task->completed_at ?? $task->updated_at)
                : $task->activeAssignment?->assigned_at)
            ->values();
    }
    
    #[Computed]
    public function dayStats()
    {
        if (! $this->selectedEmployeeId || ! $this->hasDateFilter()) {
            return null;
        }
    
        $tasks = $this->tasks;
    
        $total = $tasks->count();
        $pending = $tasks->filter(fn ($t) => $t->status->value === 'pending')->count();
        $pendingReview = $tasks->filter(fn ($t) => $t->status->value === 'pending_review')->count();
        $approved = $tasks->filter(fn ($t) => $t->status->value === 'completed')->count();
    
        $completedOnTime = $tasks->filter(function ($task) {
            return $task->status->value === 'completed'
                && $task->completed_at
                && $task->completed_at->lte($task->due_date);
        })->count();
    
        $completedLate = $approved - $completedOnTime;
    
        $stillPending = $tasks->filter(fn ($t) => ! in_array($t->status->value, ['completed', 'cancelled']))->count();
        $cancelled = $tasks->filter(fn ($t) => $t->status->value === 'cancelled')->count();
    
        return [
            'total' => $total,
            'pending' => $pending,
            'pending_review' => $pendingReview,
            'approved' => $approved,
            'completed_on_time' => $completedOnTime,
            'completed_late' => $completedLate,
            'still_pending' => $stillPending,
            'cancelled' => $cancelled,
            'completion_rate' => $total > 0 ? round((($pendingReview + $approved) / $total) * 100) : 0,
        ];
    }

    public function selectEmployee(int $userId): void
    {
        $this->selectedEmployeeId = $userId;
        $this->expandedTaskId = null;
    }

    public function setDateMode(string $mode): void
    {
        $this->dateMode = $mode;
    
        if ($mode === 'range' && ! $this->fromDate && ! $this->toDate) {
            $this->fromDate = now('Asia/Kolkata')->startOfMonth()->toDateString();
            $this->toDate = now('Asia/Kolkata')->toDateString();
        }
    }

    public function clearDateFilter(): void
    {
        $this->selectedDate = null;
        $this->fromDate = null;
        $this->toDate = null;
    }

    public function toggleHistory(int $taskId): void
    {
        $this->expandedTaskId = $this->expandedTaskId === $taskId ? null : $taskId;
    }

    public function render()
    {
        return view('livewire.employee-task-report');
    }
}