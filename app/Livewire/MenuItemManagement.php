<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\MenuItem;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\MenuPermission;

class MenuItemManagement extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $key = '';
    public string $label = '';
    public string $icon = '';
    public ?string $routeName = null;
    public ?string $section = null;
    public ?int $parentId = null;
    public ?int $restrictedDepartmentId = null;
    public bool $isActive = true;
    public array $roleVisibility = [];

    public function mount(): void
    {
        Gate::authorize('manage', \App\Models\MenuPermission::class);
    }

    #[Computed]
    public function topLevelItems()
    {
        return MenuItem::whereNull('parent_id')
            ->with('children')
            ->orderBy('sort_order')
            ->get();
    }

    #[Computed]
    public function parentOptions()
    {
        // Only top-level items can be a parent — no 3+ level nesting for now.
        return MenuItem::whereNull('parent_id')->orderBy('label')->get();
    }

    #[Computed]
    public function departments()
    {
        return Department::orderBy('name')->get();
    }

    public function openCreate(?int $parentId = null): void
    {
        $this->reset([
            'editingId',
            'key',
            'label',
            'icon',
            'routeName',
            'section',
            'parentId',
            'restrictedDepartmentId',
        ]);
        $this->isActive = true;
        $this->parentId = $parentId;
        $this->roleVisibility = collect(MenuPermission::roles())->mapWithKeys(fn($r) => [$r => true])->toArray();
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        $item = MenuItem::findOrFail($id);

        $this->editingId = $item->id;
        $this->key = $item->key;
        $this->label = $item->label;
        $this->icon = (string) $item->icon;
        $this->routeName = $item->route_name;
        $this->section = $item->section;
        $this->parentId = $item->parent_id;
        $this->restrictedDepartmentId = $item->restricted_department_id;
        $this->isActive = $item->is_active;

        // Pull current GLOBAL (department_id = null) role visibility for this item.
        $this->roleVisibility = collect(MenuPermission::roles())->mapWithKeys(function ($role) use ($item) {
            $value = MenuPermission::where('module_key', $item->key)
                ->where('role_name', $role)
                ->whereNull('department_id')
                ->value('is_visible');
            return [$role => $value === null ? true : (bool) $value];
        })->toArray();

        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate([
            'key' => 'required|string|max:100|regex:/^[a-z0-9_]+$/|unique:menu_items,key,' . $this->editingId,
            'label' => 'required|string|max:191',
            'icon' => 'nullable|string|max:100',
            'routeName' => 'nullable|string|max:191',
            'section' => 'nullable|string|max:100',
            'parentId' => 'nullable|exists:menu_items,id',
            'restrictedDepartmentId' => 'nullable|exists:departments,id',
        ], [
            'key.regex' => 'Key must be lowercase letters, numbers, and underscores only (e.g. gmb_checklist).',
        ]);

        $maxOrder = MenuItem::where('parent_id', $this->parentId)->max('sort_order') ?? 0;

        $item = MenuItem::updateOrCreate(
            ['id' => $this->editingId],
            [
                'key' => $this->key,
                'label' => $this->label,
                'icon' => $this->icon ?: null,
                'route_name' => $this->routeName ?: null,
                'section' => $this->parentId ? null : $this->section,
                'parent_id' => $this->parentId,
                'restricted_department_id' => $this->restrictedDepartmentId,
                'is_active' => $this->isActive,
                'sort_order' => $this->editingId ? MenuItem::find($this->editingId)->sort_order : $maxOrder + 1,
            ]
        );

        // Write role visibility into the SAME table the Permissions page reads/writes.
        foreach ($this->roleVisibility as $role => $visible) {
            MenuPermission::updateOrCreate(
                ['module_key' => $item->key, 'role_name' => $role, 'department_id' => null],
                ['is_visible' => $visible]
            );
        }

        $this->showModal = false;
        unset($this->topLevelItems, $this->parentOptions);
        $this->dispatch('menu-item-saved');
    }

    public function delete(int $id): void
    {
        MenuItem::findOrFail($id)->delete(); // cascades to children via FK
        unset($this->topLevelItems, $this->parentOptions);
        $this->dispatch('menu-item-deleted');
    }

    public function moveItem(int $id, string $direction): void
    {
        $item = MenuItem::findOrFail($id);
        $siblings = MenuItem::where('parent_id', $item->parent_id)->orderBy('sort_order')->get();
        $index = $siblings->search(fn($i) => $i->id === $id);
        $swapWith = $direction === 'up' ? $index - 1 : $index + 1;

        if ($swapWith < 0 || $swapWith >= $siblings->count()) {
            return;
        }

        $a = $siblings[$index];
        $b = $siblings[$swapWith];
        [$a->sort_order, $b->sort_order] = [$b->sort_order, $a->sort_order];
        $a->save();
        $b->save();

        unset($this->topLevelItems);
    }

    public function toggleActive(int $id): void
    {
        $item = MenuItem::findOrFail($id);
        $item->update(['is_active' => ! $item->is_active]);
        unset($this->topLevelItems);
    }

    public function render()
    {
        return view('livewire.menu-item-management');
    }
}
