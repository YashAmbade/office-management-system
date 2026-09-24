<?php

namespace App\Policies;

use App\Models\AppSetting;
use App\Models\User;

class GmbClientPolicy
{
    public function toggleGmbChecklist(User $user): bool
    {
        // Everyone in the GMB department (or above) can tick boxes.
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead', 'Employee'])
            && ($user->department?->code === 'GMB' || $user->hasRole(['Super Admin', 'Manager']));
    }

    public function manageCategories(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }

    public function completeGmbMonth(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }

    public function addClient(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']) || $user->can_add_gmb_clients;
    }
    
    public function deleteClient(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }
}
