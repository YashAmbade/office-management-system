<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div
        class="bg-surface border border-border rounded-2xl p-4 md:p-5 flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h2 class="text-base font-semibold text-heading">Work Log Clients</h2>
            <p class="text-sm text-muted mt-0.5">Manage which clients each department can log work against.</p>
        </div>
        <div class="flex items-center gap-2">

            @if (auth()->user()->hasRole(['Super Admin', 'Manager']))
            @livewire('work-log-access-management')
                <button wire:click="toggleTrashedView"
                    class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors inline-flex items-center gap-1.5">
                    <i class="ph ph-trash"></i>
                    {{ $showTrashed ? 'Hide Deleted' : 'View Deleted' }}
                </button>
            @endif
            @can('create', \App\Models\WorkLogClient::class)
                <button wire:click="openCreate"
                    class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                    <i class="ph ph-plus"></i> Add Client
                </button>
            @endcan
        </div>
    </div>

    @if ($showTrashed)
        <div class="bg-surface border border-border rounded-2xl overflow-x-auto">
            <div class="px-4 py-3 border-b border-border-subtle">
                <h3 class="text-sm font-semibold text-heading">Deleted Clients</h3>
            </div>
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-muted border-b border-border">
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Company</th>
                        <th class="px-4 py-3 font-medium">Department</th>
                        <th class="px-4 py-3 font-medium">Deleted By</th>
                        <th class="px-4 py-3 font-medium">Deleted At</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($this->trashedClients as $c)
                        <tr wire:key="wlc-trashed-{{ $c->id }}"
                            class="border-b border-border-subtle last:border-0">
                            <td class="px-4 py-3 font-medium text-heading">{{ $c->name }}</td>
                            <td class="px-4 py-3 text-muted">{{ $c->company ?? '-' }}</td>
                            <td class="px-4 py-3 text-muted">{{ $c->department?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-muted">{{ $c->deleter?->name ?? 'Unknown' }}</td>
                            <td class="px-4 py-3 text-muted">{{ $c->deleted_at?->format('M j, Y g:ia') }}</td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="restore({{ $c->id }})"
                                    class="text-xs font-medium text-primary hover:text-primary-strong inline-flex items-center gap-1">
                                    <i class="ph ph-arrow-counter-clockwise"></i> Restore
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-4 py-6 text-center text-muted text-sm">No deleted clients.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    @else
        <div class="bg-surface border border-border rounded-2xl overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="text-left text-xs text-muted border-b border-border">
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Company</th>
                        <th class="px-4 py-3 font-medium">Department</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->clients as $c)
                        <tr wire:key="wlc-{{ $c->id }}"
                            class="border-b border-border-subtle last:border-0 hover:bg-subtle/40">
                            <td class="px-4 py-3 font-medium text-heading">{{ $c->name }}</td>
                            <td class="px-4 py-3 text-muted">{{ $c->company ?? '-' }}</td>
                            <td class="px-4 py-3 text-muted">{{ $c->department?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full {{ $c->is_active ? 'bg-success-soft text-success' : 'bg-subtle text-faint' }} text-[11px] font-medium">
                                    {{ $c->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <div class="inline-flex items-center gap-1">
                                    <button wire:click="openEdit({{ $c->id }})"
                                        class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:text-primary hover:bg-primary/10 transition-colors"
                                        aria-label="Edit">
                                        <i class="ph ph-pencil-simple text-base"></i>
                                    </button>
                                    <button wire:click="delete({{ $c->id }})"
                                        wire:confirm="Delete {{ $c->name }}? This client will be hidden from the log-entry dropdown, but past work log entries referencing them stay intact."
                                        class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:text-danger hover:bg-danger-soft transition-colors"
                                        aria-label="Delete">
                                        <i class="ph ph-trash text-base"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    @if ($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">{{ $editingId ? 'Edit Client' : 'Add Client' }}
                    </h3>
                    <button wire:click="cancel"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>
                <form wire:submit="save" class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-muted">Name</label>
                        <input type="text" wire:model="name"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('name')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-medium text-muted">Company</label>
                        <input type="text" wire:model="company"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                    </div>
                    <div>
                        <label class="text-xs font-medium text-muted">Department</label>
                        <select wire:model="departmentId"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                            <option value="">Select…</option>
                            @foreach ($this->departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                        @error('departmentId')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-medium text-muted">Notes</label>
                        <textarea wire:model="notes" rows="2"
                            class="mt-1 w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
                    </div>
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" wire:model="isActive" />
                        <span class="text-sm text-text">Active</span>
                    </label>
                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cancel"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
