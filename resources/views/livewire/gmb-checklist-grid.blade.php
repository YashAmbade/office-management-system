<div class="p-3 md:p-5 space-y-4">
    <div class="bg-surface border border-border rounded-2xl p-4 flex items-center justify-between flex-wrap gap-3">
        <div>
            <h2 class="text-base font-semibold text-heading">GMB / SEO Checklist - {{ $this->period->label() }}</h2>
            <p class="text-sm text-muted mt-0.5">
                {{ $this->period->status === 'completed' ? 'Archived - view only' : 'Editable - current month' }}
                @unless (auth()->user()->hasRole(['Super Admin', 'Manager', 'Team Lead']))
                    · Showing only your assigned clients
                @endunless
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            @if ($this->pastPeriods->isNotEmpty())
                <select wire:model.live="viewingPeriodId"
                    class="h-9 px-3 rounded-lg border border-border text-sm bg-surface">
                    <option value="">Current Month</option>
                    @foreach ($this->pastPeriods as $p)
                        <option value="{{ $p->id }}">{{ $p->label() }} (Archived)</option>
                    @endforeach
                </select>
            @endif

            @can('manageCategories', \App\Models\GmbClient::class)
                <button wire:click="$set('showPermissionModal', true)"
                    class="h-9 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors">
                    <i class="ph ph-user-gear"></i> Manage Add Permission
                </button>
            @endcan

            @can('addClient', \App\Models\GmbClient::class)
                <button wire:click="openAddModal"
                    class="h-9 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                    <i class="ph ph-plus"></i> Add Client
                </button>
            @endcan

            @can('completeGmbMonth', \App\Models\GmbClient::class)
                @if (!$viewingPeriodId)
                    <button wire:click="completeMonth"
                        onclick="return confirm('Mark this month complete? This locks all entries and starts a fresh sheet for next month.')"
                        class="h-9 px-4 rounded-xl bg-success text-white text-sm font-medium hover:bg-green-600 transition-colors">
                        Mark Month Complete
                    </button>
                @endif
            @endcan
        </div>
    </div>

    <div class="bg-surface overflow-x-auto">
        <div class="relative w-64" style="margin: 20px;">
            <i class="ph ph-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-muted text-sm"></i>

            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Search clients..."
                class="w-full h-8 pl-9 pr-8 rounded-lg border border-border
               bg-surface text-xs text-text
               placeholder:text-muted
               focus:outline-none focus:ring-1 focus:ring-primary" />

            @if ($search)
                <button type="button" wire:click="$set('search', '')"
                    class="absolute right-2 top-1/2 -translate-y-1/2
                   text-muted hover:text-text">
                    <i class="ph ph-x text-xs"></i>
                </button>
            @endif
        </div>
        @if ($this->clients->isEmpty())
            <div class="p-8 text-center text-sm text-muted">No clients found.</div>
        @elseif ($this->categories->isEmpty())
            <div class="p-8 text-center text-sm text-muted">No checklist categories configured yet.</div>
        @else
            <style>
                /* Thicker horizontal scrollbar for the GMB checklist grid */
                .gmb-grid-scroll::-webkit-scrollbar {
                    height: 14px;
                    /* default is usually ~8-10px */
                }

                .gmb-grid-scroll::-webkit-scrollbar-track {
                    background: var(--color-subtle, #f1f2f4);
                    border-radius: 8px;
                }

                .gmb-grid-scroll::-webkit-scrollbar-thumb {
                    background: var(--color-border-strong, #b0b4bb);
                    border-radius: 8px;
                    border: 2px solid var(--color-subtle, #f1f2f4);
                }

                .gmb-grid-scroll::-webkit-scrollbar-thumb:hover {
                    background: var(--color-primary, #d4a24e);
                }

                /* Firefox fallback - only controls thin/auto/none, not exact px, but at least widens it from default */
                .gmb-grid-scroll {
                    scrollbar-width: auto;
                }
            </style>
            <div class="bg-surface border border-border rounded-2xl overflow-auto gmb-grid-scroll"
                style="max-height: 70vh;">

                <table class="text-sm border-collapse min-w-max">
                    <thead>
                        <tr>
                            <th rowspan="2"
                                class="sticky left-0 bg-surface px-4 py-3 border-b border-r border-border text-left z-10"
                                style="background: silver;">
                                Client</th>
                            @foreach ($this->categories as $cat)
                                <th colspan="{{ $cat->subcategories->count() }}"
                                    class="px-2 py-2 text-center text-xs font-semibold border-b border-l border-border"
                                    style="background-color: {{ $cat->color }}1A; color: {{ $cat->color }};">
                                    {{ $cat->name }}
                                </th>
                            @endforeach
                        </tr>
                        <tr>
                            @foreach ($this->categories as $cat)
                                @foreach ($cat->subcategories as $sub)
                                    <th class="px-2 py-2 text-[10px] font-medium border-l border-border whitespace-normal"
                                        style="background-color: {{ $cat->color }}; ">
                                        {{ $sub->name }}
                                    </th>
                                @endforeach
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($this->clients as $client)
                            <tr class="border-t border-border-subtle" wire:key="gmb-client-{{ $client->id }}">
                                <td class="sticky left-0 px-2 py-1 border-r border-border text-[11px] z-10"
                                    style="background-color: {{ $client->performanceColors()['bg'] }}; color: {{ $client->performanceColors()['text'] }};">
                                    {{ $client->name }}
                                </td>
                                @foreach ($this->categories as $cat)
                                    @foreach ($cat->subcategories as $sub)
                                        <td class="px-2 py-3 text-center border-l border-border-subtle">
                                            <label class="inline-flex items-center justify-center cursor-pointer">
                                                <input type="checkbox"
                                                    wire:key="cell-{{ $client->id }}-{{ $sub->id }}-{{ $this->isChecked($client->id, $sub->id) ? '1' : '0' }}"
                                                    @checked($this->isChecked($client->id, $sub->id)) @disabled($this->period->status === 'completed')
                                                    wire:click="toggle({{ $client->id }}, {{ $sub->id }})"
                                                    class="w-3 h-3 rounded-md border-2 border-border-strong text-success
                                                focus:ring-2 focus:ring-success/30 focus:ring-offset-0
                                                checked:bg-success checked:border-success
                                                disabled:opacity-40 disabled:cursor-not-allowed
                                                cursor-pointer transition-all" />
                                            </label>
                                        </td>
                                    @endforeach
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Add client modal --}}
    @if ($showAddModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Add Client</h3>
                    <button wire:click="$set('showAddModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                            class="ph ph-x"></i></button>
                </div>
                <form wire:submit="addClient" class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-muted">Client Name</label>
                        <input type="text" wire:model="newClientName" autofocus
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('newClientName')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showAddModal', false)"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium">Add</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Permission management modal (TL+) --}}
    @if ($showPermissionModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Client-Adding Permission</h3>
                    <button wire:click="$set('showPermissionModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                            class="ph ph-x"></i></button>
                </div>
                <div class="p-5 space-y-2 max-h-[60vh] overflow-y-auto">
                    <p class="text-xs text-muted mb-3">Employees checked below can add clients themselves without asking
                        you.</p>
                    @forelse ($this->gmbEmployees as $emp)
                        <label
                            class="flex items-center justify-between gap-2 px-3 py-2.5 rounded-lg hover:bg-subtle cursor-pointer">
                            <span class="text-sm text-text">{{ $emp->name }}</span>
                            <input type="checkbox"
                                wire:key="perm-{{ $emp->id }}-{{ $emp->can_add_gmb_clients ? '1' : '0' }}"
                                wire:click="toggleEmployeePermission({{ $emp->id }})" @checked($emp->can_add_gmb_clients)
                                class="rounded border-border" />
                        </label>
                    @empty
                        <p class="text-xs text-muted text-center py-6">No employees found in this department.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
