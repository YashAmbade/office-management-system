<?php

namespace App\Policies;

use App\Models\User;

class UserPolicy
{
    /** Can $actor see the User Management screen at all? */
    public function viewAny(User $actor): bool
    {
        return $actor->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }

    /** Can $actor view/edit this specific $target user's profile? */
    public function manage(User $actor, User $target): bool
    {
        if ($actor->hasRole('Super Admin')) {
            return true;
        }

        if ($actor->hasRole(['Manager'])) {
            return $target->hasRole(['Employee', 'Team Lead']);
        }

        return false;
    }

    /** Which roles is $actor allowed to assign to a target user? */
    public function assignableRoles(User $actor): array
    {
        if ($actor->hasRole('Super Admin')) {
            return \Spatie\Permission\Models\Role::pluck('name')->toArray();
        }

        if ($actor->hasRole(['Manager', 'Team Lead'])) {
            return ['Team Lead', 'Employee'];
        }

        return [];
    }
}
