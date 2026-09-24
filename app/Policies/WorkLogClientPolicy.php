<?php

namespace App\Policies;

use App\Models\User;
use App\Models\WorkLogClient;

class WorkLogClientPolicy
{
     public function manage(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Employee']);
    }

    /** Can click "Add Client" - Super Admin/Manager always; Employee only if individually approved. */
    public function create(User $user): bool
    {
        if ($user->hasRole(['Super Admin', 'Manager'])) {
            return true;
        }

        return $user->hasRole('Employee') && $user->can_add_work_log_clients;
    }
}
