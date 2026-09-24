<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Computed;
use Livewire\Component;

class UserManagement extends Component
{
    public ?int $editingUserId = null;

    // Edit form fields
    public string $editName = '';
    public string $editEmail = '';
    public ?string $editPhone = null;
    public ?int $editDepartmentId = null;
    public string $editRole = '';
    public bool $editIsActive = true;
    public string $editNewPassword = '';

    public bool $showCreateModal = false;

    // Create form fields
    public string $createEmployeeCode = '';
    public string $createName = '';
    public string $createEmail = '';
    public ?string $createPhone = null;
    public ?int $createDepartmentId = null;
    public string $createRole = '';
    public string $createPassword = '';

    // Role management (Super Admin only)
    public bool $showRoleModal = false;
    public string $newRoleName = '';

    public bool $showDepartmentModal = false;
    public string $newDepartmentName = '';
    public string $newDepartmentCode = '';
    public bool $editAutoApprove = false;
    public bool $showAutoApproveConfirm = false;

    #[Computed]
    public function manageableUsers()
    {
        $actor = Auth::user();

        if ($actor->hasRole('Super Admin')) {
            return User::with('roles', 'department')->orderBy('name')->get();
        }

        if ($actor->hasRole(['Manager', 'Team Lead'])) {
            // Manager & Team Lead: all Employees + Team Leads, across every department
            return User::with('roles', 'department')
                ->whereHas('roles', fn ($q) => $q->whereIn('name', ['Employee', 'Team Lead']))
                ->orderBy('name')
                ->get();
        }

        return collect();
    }

    #[Computed]
    public function departments()
    {
        return Department::orderBy('name')->get();
    }

    #[Computed]
    public function assignableRoles(): array
    {
        return Gate::allows('viewAny', User::class)
            ? app(\App\Policies\UserPolicy::class)->assignableRoles(Auth::user())
            : [];
    }

    public function edit(int $userId): void
    {
        $target = User::with('roles')->findOrFail($userId);

        Gate::authorize('manage', $target);

        $this->editingUserId = $userId;
        $this->editName = $target->name;
        $this->editEmail = $target->email;
        $this->editPhone = $target->phone;
        $this->editDepartmentId = $target->department_id;
        $this->editRole = $target->roles->first()?->name ?? '';
        $this->editIsActive = $target->is_active;
        $this->editNewPassword = '';
        $this->editAutoApprove = $target->auto_approve_tasks;
    }

    public function cancelEdit(): void
    {
        $this->reset([
            'editingUserId', 'editName', 'editEmail', 'editPhone',
            'editDepartmentId', 'editRole', 'editIsActive', 'editNewPassword', 'editAutoApprove', 'showAutoApproveConfirm',
        ]);
    }

    public function save(): void
    {
        $target = User::findOrFail($this->editingUserId);
        Gate::authorize('manage', $target);

        $allowedRoles = $this->assignableRoles;

        $this->validate([
            'editName' => 'required|string|max:191',
            'editEmail' => ['required', 'email', Rule::unique('users', 'email')->ignore($target->id)],
            'editPhone' => 'nullable|string|max:30',
            'editDepartmentId' => 'required|exists:departments,id',
            'editRole' => ['required', Rule::in($allowedRoles)],
            'editNewPassword' => 'nullable|string|min:8',
        ]);

        $target->update([
            'name' => $this->editName,
            'email' => $this->editEmail,
            'phone' => $this->editPhone,
            'department_id' => Auth::user()->hasRole(['Super Admin', 'Manager'])
    ? $this->editDepartmentId
    : $target->department_id,
            'is_active' => $this->editIsActive,
            'auto_approve_tasks' => $this->editAutoApprove, // add this line
        ]);

        if ($this->editNewPassword) {
            $target->update(['password' => Hash::make($this->editNewPassword)]);
        }

        $target->syncRoles([$this->editRole]);

        $this->cancelEdit();
        unset($this->manageableUsers);
        $this->dispatch('user-updated');
    }

    public function openCreateModal(): void
    {
        Gate::authorize('viewAny', User::class);

        $this->reset([
            'createEmployeeCode', 'createName', 'createEmail', 'createPhone',
            'createDepartmentId', 'createRole', 'createPassword',
        ]);

        $this->createEmployeeCode = $this->nextEmployeeCode();

        if (! Auth::user()->hasRole('Super Admin')) {
            $this->createDepartmentId = Auth::user()->department_id;
        }

        $this->showCreateModal = true;
    }

    /** Next sequential EMP-XXXX code, based on the highest existing number (including soft-deleted users, so codes are never reissued). */
    private function nextEmployeeCode(): string
    {
        $maxNumber = User::withTrashed()
            ->where('employee_code', 'like', 'EMP-%')
            ->get()
            ->map(fn ($u) => (int) substr($u->employee_code, 4))
            ->max();

        $next = ($maxNumber ?? 0) + 1;

        return 'EMP-' . str_pad((string) $next, 4, '0', STR_PAD_LEFT);
    }

    public function cancelCreate(): void
    {
        $this->showCreateModal = false;
    }

    public function createUser(): void
    {
        Gate::authorize('viewAny', User::class);

        $allowedRoles = $this->assignableRoles;

        $this->validate([
            'createEmployeeCode' => 'required|string|max:191|unique:users,employee_code',
            'createName' => 'required|string|max:191',
            'createEmail' => 'required|email|unique:users,email',
            'createPhone' => 'nullable|string|max:30',
            'createDepartmentId' => 'required|exists:departments,id',
            'createRole' => ['required', Rule::in($allowedRoles)],
            'createPassword' => 'required|string|min:8',
        ]);

        $actor = Auth::user();
        $departmentId = $actor->hasRole('Super Admin') ? $this->createDepartmentId : $actor->department_id;

        $user = User::create([
            'employee_code' => $this->createEmployeeCode,
            'name' => $this->createName,
            'email' => $this->createEmail,
            'phone' => $this->createPhone,
            'department_id' => $departmentId,
            'is_active' => true,
            'password' => Hash::make($this->createPassword),
        ]);

        $user->assignRole($this->createRole);

        $this->showCreateModal = false;
        unset($this->manageableUsers);
        $this->dispatch('user-created');
    }

    /** Only Super Admin manages the role list itself (adding new custom roles). */
    public function openRoleModal(): void
    {
        if (! Auth::user()->hasRole(['Super Admin', 'Manager'])) {
            abort(403);
        }

        $this->newRoleName = '';
        $this->showRoleModal = true;
    }

    public function addRole(): void
    {
        if (! Auth::user()->hasRole(['Super Admin', 'Manager'])) {
            abort(403);
        }

        $this->validate([
            'newRoleName' => 'required|string|max:50|unique:roles,name',
        ]);

        \Spatie\Permission\Models\Role::create(['name' => $this->newRoleName, 'guard_name' => 'web']);

        $this->newRoleName = '';
        unset($this->allRoles);
        $this->dispatch('role-created');
    }

    public function openDepartmentModal(): void
    {
        if (! Auth::user()->hasRole(['Super Admin', 'Manager'])) {
            abort(403);
        }

        $this->newDepartmentName = '';
        $this->newDepartmentCode = '';
        $this->showDepartmentModal = true;
    }

    public function addDepartment(): void
    {
        if (! Auth::user()->hasRole(['Super Admin', 'Manager'])) {
            abort(403);
        }

        $this->validate([
            'newDepartmentName' => 'required|string|max:191',
            'newDepartmentCode' => 'required|string|max:20|unique:departments,code',
        ]);

        Department::create([
            'name' => $this->newDepartmentName,
            'code' => strtoupper($this->newDepartmentCode),
        ]);

        $this->newDepartmentName = '';
        $this->newDepartmentCode = '';
        unset($this->departments, $this->allDepartments);
        $this->dispatch('department-created');
    }

    #[Computed]
    public function allDepartments()
    {
        return Department::orderBy('name')->get();
    }

    #[Computed]
    public function allRoles()
    {
        return Auth::user()->hasRole(['Super Admin', 'Manager'])
            ? \Spatie\Permission\Models\Role::orderBy('name')->get()
            : collect();
    }

    public function toggleAutoApprove(): void
    {
        // Turning it OFF needs no confirmation — only turning it ON does.
        if ($this->editAutoApprove) {
            $this->editAutoApprove = false;
            return;
        }

        $this->showAutoApproveConfirm = true;
    }

    public function confirmAutoApprove(): void
    {
        $this->editAutoApprove = true;
        $this->showAutoApproveConfirm = false;
    }

    public function cancelAutoApproveConfirm(): void
    {
        $this->showAutoApproveConfirm = false;
        // editAutoApprove stays false / unchanged since we never set it true
    }


    public function render()
    {
        return view('livewire.user-management');
    }
}
