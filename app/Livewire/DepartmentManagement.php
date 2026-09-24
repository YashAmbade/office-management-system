<?php

namespace App\Livewire;

use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class DepartmentManagement extends Component
{
    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $code = '';
    public ?int $parentDepartmentId = null;
    public bool $isActive = true;

    private function ensureSuperAdmin(): void
    {
        if (! Auth::user()->hasRole('Super Admin')) {
            abort(403);
        }
    }

    #[Computed]
    public function departments()
    {
        return Department::with('parent')->withCount('users')->orderBy('name')->get();
    }

    #[Computed]
    public function parentOptions()
    {
        return Department::when(
            $this->editingId,
            fn ($q) => $q->where('id', '!=', $this->editingId)
        )->orderBy('name')->get();
    }

    public function openCreate(): void
    {
        $this->ensureSuperAdmin();
        $this->reset(['editingId', 'name', 'code', 'parentDepartmentId']);
        $this->isActive = true;
        $this->showModal = true;
    }

    public function openEdit(int $departmentId): void
    {
        $this->ensureSuperAdmin();
        $dept = Department::findOrFail($departmentId);

        $this->editingId = $dept->id;
        $this->name = $dept->name;
        $this->code = $dept->code;
        $this->parentDepartmentId = $dept->parent_department_id;
        $this->isActive = $dept->is_active;
        $this->showModal = true;
    }

    public function cancel(): void
    {
        $this->showModal = false;
    }

    public function save(): void
    {
        $this->ensureSuperAdmin();

        $this->validate([
            'name' => 'required|string|max:191',
            'code' => ['required', 'string', 'max:20', Rule::unique('departments', 'code')->ignore($this->editingId)],
            'parentDepartmentId' => 'nullable|exists:departments,id',
            'isActive' => 'boolean',
        ]);

        Department::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'code' => strtoupper($this->code),
                'parent_department_id' => $this->parentDepartmentId,
                'is_active' => $this->isActive,
            ]
        );

        $this->showModal = false;
        unset($this->departments, $this->parentOptions);
        $this->dispatch('department-saved');
    }

    public function render()
    {
        return view('livewire.department-management');
    }
}
