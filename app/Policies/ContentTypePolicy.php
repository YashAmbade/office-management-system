<?php

namespace App\Policies;

use App\Models\AppSetting;
use App\Models\User;

class ContentTypePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager', 'Team Lead']);
    }

    public function create(User $user): bool
    {
        if ($user->hasRole(['Super Admin', 'Manager'])) {
            return true;
        }

        if ($user->hasRole('Team Lead')) {
            return AppSetting::get('tl_can_create_content_types', '0') === '1';
        }

        return false;
    }

    /** Only Super Admin/Manager can flip the TL permission toggle itself. */
    public function toggleTeamLeadAccess(User $user): bool
    {
        return $user->hasRole(['Super Admin', 'Manager']);
    }
}
