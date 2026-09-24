<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use SoftDeletes;
    use HasRoles;

    protected $fillable = [
        'employee_code', 'name', 'email', 'phone', 'department_id',
        'manager_id', 'is_active', 'password', 'work_log_enabled', 'can_add_work_log_clients', 'auto_approve_tasks',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'is_active' => 'boolean',
        'password' => 'hashed',
        'work_log_enabled' => 'boolean',
        'can_add_work_log_clients' => 'boolean',
        'auto_approve_tasks' => 'boolean',
        'can_add_gmb_clients' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function manager(): BelongsTo
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function directReports(): HasMany
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    public function createdTasks(): HasMany
    {
        return $this->hasMany(Task::class, 'created_by');
    }

    public function taskAssignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class, 'assigned_to');
    }

    /** Determines whether this user may act as a 'reviewer' (approve delays, sign off completion, etc.) for a given task. */
    public function isReviewerFor(Task $task): bool
    {
        if ($this->hasRole(['Super Admin', 'Manager'])) {
            return true;
        }

        if ($this->hasRole('Team Lead')) {
            return $this->department_id === $task->department_id;
        }


        return false;
    }

    /** Role-based color classes for consistent hierarchical coloring across the UI (e.g. Activity feed, avatars). */
    public function roleColorClasses(): array
    {
        return match (true) {
            $this->hasRole('Super Admin') => ['bg' => 'bg-violet-100 dark:bg-violet-500/15', 'text' => 'text-violet-700 dark:text-violet-300'],
            $this->hasRole('Manager') => ['bg' => 'bg-blue-100 dark:bg-blue-500/15', 'text' => 'text-blue-700 dark:text-blue-300'],
            $this->hasRole('Team Lead') => ['bg' => 'bg-teal-100 dark:bg-teal-500/15', 'text' => 'text-teal-700 dark:text-teal-300'],
            default => ['bg' => 'bg-slate-100 dark:bg-slate-500/15', 'text' => 'text-slate-700 dark:text-slate-300'],
        };
    }

    public function canAccessWorkLog(): bool
    {
        return $this->hasRole(['Super Admin', 'Manager', 'Employee']) || $this->work_log_enabled;
    }

    public function workLogs()
    {
        return $this->hasMany(WorkLog::class);
    }

    /** True if any of this user's roles is allowed to see this module in the menu. */
    public function canSeeModule(string $moduleKey): bool
    {
        $roleNames = $this->roles->pluck('name');

        if ($roleNames->isEmpty()) {
            return false;
        }

        $permissions = \App\Models\MenuPermission::where('module_key', $moduleKey)
            ->whereIn('role_name', $roleNames)
            ->get();

        foreach ($roleNames as $role) {
            // Department-specific override takes precedence over the global default.
            $override = $permissions->first(
                fn ($p) => $p->role_name === $role && $p->department_id === $this->department_id
            );

            if ($override) {
                if ($override->is_visible) {
                    return true;
                }
                continue;
            }

            $default = $permissions->first(
                fn ($p) => $p->role_name === $role && is_null($p->department_id)
            );

            if ($default?->is_visible) {
                return true;
            }
        }

        return false;
    }
}
