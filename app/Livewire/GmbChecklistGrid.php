<?php

namespace App\Livewire;

use App\Models\GmbCategory;
use App\Models\GmbClient;
use App\Models\GmbChecklistEntry;
use App\Models\GmbPeriod;
use App\Models\User;
use Illuminate\Support\Facades\{Auth, DB, Gate};
use Livewire\Attributes\Computed;
use Livewire\Component;

class GmbChecklistGrid extends Component
{
    public ?int $viewingPeriodId = null;

    // Add Client modal
    public bool $showAddModal = false;
    public string $newClientName = '';

    // Permission management modal (TL+)
    public bool $showPermissionModal = false;

    public string $search = '';
    
    public ?int $newClientAssignedTo = null; 

    public function mount(): void
    {
        abort_unless($this->canAccessModule(), 403);
    }

    private function canAccessModule(): bool
    {
        $user = Auth::user();
        return $user->department?->code === 'GMB' || $user->hasRole(['Super Admin', 'Manager']);
    }

    private function departmentId(): ?int
    {
        return Auth::user()->hasRole(['Super Admin', 'Manager'])
            ? \App\Models\Department::where('code', 'GMB')->value('id')
            : Auth::user()->department_id;
    }

    #[Computed]
    public function currentPeriod(): GmbPeriod
    {
        return GmbPeriod::firstOrCreate(
            ['department_id' => $this->departmentId(), 'month' => now()->month, 'year' => now()->year],
            ['status' => 'open']
        );
    }

    #[Computed]
    public function period(): GmbPeriod
    {
        return $this->viewingPeriodId
            ? GmbPeriod::findOrFail($this->viewingPeriodId)
            : $this->currentPeriod;
    }

    #[Computed]
    public function pastPeriods()
    {
        return GmbPeriod::where('department_id', $this->departmentId())
            ->where('status', 'completed')
            ->orderByDesc('year')->orderByDesc('month')
            ->get();
    }

    #[Computed]
    public function categories()
    {
        return GmbCategory::with('subcategories')->orderBy('sort_order')->get();
    }

    #[Computed]
    public function clients()
    {
        $query = GmbClient::where('department_id', $this->departmentId())
            ->where('is_active', true);

        // Employees only see clients assigned to them. TL and above see everyone.
        if (! Auth::user()->hasRole(['Super Admin', 'Manager', 'Team Lead'])) {
            $query->where('assigned_to', Auth::id());
        }

        return $query->orderBy('name')->get();
    }

    /** GMB-department employees, for the permission-toggle list. */
    #[Computed]
    public function gmbEmployees()
    {
        return User::where('department_id', $this->departmentId())
            ->whereHas('roles', fn ($q) => $q->where('name', 'Employee'))
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function entries()
    {
        return GmbChecklistEntry::where('gmb_period_id', $this->period->id)
            ->get()
            ->keyBy(fn ($e) => $e->gmb_client_id . '-' . $e->gmb_subcategory_id);
    }

    public function isChecked(int $clientId, int $subcategoryId): bool
    {
        return (bool) ($this->entries->get("{$clientId}-{$subcategoryId}")?->is_checked);
    }

    public function toggle(int $clientId, int $subcategoryId): void
    {
        // Only editable on the OPEN (current) period, never on archived ones.
        if ($this->period->status !== 'open') {
            return;
        }

        Gate::authorize('toggleGmbChecklist', GmbClient::class);

        GmbChecklistEntry::updateOrCreate(
            [
                'gmb_period_id' => $this->period->id,
                'gmb_client_id' => $clientId,
                'gmb_subcategory_id' => $subcategoryId,
            ],
            [
                'is_checked' => ! $this->isChecked($clientId, $subcategoryId),
                'updated_by' => Auth::id(),
                'checked_at' => now(),
            ]
        );

        unset($this->entries);
    }

    public function viewPeriod(?int $periodId): void
    {
        $this->viewingPeriodId = $periodId;
        unset($this->entries);
    }

    /** Team Lead+ marks the month complete, archives it, and opens next month fresh. */
    public function completeMonth(): void
    {
        Gate::authorize('completeGmbMonth', GmbClient::class);

        DB::transaction(function () {
            $this->currentPeriod->update([
                'status' => 'completed',
                'completed_by' => Auth::id(),
                'completed_at' => now(),
            ]);
        });

        unset($this->currentPeriod, $this->period, $this->pastPeriods, $this->entries);
        $this->viewingPeriodId = null;
        $this->dispatch('gmb-month-completed');
    }

    // ---------- Add Client ----------

    public function openAddModal(): void
    {
        Gate::authorize('addClient', GmbClient::class);
        $this->newClientName = '';
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
        'assigned_to' => $this->newClientAssignedTo,
        'is_active' => true,
    ]);

    $this->showAddModal = false;
    unset($this->clients);
    $this->dispatch('gmb-client-added');
}

public function reassignClient(int $clientId, ?int $userId): void
{
    Gate::authorize('manageCategories', GmbClient::class); // or a dedicated policy method

    GmbClient::whereKey($clientId)->update(['assigned_to' => $userId]);

    unset($this->clients);
}

    // ---------- Permission management ----------

    public function toggleEmployeePermission(int $userId): void
    {
        Gate::authorize('manageCategories', GmbClient::class); // TL+ only

        $user = User::findOrFail($userId);
        $user->update(['can_add_gmb_clients' => ! $user->can_add_gmb_clients]);

        unset($this->gmbEmployees);
    }

    public function render()
    {
        return view('livewire.gmb-checklist-grid');
    }
}
