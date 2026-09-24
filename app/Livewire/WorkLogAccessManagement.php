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

class WorkLogAccessManagement extends Component
{
    public bool $showModal = false;

    public bool $showCreateModal = false;

    // Create form fields
    public string $createEmployeeCode = '';
    public string $createName = '';
    public string $createEmail = '';
    public ?string $createPhone = null;
    public ?int $createDepartmentId = null;
    public string $createRole = '';
    public string $createPassword = '';

    public function open(): void
    {
        Gate::authorize('manage', \App\Models\WorkLogClient::class);
        $this->showModal = true;
    }

    #[Computed]
    public function toggleableUsers()
    {
        return User::where('is_active', true)
            ->whereHas('roles', fn ($q) => $q->whereIn('name', ['Employee', 'Team Lead']))
            ->with('department')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function departments()
    {
        return Department::orderBy('name')->get();
    }

    /** Only Employee / Team Lead are offered here — this modal is scoped to Work Log access, not full user administration. */
    #[Computed]
    public function assignableRoles(): array
    {
        $allowed = Gate::allows('viewAny', User::class)
            ? app(\App\Policies\UserPolicy::class)->assignableRoles(Auth::user())
            : [];

        return array_values(array_intersect($allowed, ['Employee', 'Team Lead']));
    }

    public function toggleAddClientAccess(int $userId): void
    {
        Gate::authorize('manage', \App\Models\WorkLogClient::class);

        $user = User::findOrFail($userId);
        $user->update(['can_add_work_log_clients' => ! $user->can_add_work_log_clients]);

        unset($this->toggleableUsers);
    }

    public function openCreateModal(): void
{
    Gate::authorize('viewAny', User::class);

    $this->reset([
        'createEmployeeCode', 'createName', 'createEmail', 'createPhone',
        'createDepartmentId', 'createRole', 'createPassword',
    ]);

    $this->createEmployeeCode = $this->nextEmployeeCode();   // ← this line

    if (! Auth::user()->hasRole('Super Admin')) {
        $this->createDepartmentId = Auth::user()->department_id;
    }

    $this->showCreateModal = true;
}

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
        unset($this->toggleableUsers);
        $this->dispatch('user-created');
    }

    public function render()
    {
        return view('livewire.work-log-access-management');
    }
}
