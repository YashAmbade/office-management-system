<?php

namespace App\Livewire;

use App\Enums\Platform;
use App\Models\Client;
use App\Models\Department;
use App\Models\Service;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ClientDetail extends Component
{
    public Client $client;
    public string $newServiceName = '';

    public bool $editMode = false;

    // Editable fields
    public string $name = '';
    public string $company = '';
    public string $email = '';
    public string $phone = '';
    public string $status = 'active';
    public ?int $departmentId = null;
    public string $notes = '';
    public ?string $startDate = null;
    public ?string $endDate = null;
    public string $taskStatusFilter = 'all';
    public ?string $taskDateFrom = null;
    public ?string $taskDateTo = null;
    public string $taskContentTypeFilter = 'all';

    /** @var array<int, array{platform: string, reels_count: int, posts_count: int}> */
    public array $planItems = [];

    public function mount(Client $client): void
    {
        $this->client = $client;
        $this->hydrateFromClient();
    }

    private function hydrateFromClient(): void
    {
        $this->name = $this->client->name;
        $this->company = (string) $this->client->company;
        $this->email = (string) $this->client->email;
        $this->phone = (string) $this->client->phone;
        $this->status = $this->client->status;
        $this->departmentId = $this->client->department_id;
        $this->notes = (string) $this->client->notes;
        $this->startDate = $this->client->start_date?->format('Y-m-d');
        $this->endDate = $this->client->end_date?->format('Y-m-d');

        $this->planItems = $this->client->planItems->map(fn ($p) => [
    'platform' => $p->platform,
    'content_type_id' => $p->content_type_id,
    'quantity' => $p->quantity,
    'notes' => $p->notes,
    'setup_done' => $p->setup_done,
])->toArray();

        if (empty($this->planItems)) {
            $this->planItems = [['platform' => '', 'content_type_id' => null, 'quantity' => 0, 'notes' => '', 'setup_done' => false]];
        }
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

    #[Computed]
    public function platformOptions(): array
    {
        return Platform::options();
    }

    public function enterEditMode(): void
    {
        Gate::authorize('manage', $this->client);
        $this->hydrateFromClient();
        $this->editMode = true;
    }

    public function cancelEdit(): void
    {
        $this->hydrateFromClient();
        $this->editMode = false;
    }

    public function addPlanItem(): void
{
    $this->planItems[] = ['platform' => '', 'content_type_id' => null, 'quantity' => 0, 'notes' => '', 'setup_done' => false];
}

    public function removePlanItem(int $index): void
    {
        unset($this->planItems[$index]);
        $this->planItems = array_values($this->planItems);
    }

    #[Computed]
public function contentTypeOptions()
{
    return \App\Models\ContentType::orderBy('name')->get();
}

    public function save(): void
    {
        Gate::authorize('manage', $this->client);

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
            'planItems.*.platform' => 'nullable|string|in:'.implode(',', Platform::options()),
            'planItems.*.content_type_id' => 'nullable|exists:content_types,id',
            'planItems.*.quantity' => 'nullable|integer|min:0',
        ]);

        $departmentId = Auth::user()->hasRole(['Super Admin', 'Manager'])
            ? $this->departmentId
            : Auth::user()->department_id;

        $this->client->update([
            'name' => $this->name,
            'company' => $this->company ?: null,
            'email' => $this->email ?: null,
            'phone' => $this->phone ?: null,
            'status' => $this->status,
            'department_id' => $departmentId,
            'start_date' => $this->startDate,
            'end_date' => $this->endDate,
            'notes' => $this->notes ?: null,
        ]);

        $this->client->planItems()->delete();
        foreach ($this->planItems as $item) {
            if (blank($item['platform']) || blank($item['content_type_id'])) {
                continue;
            }
            $this->client->planItems()->create([
                'platform' => $item['platform'],
                'content_type_id' => $item['content_type_id'],
                'quantity' => $item['quantity'] ?: 0,
                'notes' => $item['notes'] ?? null,
                'setup_done' => $item['setup_done'] ?? false,
            ]);
        }

        $this->client->refresh();
        $this->editMode = false;
        $this->dispatch('client-saved');
    }

    #[Computed]
    public function allServices()
    {
        return Service::orderBy('name')->get();
    }

    #[Computed]
    public function attachedServices()
    {
        return $this->client->services()->orderBy('name')->get();
    }

    public function addService(): void
    {
        Gate::authorize('manageServices', Client::class);

        $this->validate(['newServiceName' => 'required|string|max:191']);

        $service = Service::whereRaw('LOWER(name) = ?', [strtolower(trim($this->newServiceName))])->first()
            ?? Service::create(['name' => trim($this->newServiceName)]);

        $this->client->services()->syncWithoutDetaching([$service->id]);

        $this->newServiceName = '';
        unset($this->attachedServices, $this->allServices);
    }

    public function removeService(int $serviceId): void
    {
        Gate::authorize('manageServices', Client::class);

        $this->client->services()->detach($serviceId);
        unset($this->attachedServices);
    }

    #[Computed]
    public function clientTasks()
    {
        $query = $this->client->tasks()
            ->with(['currentAssignment.assignee', 'creator']);

        if ($this->taskStatusFilter !== 'all') {
            $query->where('status', $this->taskStatusFilter);
        }

        if ($this->taskContentTypeFilter !== 'all') {
            $query->where('content_type', $this->taskContentTypeFilter);
        }

        if ($this->taskDateFrom) {
            $query->whereDate('created_at', '>=', $this->taskDateFrom);
        }

        if ($this->taskDateTo) {
            $query->whereDate('created_at', '<=', $this->taskDateTo);
        }

        return $query->orderByDesc('created_at')->get();
    }

    public function clearTaskFilters(): void
    {
        $this->reset(['taskStatusFilter', 'taskContentTypeFilter', 'taskDateFrom', 'taskDateTo']);
    }

    public function render()
    {
        return view('livewire.client-detail');
    }
}
