<?php

namespace App\Livewire;

use App\Enums\TaskStatus;
use App\Models\Client;
use App\Models\Task;
use App\Models\TaskAssignment;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class Dashboard extends Component
{
    #[Computed]
    public function isReviewer(): bool
    {
        return Auth::user()->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }

    /** Manager & Super Admin get the analytics dashboard instead of the personal one. */
    #[Computed]
    public function isManagerView(): bool
    {
        return Auth::user()->hasRole(['Super Admin', 'Manager']);
    }

    #[Computed]
    public function myTasksCount(): int
    {
        return Task::whereHas('currentAssignment', fn ($q) => $q->where('assigned_to', Auth::id()))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
    }

    #[Computed]
    public function overdueCount(): int
    {
        return Task::visibleTo(Auth::user())
            ->where('due_date', '<', now())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->count();
    }

    #[Computed]
    public function pendingApprovalCount(): int
    {
        return Task::visibleTo(Auth::user())->where('status', TaskStatus::PendingReview->value)->count();
    }

    #[Computed]
    public function completedThisWeekCount(): int
    {
        return Task::visibleTo(Auth::user())
            ->where('status', 'completed')
            ->whereBetween('completed_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
    }

    #[Computed]
    public function activeClientsCount(): int
    {
        return Client::where('status', 'active')->count();
    }

    #[Computed]
    public function priorityClientsCount(): int
    {
        return Client::where('is_priority', true)->where('status', '!=', 'inactive')->count();
    }

    #[Computed]
    public function myUpcomingTasks()
    {
        return Task::whereHas('currentAssignment', fn ($q) => $q->where('assigned_to', Auth::id()))
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->with('client')
            ->orderBy('due_date')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function pendingApprovalTasks()
    {
        return Task::visibleTo(Auth::user())
            ->where('status', TaskStatus::PendingReview->value)
            ->with(['currentAssignment.assignee'])
            ->orderBy('due_date')
            ->limit(6)
            ->get();
    }

    #[Computed]
    public function recentActivity()
    {
        return \App\Models\TaskStatusHistory::whereHas('task', fn ($q) => $q->visibleTo(Auth::user()))
            ->with(['task', 'changedBy'])
            ->orderByDesc('changed_at')
            ->limit(8)
            ->get();
    }

    #[Computed]
    public function expiringClientsCount(): int
    {
        if (! auth()->user()->hasRole(['Super Admin', 'Manager'])) {
            return 0;
        }

        return \App\Models\Client::where('status', '!=', 'inactive')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now()->startOfDay(), now()->addDays(5)->endOfDay()])
            ->count();
    }

    // ─────────────────────────────────────────────────────────────
    // Manager analytics (Team Leads + Employees performance)
    // ─────────────────────────────────────────────────────────────

    /** Per-user stats: assigned/completed in the last 30 days, current active/overdue load. */
    #[Computed]
    public function teamStats()
    {
        $since = now()->subDays(30);

        $userIds = User::whereHas('roles', fn ($q) => $q->whereIn('name', ['Team Lead', 'Employee']))
            ->where('is_active', true)
            ->pluck('id');

        // One query: assigned counts per user, grouped.
        $assignedCounts = TaskAssignment::whereIn('assigned_to', $userIds)
            ->where('created_at', '>=', $since)
            ->selectRaw('assigned_to, count(*) as total')
            ->groupBy('assigned_to')
            ->pluck('total', 'assigned_to');

        // One query: completed counts per user, grouped (via currentAssignment join).
        $completedCounts = Task::join('task_assignments', 'task_assignments.task_id', '=', 'tasks.id')
            ->where('task_assignments.is_current', true)
            ->whereIn('task_assignments.assigned_to', $userIds)
            ->where('tasks.status', 'completed')
            ->whereBetween('tasks.completed_at', [$since, now()])
            ->selectRaw('task_assignments.assigned_to, count(*) as total')
            ->groupBy('task_assignments.assigned_to')
            ->pluck('total', 'task_assignments.assigned_to');

        // One query: active-now counts per user, grouped.
        $activeCounts = Task::join('task_assignments', 'task_assignments.task_id', '=', 'tasks.id')
            ->where('task_assignments.is_current', true)
            ->whereIn('task_assignments.assigned_to', $userIds)
            ->whereNotIn('tasks.status', ['completed', 'cancelled'])
            ->selectRaw('task_assignments.assigned_to, count(*) as total')
            ->groupBy('task_assignments.assigned_to')
            ->pluck('total', 'task_assignments.assigned_to');

        // One query: overdue-now counts per user, grouped.
        $overdueCounts = Task::join('task_assignments', 'task_assignments.task_id', '=', 'tasks.id')
            ->where('task_assignments.is_current', true)
            ->whereIn('task_assignments.assigned_to', $userIds)
            ->where('tasks.due_date', '<', now())
            ->whereNotIn('tasks.status', ['completed', 'cancelled'])
            ->selectRaw('task_assignments.assigned_to, count(*) as total')
            ->groupBy('task_assignments.assigned_to')
            ->pluck('total', 'task_assignments.assigned_to');

        return User::whereHas('roles', fn ($q) => $q->whereIn('name', ['Team Lead', 'Employee']))
            ->where('is_active', true)
            ->with(['roles', 'department'])
            ->get()
            ->map(function ($user) use ($assignedCounts, $completedCounts, $activeCounts, $overdueCounts) {
                $assigned30 = $assignedCounts[$user->id] ?? 0;
                $completed30 = $completedCounts[$user->id] ?? 0;

                return (object) [
                    'user' => $user,
                    'role' => $user->roles->first()?->name ?? '-',
                    'assigned_30d' => $assigned30,
                    'completed_30d' => $completed30,
                    'active_now' => $activeCounts[$user->id] ?? 0,
                    'overdue_now' => $overdueCounts[$user->id] ?? 0,
                    'completion_rate' => $assigned30 > 0 ? round(($completed30 / $assigned30) * 100) : null,
                ];
            })
            ->sortByDesc('active_now')
            ->values();
    }

    /** Org-wide totals for the summary cards. */
    #[Computed]
    public function orgStats(): array
    {
        $since = now()->subDays(30);

        return [
            'assigned_30d' => TaskAssignment::where('created_at', '>=', $since)->count(),
            'completed_30d' => Task::where('status', 'completed')
                ->whereBetween('completed_at', [$since, now()])
                ->count(),
            'overdue_now' => Task::whereNotIn('status', ['completed', 'cancelled'])
                ->where('due_date', '<', now())
                ->count(),
            'pending_review' => Task::where('status', TaskStatus::PendingReview->value)->count(),
        ];
    }

    /** Same shape as teamStats but grouped by department, for a quick department comparison. */
    #[Computed]
    public function departmentStats()
    {
        return $this->teamStats
            ->groupBy(fn ($row) => $row->user->department?->name ?? 'Unassigned')
            ->map(function ($rows, $deptName) {
                return (object) [
                    'name' => $deptName,
                    'assigned_30d' => $rows->sum('assigned_30d'),
                    'completed_30d' => $rows->sum('completed_30d'),
                    'active_now' => $rows->sum('active_now'),
                    'overdue_now' => $rows->sum('overdue_now'),
                    'headcount' => $rows->count(),
                ];
            })
            ->sortByDesc('active_now')
            ->values();
    }

    public function render()
    {
        return $this->isManagerView
            ? view('livewire.dashboard-manager')
            : view('livewire.dashboard');
    }
}
