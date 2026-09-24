<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ClientManagementSimple extends Component
{
    public string $statusFilter = 'all'; // all | active | hold | inactive
    public string $search = '';

    public bool $showModal = false;
    public ?int $editingId = null;

    public string $name = '';
    public string $company = '';
    public string $email = '';
    public string $phone = '';
    public string $status = 'active';
    public ?int $departmentId = null;
    public string $notes = '';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public bool $isPriority = false;

    #[Computed]
    public function clients()
    {
        $query = Client::query()->visibleTo(Auth::user());

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if (trim($this->search) !== '') {
            $query->where(function ($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                    ->orWhere('company', 'like', '%' . $this->search . '%');
            });
        }

        return $query
            ->orderByDesc('is_priority')
            ->orderByRaw("FIELD(status, 'active', 'hold', 'inactive')")
            ->orderBy('name')
            ->get();
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
        Gate::authorize('create', Client::class);

        $this->reset(['editingId', 'name', 'company', 'email', 'phone', 'notes', 'startDate', 'endDate']);
        $this->status = 'active';
        $this->departmentId = Auth::user()->hasRole(['Super Admin', 'Manager']) ? null : Auth::user()->department_id;
        $this->isPriority = false;
        $this->showModal = true;
    }

    public function openEdit(int $clientId): void
    {
        $client = Client::findOrFail($clientId);
        Gate::authorize('manage', $client);

        $this->editingId = $client->id;
        $this->name = $client->name;
        $this->company = (string) $client->company;
        $this->email = (string) $client->email;
        $this->phone = (string) $client->phone;
        $this->status = $client->status;
        $this->departmentId = $client->department_id;
        $this->notes = (string) $client->notes;
        $this->isPriority = $client->is_priority;
        $this->startDate = $client->start_date?->format('Y-m-d');
        $this->endDate = $client->end_date?->format('Y-m-d');

        $this->showModal = true;
    }

    public function cancel(): void
    {
        $this->showModal = false;
    }

    public function save(): void
    {
        $isNew = ! $this->editingId;
        $existing = $isNew ? null : Client::findOrFail($this->editingId);

        if ($isNew) {
            Gate::authorize('create', Client::class);
        } else {
            Gate::authorize('manage', $existing);
        }

        $this->validate([
            'name' => 'required|string|max:191',
            'company' => 'nullable|string|max:191',
            'email' => 'nullable|email|max:191',
            'phone' => 'nullable|string|max:30',
            'status' => 'required|in:active,hold,inactive',
            'departmentId' => 'required|exists:departments,id',
            'notes' => 'nullable|string',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after_or_equal:startDate',
        ]);

        $departmentId = Auth::user()->hasRole(['Super Admin', 'Manager'])
            ? $this->departmentId
            : Auth::user()->department_id;

        Client::updateOrCreate(
            ['id' => $this->editingId],
            [
                'name' => $this->name,
                'company' => $this->company ?: null,
                'email' => $this->email ?: null,
                'phone' => $this->phone ?: null,
                'status' => $this->status,
                'is_priority' => $this->isPriority,
                'department_id' => $departmentId,
                'start_date' => $this->startDate,
                'end_date' => $this->endDate,
                'created_by' => $existing->created_by ?? Auth::id(),
                'notes' => $this->notes ?: null,
            ]
        );

        $this->isPriority = false;
        $this->showModal = false;
        unset($this->clients);
        $this->dispatch('client-saved');
    }

    public function render()
    {
        return view('livewire.client-management-simple');
    }
}
