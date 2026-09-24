<?php

namespace App\Livewire;

use App\Models\Task;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class AllTasksLog extends Component
{
    public string $filter = 'all'; // all | active | deleted

    #[Computed]
    public function tasks()
    {
        $query = Task::withTrashed()
            ->visibleTo(Auth::user())
            ->with(['currentAssignment.assignee', 'creator', 'deletedBy', 'client']);

        match ($this->filter) {
            'active' => $query->whereNull('deleted_at'),
            'deleted' => $query->whereNotNull('deleted_at'),
            default => null,
        };

        return $query->orderByDesc('created_at')->get();
    }

    public function restoreTask(int $taskId): void
    {
        $task = Task::withTrashed()->findOrFail($taskId);
        \Illuminate\Support\Facades\Gate::authorize('delete', $task); // same authority that can delete can restore

        $task->update(['deleted_by' => null, 'deletion_reason' => null]);
        $task->restore();

        unset($this->tasks);
        $this->dispatch('task-restored');
    }

    public function render()
    {
        return view('livewire.all-tasks-log');
    }
}
