<?php

namespace App\Livewire;

use App\Models\Client;
use App\Models\Department;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class ClientManagement extends Component
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
    public string $quickAddPlatform = '';
    public array $quickAddContentTypeIds = [];

    /** @var array<int, array{item_name: string, quantity: int, period: string}> */
    public array $planItems = [];

    #[Computed]
    public function clients()
    {
        $query = Client::query()->visibleTo(Auth::user())->with(['department', 'planItems.contentType']);

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

        // Team Lead: locked to their own department, same pattern as UserManagement
        return Department::where('id', $user->department_id)->get();
    }

    public function openCreate(): void
    {
        Gate::authorize('create', Client::class);

        $this->reset(['editingId', 'name', 'company', 'email', 'phone', 'notes', 'startDate', 'endDate', 'quickAddPlatform', 'quickAddContentTypeIds']);
        $this->status = 'active';
        $this->departmentId = Auth::user()->hasRole(['Super Admin', 'Manager']) ? null : Auth::user()->department_id;
        $this->planItems = [
            ['platform' => '', 'items' => [['content_type_id' => null, 'quantity' => 0, 'notes' => '', 'setup_done' => false]]],
        ];
        $this->showModal = true;
        $this->isPriority = false;
    }

    public function openEdit(int $clientId): void
    {
        $client = Client::with('planItems')->findOrFail($clientId);
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


        $this->planItems = $client->planItems
             ->groupBy('platform')
             ->map(function ($items, $platform) {
                 return [
                     'platform' => $platform,
                     'items' => $items->map(fn ($p) => [
                         'content_type_id' => $p->content_type_id,
                         'quantity' => $p->quantity,
                         'notes' => $p->notes,
                         'setup_done' => $p->setup_done,
                     ])->values()->toArray(),
                 ];
             })
             ->values()
             ->toArray();

        if (empty($this->planItems)) {
            $this->planItems = [
                ['platform' => '', 'items' => [['content_type_id' => null, 'quantity' => 0, 'notes' => '', 'setup_done' => false]]],
            ];
        }

        $this->quickAddPlatform = '';
        $this->quickAddContentTypeIds = [];
        $this->showModal = true;
    }

    public function addPlatformBlock(): void
    {
        $this->planItems[] = [
            'platform' => '',
            'items' => [['content_type_id' => null, 'quantity' => 0, 'notes' => '', 'setup_done' => false]],
        ];
    }

    public function removePlatformBlock(int $platformIndex): void
    {
        unset($this->planItems[$platformIndex]);
        $this->planItems = array_values($this->planItems);
    }

    public function addCategoryRow(int $platformIndex): void
    {
        $this->planItems[$platformIndex]['items'][] = ['content_type_id' => null, 'quantity' => 0, 'notes' => '', 'setup_done' => false];
    }

    public function removeCategoryRow(int $platformIndex, int $itemIndex): void
    {
        unset($this->planItems[$platformIndex]['items'][$itemIndex]);
        $this->planItems[$platformIndex]['items'] = array_values($this->planItems[$platformIndex]['items']);
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
            'planItems.*.platform' => 'nullable|string|in:' . implode(',', \App\Enums\Platform::options()),
            'planItems.*.items.*.content_type_id' => 'nullable|exists:content_types,id',
            'planItems.*.items.*.quantity' => 'nullable|integer|min:0',
        ]);

        // Team Lead can't assign a client outside their own department, even via tampering.
        $departmentId = Auth::user()->hasRole(['Super Admin', 'Manager'])
            ? $this->departmentId
            : Auth::user()->department_id;

        $client = Client::updateOrCreate(
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

        $client->planItems()->delete();
        foreach ($this->planItems as $block) {
            if (blank($block['platform'])) {
                continue;
            }
            foreach ($block['items'] as $item) {
                if (blank($item['content_type_id'])) {
                    continue;
                }
                $client->planItems()->create([
                    'platform' => $block['platform'],
                    'content_type_id' => $item['content_type_id'],
                    'quantity' => $item['quantity'] ?: 0,
                    'notes' => $item['notes'] ?? null,
                    'setup_done' => $item['setup_done'] ?? false,
                ]);
            }
        }

        $this->isPriority = false;
        $this->showModal = false;
        unset($this->clients);
        $this->dispatch('client-saved');
    }

    #[Computed]
    public function contentTypeOptions()
    {
        return \App\Models\ContentType::orderBy('name')->get();
    }

    #[Computed]
    public function allPlatforms()
    {
        return $this->clients
            ->flatMap(fn ($client) => $client->planItems->pluck('platform'))
            ->unique()
            ->sort()
            ->values();
    }

    public function quickAddPlanItems(): void
    {
        if (blank($this->quickAddPlatform) || empty($this->quickAddContentTypeIds)) {
            return;
        }

        foreach ($this->quickAddContentTypeIds as $contentTypeId) {
            // Skip if this exact platform + content type combo already exists in the current list.
            $alreadyExists = collect($this->planItems)->contains(
                fn ($item) => $item['platform'] === $this->quickAddPlatform && (int) ($item['content_type_id'] ?? 0) === (int) $contentTypeId
            );

            if ($alreadyExists) {
                continue;
            }

            // Drop the very first row if it's still the untouched blank default.
            if (count($this->planItems) === 1 && blank($this->planItems[0]['platform'])) {
                $this->planItems = [];
            }

            $this->planItems[] = [
                'platform' => $this->quickAddPlatform,
                'content_type_id' => $contentTypeId,
                'quantity' => 0,
                'notes' => '',
                'setup_done' => false,
            ];
        }

        $this->quickAddPlatform = '';
        $this->quickAddContentTypeIds = [];
    }

    public bool $showExpiringModal = false;
     #[Computed]
    public function expiringClients()
    {
        if (! auth()->user()->hasRole(['Super Admin', 'Manager'])) {
            return collect();
        }

        return Client::where('status', '!=', 'inactive')
            ->whereNotNull('end_date')
            ->whereBetween('end_date', [now()->startOfDay(), now()->addDays(5)->endOfDay()])
            ->orderBy('end_date')
            ->get();
    }

    public function render()
    {
        return view('livewire.client-management');
    }
}
