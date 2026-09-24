<?php

namespace App\Policies;

use App\Models\Client;
use App\Models\User;

class ClientPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead', 'Employee']);
    }

    public function manage(User $user, Client $client): bool
    {
        if ($user->hasRole(['Super Admin', 'Manager', 'Team Lead'])) {
            return true;
        }

        if ($user->hasRole('Team Lead')) {
            return $user->department_id === $client->department_id;
        }

        return false;
    }

    public function create(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }

    public function manageServices(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager']);
    }

    public function generateTasks(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }
}
