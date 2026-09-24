<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Gate;

class TaskController extends Controller
{
    /**
     * Task list/board page. All data fetching, filtering, and view-switching
     * lives in the TaskBoard Livewire component — this just authorizes and
     * renders the page shell.
     */
    public function index(): View
    {
        Gate::authorize('viewAny', Task::class);

        return view('tasks.index');
    }

    /**
     * Single task detail page. Route-model-bound; authorization ensures
     * an Employee can't view a task outside their own assignments, and a
     * Manager/Team Lead can't view tasks outside their department.
     */
    public function show(Task $task): View
    {
        Gate::authorize('view', $task);

        return view('tasks.show', ['task' => $task]);
    }

    public function allLog(): View
    {
        \Illuminate\Support\Facades\Gate::authorize('viewAny', \App\Models\Task::class);

        if (! auth()->user()->hasRole(['Super Admin', 'Manager', 'Team Lead'])) {
            abort(403);
        }

        return view('tasks.all-log');
    }
}
