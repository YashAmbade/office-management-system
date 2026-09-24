<div class="p-3 md:p-5 space-y-4" x-data="{ toast: '' }"
    x-on:gmb-bulk-added.window="toast = $event.detail.message; setTimeout(() => toast = '', 6000)"
    x-on:gmb-bulk-deleted.window="toast = $event.detail.message; setTimeout(() => toast = '', 6000)"
    x-on:gmb-client-deleted.window="toast = 'Client deleted.'; setTimeout(() => toast = '', 6000)">

    <div
        class="bg-surface border border-border rounded-2xl p-4 md:p-5 flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h2 class="text-base font-semibold text-heading">GMB Clients</h2>
            <p class="text-sm text-muted mt-0.5">Clients tracked on the monthly checklist</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            @can('manageCategories', \App\Models\GmbClient::class)
                <button wire:click="openPermissionModal"
                    class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors">
                    <i class="ph ph-user-gear"></i> Manage Add Permission
                </button>
            @endcan
            @can('addClient', \App\Models\GmbClient::class)
                <button wire:click="openBulkModal"
                    class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors">
                    <i class="ph ph-upload-simple"></i> Bulk Add
                </button>
                <button wire:click="openAddModal"
                    class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                    <i class="ph ph-plus"></i>Add Client
                </button>
            @endcan
        </div>
    </div>

    {{-- Toast --}}
    <div x-show="toast" x-cloak x-transition class="bg-success-soft text-success text-sm rounded-xl p-3" x-text="toast">
    </div>

    {{-- Performance legend --}}
    <div class="bg-surface border border-border rounded-2xl p-4 flex items-center gap-4 flex-wrap text-xs">
        <span class="font-medium text-muted">Performance:</span>
        @foreach (\App\Models\GmbClient::performanceLevels() as $key => $level)
            <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium"
                style="background-color: {{ $level['bg'] }}; color: {{ $level['text'] }};">
                {{ $level['label'] }}
            </span>
        @endforeach
    </div>

    {{-- Bulk delete bar --}}
    @can('deleteClient', \App\Models\GmbClient::class)
        @if (!empty($selectedClients))
            <div class="bg-danger-soft border border-danger/30 rounded-2xl p-3 flex items-center justify-between gap-3 flex-wrap">
                <span class="text-sm text-danger font-medium">{{ count($selectedClients) }} selected</span>
                <div class="flex items-center gap-2">
                    <button wire:click="$set('selectedClients', [])"
                        class="h-9 px-3 rounded-xl border border-border text-sm font-medium text-text-secondary">
                        Clear
                    </button>
                    <button wire:click="bulkDeleteClients"
                        onclick="return confirm('Delete {{ count($selectedClients) }} client(s)? They will be removed from the checklist and client list.')"
                        class="h-9 px-4 rounded-xl bg-danger text-white text-sm font-medium hover:bg-red-600 transition-colors">
                        <i class="ph ph-trash"></i> Delete Selected
                    </button>
                </div>
            </div>
        @endif
    @endcan

    <div class="bg-surface border border-border rounded-2xl overflow-hidden">
        @if ($this->clients->isEmpty())
            <div class="p-8 text-center text-sm text-muted">No clients yet.</div>
        @else
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-muted border-b border-border">
                        @can('deleteClient', \App\Models\GmbClient::class)
                            <th class="px-4 py-3 font-medium w-8">
                                <input type="checkbox" wire:model.live="selectAll" class="rounded border-border" />
                            </th>
                        @endcan
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Assigned Employee</th>
                        <th class="px-4 py-3 font-medium">Performance</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Added By</th>
                        <th class="px-4 py-3 font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->clients as $client)
                        <tr wire:key="gmb-client-{{ $client->id }}"
                            class="border-b border-border-subtle last:border-0 hover:bg-subtle/40 transition-colors">
                            @can('deleteClient', \App\Models\GmbClient::class)
                                <td class="px-4 py-3">
                                    <input type="checkbox" wire:model.live="selectedClients"
                                        value="{{ $client->id }}" class="rounded border-border" />
                                </td>
                            @endcan
                            <td class="px-4 py-3 font-medium text-heading">{{ $client->name }}</td>
                            <td class="px-4 py-3 text-muted">{{ $client->assignee?->name ?? '- Unassigned -' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-medium"
                                    style="background-color: {{ $client->performanceColors()['bg'] }}; color: {{ $client->performanceColors()['text'] }};">
                                    {{ $client->performanceLabel() }}
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="px-2 py-0.5 rounded-full text-[11px] font-medium {{ $client->is_active ? 'bg-success-soft text-success' : 'bg-subtle text-faint' }}">
                                    {{ $client->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $client->creator?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-right whitespace-nowrap">
                                @can('manageCategories', \App\Models\GmbClient::class)
                                    <button wire:click="openEditClient({{ $client->id }})"
                                        class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:text-primary hover:bg-primary-soft transition-colors"
                                        title="Edit" aria-label="Edit client">
                                        <i class="ph ph-pencil-simple text-base"></i>
                                    </button>

                                    @if ($client->is_active)
                                        <button wire:click="deactivateClient({{ $client->id }})"
                                            class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:text-danger hover:bg-danger-soft transition-colors"
                                            title="Deactivate" aria-label="Deactivate client">
                                            <i class="ph ph-eye-slash text-base"></i>
                                        </button>
                                    @else
                                        <button wire:click="reactivateClient({{ $client->id }})"
                                            class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:text-success hover:bg-success-soft transition-colors"
                                            title="Reactivate" aria-label="Reactivate client">
                                            <i class="ph ph-eye text-base"></i>
                                        </button>
                                    @endif
                                @endcan
                                @can('deleteClient', \App\Models\GmbClient::class)
                                    <button wire:click="deleteClient({{ $client->id }})"
                                        onclick="return confirm('Delete {{ $client->name }}? It will be removed from the checklist and client list.')"
                                        class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:text-danger hover:bg-danger-soft transition-colors"
                                        title="Delete" aria-label="Delete client">
                                        <i class="ph ph-trash text-base"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

    {{-- Single Add modal --}}
    {{-- Single Add modal --}}
@if ($showAddModal)
    <div class="fixed inset-y-0 right-0 left-0 lg:left-64 z-[70] flex items-center justify-center bg-overlay p-4">
        <div class="w-full max-w-xs bg-surface rounded-2xl border border-border shadow-2xl">
            <div class="px-5 h-14 flex items-center justify-between border-b border-border-subtle">
                <h3 class="text-base font-semibold text-heading">Add Client</h3>
                <button wire:click="$set('showAddModal', false)"
                    class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                        class="ph ph-x"></i></button>
            </div>
            <form wire:submit="addClient" class="p-5 space-y-4">
                <div>
                    <label class="text-xs font-medium text-muted">Client Name</label>
                    <input type="text" wire:model="newClientName"
                        class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                    @error('newClientName')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                @unless (auth()->user()->hasRole(['Team Lead', 'Employee']) && !auth()->user()->hasRole(['Super Admin', 'Manager']))
                    <div>
                        <label class="text-xs font-medium text-muted">Assigned Employee</label>
                        <select wire:model="newClientAssignedTo"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                            <option value="">- Unassigned -</option>
                            @foreach ($this->assignableUsers as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>
                @else
                    <p class="text-[11px] text-muted">This client will be assigned to you automatically.</p>
                @endunless

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

    {{-- Bulk Add modal --}}
    @if ($showBulkModal)
         <div class="fixed inset-y-0 right-0 left-0 lg:left-64 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-xs bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="px-5 h-14 flex items-center justify-between border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Bulk Add Clients</h3>
                    <button wire:click="$set('showBulkModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                            class="ph ph-x"></i></button>
                </div>
                <form wire:submit="bulkAddClients" class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-muted">One client name per line</label>
                        <textarea wire:model="bulkClientNames" rows="8" placeholder="Acme Dental&#10;Rani Salon&#10;Downtown Cafe"
                            class="mt-1 w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm font-mono"></textarea>
                        @error('bulkClientNames')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-muted mt-1">Duplicate names (already existing) will be skipped
                            automatically.</p>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showBulkModal', false)"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium">Add All</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Edit modal --}}
    @if ($showEditModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Edit Client</h3>
                    <button wire:click="$set('showEditModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                            class="ph ph-x"></i></button>
                </div>
                <form wire:submit="saveEditClient" class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-muted">Client Name</label>
                        <input type="text" wire:model="editName"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('editName')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Performance</label>
                        <select wire:model="editStatus"
                            style="background-color: {{ \App\Models\GmbClient::performanceLevels()[$editStatus]['bg'] ?? '#fff' }}; color: {{ \App\Models\GmbClient::performanceLevels()[$editStatus]['text'] ?? '#000' }};"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border text-sm font-medium">
                            @foreach (\App\Models\GmbClient::performanceLevels() as $key => $level)
                                <option value="{{ $key }}"
                                    style="background-color: {{ $level['bg'] }}; color: {{ $level['text'] }};">
                                    {{ $level['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Assigned Employee</label>
                        <select wire:model="editAssignedTo"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                            <option value="">- Unassigned -</option>
                            @foreach ($this->assignableUsers as $emp)
                                <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" wire:click="$set('showEditModal', false)"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium">Save
                            Changes</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- Permission modal --}}
    @if ($showPermissionModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Client-Adding Permission</h3>
                    <button wire:click="$set('showPermissionModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                            class="ph ph-x"></i></button>
                </div>
                <div class="p-5 space-y-2 max-h-[50vh] overflow-y-auto">
                    <p class="text-xs text-muted mb-3">Employees checked below can add clients themselves without
                        asking you.</p>
                    @forelse ($this->gmbEmployees as $emp)
                        <label wire:key="gmb-perm-row-{{ $emp->id }}"
                            class="flex items-center justify-between gap-2 px-3 py-2 rounded-lg hover:bg-subtle cursor-pointer">
                            <span class="text-sm text-text">{{ $emp->name }}</span>
                            <input type="checkbox" wire:model.live="employeePermissions.{{ $emp->id }}"
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