<div>
    @can('manage', \App\Models\WorkLogClient::class)
        <button wire:click="open"
            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors">
            <i class="ph ph-toggle-right"></i> Manage Access
        </button>
    @endcan

    @if ($showModal && !$showCreateModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Work Log Access</h3>
                    <div class="flex items-center gap-1">
                        <button wire:click="openCreateModal"
                            class="h-8 px-3 rounded-lg bg-primary text-white text-xs font-medium hover:bg-primary-strong transition-colors inline-flex items-center gap-1">
                            <i class="ph ph-plus"></i> Add User
                        </button>
                        <button wire:click="$set('showModal', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                            aria-label="Close">
                            <i class="ph ph-x text-lg"></i>
                        </button>
                    </div>
                </div>

                <div class="p-5 space-y-1.5" style="max-height: 70vh; overflow-y: auto;">
                    <p class="text-xs text-muted mb-3">Choose which employees/team leads are approved to add new Work
                        Log Clients.</p>

                    @foreach ($this->toggleableUsers as $u)
                        <label
                            class="flex items-center justify-between gap-3 p-2.5 rounded-xl bg-subtle/40 cursor-pointer">
                            <span class="flex items-center gap-2.5 min-w-0">
                                <span
                                    class="w-8 h-8 rounded-full bg-primary/10 text-primary text-[11px] font-semibold flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </span>
                                <span class="min-w-0">
                                    <span class="text-sm text-text block truncate">{{ $u->name }}</span>
                                    <span class="text-[11px] text-muted">{{ $u->department?->name ?? '-' }}</span>
                                </span>
                            </span>
                            <input type="checkbox" wire:click="toggleAddClientAccess({{ $u->id }})"
                                @checked($u->can_add_work_log_clients) />
                        </label>
                    @endforeach

                    @if ($this->toggleableUsers->isEmpty())
                        <p class="text-sm text-muted text-center py-6">No employees or team leads yet.</p>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- Quick Add User modal --}}
    @if ($showCreateModal)
        <div class="fixed inset-0 z-[80] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-lg bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Add New User</h3>
                    <button wire:click="cancelCreate"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form wire:submit="createUser" class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label class="text-xs font-medium text-muted">Employee Code</label>
                        <input type="text" wire:model="createEmployeeCode" readonly
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-subtle text-sm text-muted cursor-not-allowed" />
                        <p class="text-[11px] text-muted mt-1">Auto-assigned — next available code.</p>
                        @error('createEmployeeCode')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Name</label>
                        <input type="text" wire:model="createName"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('createName')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Email</label>
                        <input type="email" wire:model="createEmail"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('createEmail')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Phone</label>
                        <input type="tel" wire:model="createPhone"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-muted">Role</label>
                            <select wire:model="createRole"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                                <option value="">Select…</option>
                                @foreach ($this->assignableRoles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                            @error('createRole')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium text-muted">Department</label>
                            <select wire:model="createDepartmentId" @if ($this->departments->count() <= 1) disabled @endif
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm disabled:bg-subtle disabled:cursor-not-allowed">
                                <option value="">Select…</option>
                                @foreach ($this->departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('createDepartmentId')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Temporary Password</label>
                        <input type="password" wire:model="createPassword" placeholder="Min. 8 characters"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('createPassword')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cancelCreate"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong">Create
                            User</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
