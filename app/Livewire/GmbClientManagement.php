<?php

namespace App\Livewire;

use App\Models\GmbClient;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class GmbClientManagement extends Component
{
    public bool $showAddModal = false;
    public string $newClientName = '';

    public bool $showBulkModal = false;
    public string $bulkClientNames = '';

    public ?int $editingClientId = null;
    public bool $showEditModal = false;
    public string $editName = '';
    public string $editStatus = 'medium';
    public ?int $editAssignedTo = null;

    public bool $showPermissionModal = false;
    public array $employeePermissions = [];

    public string $search = '';

    // ---------- Delete / bulk select ----------
    public array $selectedClients = [];
    public bool $selectAll = false;

    public function mount(): void
    {
        abort_unless($this->canAccessModule(), 403);
    }

    private function canAccessModule(): bool
    {
        $user = Auth::user();
        return $user->department?->code === 'GMB' || $user->hasRole(['Super Admin', 'Manager']);
    }
    
    public ?int $newClientAssignedTo = null; // only used when the picker is shown

// ---------- helpers ----------

private function autoAssignsSelf(): bool
{
    return Auth::user()->hasRole(['Team Lead', 'Employee'])
        && ! Auth::user()->hasRole(['Super Admin', 'Manager']);
}

private function resolveAssignedTo(?int $picked): ?int
{
    return $this->autoAssignsSelf() ? Auth::id() : $picked;
}

    private function departmentId(): ?int
    {
        return Auth::user()->hasRole(['Super Admin', 'Manager'])
            ? \App\Models\Department::where('code', 'GMB')->value('id')
            : Auth::user()->department_id;
    }

    #[Computed]
    public function clients()
    {
        return GmbClient::with(['creator', 'assignee'])
            ->where('department_id', $this->departmentId())
            ->when($this->search !== '', function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%');
            })
            ->orderBy('name')
            ->get();
    }

    #[Computed]
public function gmbEmployees()
{
    // Used only for the "Manage Add Permission" list — Employees only.
    return User::where('department_id', $this->departmentId())
        ->whereHas('roles', fn ($q) => $q->where('name', ['Employee', 'Team Lead']))
        ->orderBy('name')
        ->get();
}

    // ---------- Single Add ----------

    public function openAddModal(): void
{
    Gate::authorize('addClient', GmbClient::class);
    $this->newClientName = '';
    $this->newClientAssignedTo = null;
    $this->showAddModal = true;
}

public function addClient(): void
{
    Gate::authorize('addClient', GmbClient::class);

    $this->validate([
        'newClientName' => 'required|string|max:191',
        'newClientAssignedTo' => 'nullable|exists:users,id',
    ]);

    GmbClient::create([
        'name' => $this->newClientName,
        'department_id' => $this->departmentId(),
        'created_by' => Auth::id(),
        'assigned_to' => $this->resolveAssignedTo($this->newClientAssignedTo),
        'is_active' => true,
        'performance_status' => 'medium',
    ]);

    $this->showAddModal = false;
    unset($this->clients);
    $this->dispatch('gmb-client-added');
}
    
    #[Computed]
public function assignableUsers()
{
    return User::where('department_id', $this->departmentId())
        ->whereHas('roles', fn ($q) => $q->whereIn('name', ['Employee', 'Team Lead']))
        ->orderBy('name')
        ->get();
}

    // ---------- Bulk Add ----------

    public function openBulkModal(): void
    {
        Gate::authorize('addClient', GmbClient::class);
        $this->bulkClientNames = '';
        $this->showBulkModal = true;
    }

    public function bulkAddClients(): void
{
    Gate::authorize('addClient', GmbClient::class);

    $this->validate(['bulkClientNames' => 'required|string']);

    $names = collect(explode("\n", $this->bulkClientNames))
        ->map(fn ($n) => trim($n))
        ->filter()
        ->unique();

    if ($names->isEmpty()) {
        $this->addError('bulkClientNames', 'Enter at least one client name.');
        return;
    }

    $deptId = $this->departmentId();
    $assignedTo = $this->resolveAssignedTo(null); // self for TL/Employee, null (unassigned) for Admin/Manager on bulk

    $existing = GmbClient::where('department_id', $deptId)
        ->whereIn('name', $names)
        ->pluck('name')
        ->map(fn ($n) => strtolower($n));

    $created = 0;
    $skipped = [];

    foreach ($names as $name) {
        if ($existing->contains(strtolower($name))) {
            $skipped[] = $name;
            continue;
        }

        GmbClient::create([
            'name' => $name,
            'department_id' => $deptId,
            'created_by' => Auth::id(),
            'assigned_to' => $assignedTo,
            'is_active' => true,
            'performance_status' => 'medium',
        ]);
        $created++;
    }

    $this->showBulkModal = false;
    unset($this->clients);

    $message = "{$created} client(s) added.";
    if (! empty($skipped)) {
        $message .= ' Skipped (already exist): ' . implode(', ', $skipped);
    }

    $this->dispatch('gmb-bulk-added', message: $message);
}

#[Computed]
public function isAutoAssignSelf(): bool
{
    return $this->autoAssignsSelf();
}

    // ---------- Edit ----------

    public function openEditClient(int $id): void
    {
        Gate::authorize('manageCategories', GmbClient::class);

        $client = GmbClient::findOrFail($id);
        $this->editingClientId = $client->id;
        $this->editName = $client->name;
        $this->editStatus = $client->performance_status;
        $this->editAssignedTo = $client->assigned_to;
        $this->showEditModal = true;
    }

    public function saveEditClient(): void
    {
        Gate::authorize('manageCategories', GmbClient::class);

        $this->validate([
            'editName' => 'required|string|max:191',
            'editStatus' => 'required|in:gone,low,medium,high,very_high',
            'editAssignedTo' => 'nullable|exists:users,id',
        ]);

        GmbClient::findOrFail($this->editingClientId)->update([
            'name' => $this->editName,
            'performance_status' => $this->editStatus,
            'assigned_to' => $this->editAssignedTo,
        ]);

        $this->showEditModal = false;
        unset($this->clients);
        $this->dispatch('gmb-client-updated');
    }

    public function deactivateClient(int $id): void
    {
        Gate::authorize('manageCategories', GmbClient::class);
        GmbClient::findOrFail($id)->update(['is_active' => false]);
        unset($this->clients);
    }

    public function reactivateClient(int $id): void
    {
        Gate::authorize('manageCategories', GmbClient::class);
        GmbClient::findOrFail($id)->update(['is_active' => true]);
        unset($this->clients);
    }

    // ---------- Delete (single + bulk, soft delete) ----------

    public function deleteClient(int $id): void
    {
        Gate::authorize('deleteClient', GmbClient::class);

        GmbClient::where('department_id', $this->departmentId())
            ->whereKey($id)
            ->delete(); // soft delete (sets deleted_at)

        $this->selectedClients = array_values(array_diff($this->selectedClients, [(string) $id, $id]));
        $this->selectAll = false;
        unset($this->clients);
        $this->dispatch('gmb-client-deleted');
    }

    public function bulkDeleteClients(): void
    {
        Gate::authorize('deleteClient', GmbClient::class);

        if (empty($this->selectedClients)) {
            return;
        }

        $count = GmbClient::where('department_id', $this->departmentId())
            ->whereIn('id', $this->selectedClients)
            ->delete(); // soft delete on all matched rows

        $this->selectedClients = [];
        $this->selectAll = false;
        unset($this->clients);

        $this->dispatch('gmb-bulk-deleted', message: "{$count} client(s) deleted.");
    }

    public function updatedSelectAll($value): void
    {
        $this->selectedClients = $value
            ? $this->clients->pluck('id')->map(fn ($id) => (string) $id)->toArray()
            : [];
    }

    public function updatedSelectedClients(): void
    {
        // Keep the header checkbox in sync if the user unticks a row manually.
        $this->selectAll = $this->clients->isNotEmpty()
            && count($this->selectedClients) === $this->clients->count();
    }

    // ---------- Permissions ----------

    public function openPermissionModal(): void
    {
        Gate::authorize('manageCategories', GmbClient::class);

        $this->employeePermissions = $this->gmbEmployees
            ->mapWithKeys(fn ($emp) => [$emp->id => (bool) $emp->can_add_gmb_clients])
            ->toArray();

        $this->showPermissionModal = true;
    }

    public function updatedEmployeePermissions($value, $key): void
    {
        Gate::authorize('manageCategories', GmbClient::class);
        User::where('id', $key)->update(['can_add_gmb_clients' => (bool) $value]);
        unset($this->gmbEmployees);
    }

    public function render()
    {
        return view('livewire.gmb-client-management');
    }
}