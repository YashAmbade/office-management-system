<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuPermission extends Model
{
    protected $fillable = ['module_key', 'role_name', 'department_id', 'is_visible'];
    protected $casts = ['is_visible' => 'boolean'];

    public function department(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public static function roles(): array
    {
        // Order matters: highest privilege first — used to pick a user's "effective" role for lookup.
        return ['Super Admin', 'Manager', 'Team Lead', 'Employee'];
    }

    private static function effectiveRoleFor(User $user): string
    {
        foreach (self::roles() as $role) {
            if ($user->hasRole($role)) {
                return $role;
            }
        }
        return 'Employee';
    }

    /** Should this user see this menu item, accounting for department restriction + role/department overrides? */
    protected static ?\Illuminate\Support\Collection $permissionCache = null;
    protected static ?int $cachedForDepartment = -1; // -1 = not loaded yet, distinguishes from null (global)

    public static function isVisibleFor(User $user, MenuItem $item): bool
    {
        // Hard department gate — unchanged.
        if ($item->restricted_department_id && ! $user->hasRole(['Super Admin', 'Manager'])) {
            if ($user->department_id !== $item->restricted_department_id) {
                return false;
            }
        }

        $role = self::effectiveRoleFor($user);
        $permissions = self::loadPermissionsFor($user->department_id);

        // Department-specific override wins if it exists.
        $override = $permissions->get($item->key . '|' . $role . '|dept');
        if (! is_null($override)) {
            return (bool) $override;
        }

        // Fall back to the global default for this role.
        $default = $permissions->get($item->key . '|' . $role . '|global');
        return is_null($default) ? true : (bool) $default;
    }

    private static function loadPermissionsFor(?int $departmentId): \Illuminate\Support\Collection
    {
        // Already loaded for this exact department this request — reuse it.
        if (self::$permissionCache !== null && self::$cachedForDepartment === $departmentId) {
            return self::$permissionCache;
        }

        $rows = static::where(function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId)
                  ->orWhereNull('department_id');
            })
            ->get();

        self::$permissionCache = $rows->mapWithKeys(function ($row) {
            $scope = is_null($row->department_id) ? 'global' : 'dept';
            return ["{$row->module_key}|{$row->role_name}|{$scope}" => $row->is_visible];
        });

        self::$cachedForDepartment = $departmentId;

        return self::$permissionCache;
    }
}
