<?php

namespace App\Policies;

use App\Models\Task;
use App\Models\User;

class TaskPolicy
{
    public function viewAny(User $user): bool
    {
        return true; // scoped via Task::visibleTo() in the query itself
    }

    public function view(User $user, Task $task): bool
    {
        if ($user->hasRole(['Super Admin', 'Manager', 'Team Lead'])) {
            return true;
        }


        return $task->assignments()->where('assigned_to', $user->id)->exists();
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }

    public function updateStatus(User $user, Task $task): bool
    {
        return $this->view($user, $task);
    }

    public function approveDelay(User $user, Task $task): bool
    {
        return $user->hasRole(['Super Admin', 'Manager'])
            || ($user->hasRole('Team Lead') && $user->department_id === $task->department_id);
    }

    public function viewAssigneeFilters(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }

    public function delete(User $user, Task $task): bool
    {
        if ($user->hasRole(['Super Admin', 'Manager'])) {
            return true;
        }

        if ($user->hasRole('Team Lead')) {
            return $user->department_id === $task->department_id;
        }

        return false;
    }
}
