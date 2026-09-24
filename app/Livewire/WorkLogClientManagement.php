<?php

namespace App\Livewire;

use App\Models\Department;
use App\Models\WorkLogClient;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WorkLogClientManagement extends Component
{
    public bool $showModal = false;
    public bool $showTrashed = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $company = '';
    public string $notes = '';
    public ?int $departmentId = null;
    public bool $isActive = true;

    #[Computed]
    public function clients()
    {
        $query = WorkLogClient::with('department');

        if (! Auth::user()->hasRole(['Super Admin', 'Manager'])) {
            $query->where('department_id', Auth::user()->department_id);
        }

        return $query->orderBy('name')->get();
    }

    #[Computed]
    public function trashedClients()
    {
        if (! Auth::user()->hasRole(['Super Admin', 'Manager'])) {
            return collect();
        }

        $query = WorkLogClient::onlyTrashed()->with(['department', 'deleter']);

        if (! Auth::user()->hasRole(['Super Admin', 'Manager'])) {
            $query->where('department_id', Auth::user()->department_id);
        }

        return $query->orderByDesc('deleted_at')->get();
    }

    #[Computed]
    public function departments()
    {
        $user = Auth::user();

        if ($user->hasRole(['Super Admin', 'Manager'])) {
            return Department::orderBy('name')->get();
        }

        return Department::where('id', $user->department_id)->get();
    }

    public function openCreate(): void
    {
        Gate::authorize('create', WorkLogClient::class);
        $this->reset(['editingId', 'name', 'company', 'notes', 'departmentId']);
        $this->isActive = true;
        $this->departmentId = Auth::user()->hasRole(['Super Admin', 'Manager']) ? null : Auth::user()->department_id;
        $this->showModal = true;
    }

    public function openEdit(int $id): void
    {
        Gate::authorize('manage', WorkLogClient::class);

        $c = WorkLogClient::findOrFail($id);

        if (! Auth::user()->hasRole(['Super Admin', 'Manager']) && $c->department_id !== Auth::user()->department_id) {
            abort(403);
        }

        $this->editingId = $c->id;
        $this->name = $c->name;
        $this->company = (string) $c->company;
        $this->notes = (string) $c->notes;
        $this->departmentId = $c->department_id;
        $this->isActive = $c->is_active;
        $this->showModal = true;
    }

    public function cancel(): void
    {
        $this->showModal = false;
    }

    public function save(): void
    {
        if ($this->editingId) {
            Gate::authorize('manage', WorkLogClient::class);
        } else {
            Gate::authorize('create', WorkLogClient::class);
        }

        $this->validate([
            'name' => 'required|string|max:191',
            'company' => 'nullable|string|max:191',
            'notes' => 'nullable|string',
            'departmentId' => 'required|exists:departments,id',
        ]);

        $departmentId = Auth::user()->hasRole(['Super Admin', 'Manager'])
            ? $this->departmentId
            : Auth::user()->department_id;

        WorkLogClient::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'company' => $this->company ?: null,
                'notes' => $this->notes ?: null,
                'department_id' => $departmentId,
                'is_active' => $this->isActive,
                'created_by' => $this->editingId ? WorkLogClient::find($this->editingId)->created_by : Auth::id(),
            ]
        );

        $this->showModal = false;
        unset($this->clients);
        $this->dispatch('work-log-client-saved');
    }

    public function delete(int $id): void
    {
        Gate::authorize('manage', WorkLogClient::class);

        $client = WorkLogClient::findOrFail($id);

        if (! Auth::user()->hasRole(['Super Admin', 'Manager']) && $client->department_id !== Auth::user()->department_id) {
            abort(403);
        }

        $client->update(['deleted_by' => Auth::id()]);
        $client->delete(); // soft delete

        unset($this->clients);
        unset($this->trashedClients);
        $this->dispatch('work-log-client-deleted');
    }

    public function restore(int $id): void
    {
        Gate::authorize('manage', WorkLogClient::class);

        $client = WorkLogClient::onlyTrashed()->findOrFail($id);

        if (! Auth::user()->hasRole(['Super Admin', 'Manager']) && $client->department_id !== Auth::user()->department_id) {
            abort(403);
        }

        $client->update(['deleted_by' => null]);
        $client->restore();

        unset($this->clients);
        unset($this->trashedClients);
        $this->dispatch('work-log-client-restored');
    }

    public function toggleTrashedView(): void
    {
        if (! Auth::user()->hasRole(['Super Admin', 'Manager'])) {
            abort(403);
        }

        $this->showTrashed = ! $this->showTrashed;
    }

    public function render()
    {
        return view('livewire.work-log-client-management');
    }
}
