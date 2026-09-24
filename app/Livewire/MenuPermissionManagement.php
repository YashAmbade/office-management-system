<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\MenuPermission;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\MenuItem;

class MenuPermissionManagement extends Component
{
    /** null = editing global defaults; otherwise the department id being overridden. */
    public ?int $departmentId = null;

    public function mount(): void
    {
        Gate::authorize('manage', MenuPermission::class);
    }

    #[Computed]
    public function departments()
    {
        return Department::orderBy('name')->get();
    }

    #[Computed]
    public function grid()
    {
        $items = MenuItem::whereNull('parent_id')->with('children')->orderBy('sort_order')->get();

        $scopedRows = MenuPermission::where('department_id', $this->departmentId)->get()->groupBy('module_key');
        $defaultRows = $this->departmentId === null ? null : MenuPermission::whereNull('department_id')->get()->groupBy('module_key');

        $resolveRoles = function ($item) use ($scopedRows, $defaultRows) {
            $roles = [];
            foreach (MenuPermission::roles() as $role) {
                $entry = $scopedRows->get($item->key, collect())->firstWhere('role_name', $role);
                if ($entry) {
                    $roles[$role] = ['value' => $entry->is_visible, 'overridden' => true];
                    continue;
                }
                $fallback = $this->departmentId === null ? true : ($defaultRows->get($item->key, collect())->firstWhere('role_name', $role)?->is_visible ?? true);
                $roles[$role] = ['value' => $fallback, 'overridden' => false];
            }
            return $roles;
        };

        $result = [];
        foreach ($items as $item) {
            $result[] = ['item' => $item, 'roles' => $resolveRoles($item), 'children' => $item->children->map(fn ($c) => ['item' => $c, 'roles' => $resolveRoles($c)])];
        }

        return $result;
    }

    public function selectScope(?int $departmentId): void
    {
        $this->departmentId = $departmentId;
    }

    public function toggle(string $moduleKey, string $role): void
    {
        Gate::authorize('manage', MenuPermission::class);

        // Prevent locking Super Admin out of the permissions page or Dashboard entirely — applies in every scope.
        if ($role === 'Super Admin' && in_array($moduleKey, ['dashboard', 'settings'], true)) {
            return;
        }

        $startingValue = $this->currentEffectiveValue($moduleKey, $role);

        $current = MenuPermission::firstOrCreate(
            ['module_key' => $moduleKey, 'role_name' => $role, 'department_id' => $this->departmentId],
            ['is_visible' => $startingValue]
        );

        $current->update(['is_visible' => ! $current->is_visible]);
        unset($this->grid);
    }

    /** Remove a department-level override so that module/role falls back to the global default again. */
    public function resetOverride(string $moduleKey, string $role): void
    {
        Gate::authorize('manage', MenuPermission::class);

        if ($this->departmentId === null) {
            return; // nothing to reset — this already is the default
        }

        MenuPermission::where([
            'module_key' => $moduleKey,
            'role_name' => $role,
            'department_id' => $this->departmentId,
        ])->delete();

        unset($this->grid);
    }

    private function currentEffectiveValue(string $moduleKey, string $role): bool
    {
        if ($this->departmentId === null) {
            return true;
        }

        return MenuPermission::where('module_key', $moduleKey)
            ->where('role_name', $role)
            ->whereNull('department_id')
            ->value('is_visible') ?? true;
    }

    public function render()
    {
        return view('livewire.menu-permission-management');
    }
}
