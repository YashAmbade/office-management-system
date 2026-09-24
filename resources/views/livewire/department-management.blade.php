<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5 flex items-center justify-between gap-3 flex-wrap bs-cards">
        <div>
            <h2 class="text-base font-semibold text-heading">Departments</h2>
            <p class="text-sm text-muted mt-0.5">Manage your organization's departments</p>
        </div>
        <button wire:click="openCreate" class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
            <i class="ph ph-plus"></i>Add Department
        </button>


    @if($this->departments->isEmpty())
        <div class="bg-surface border border-border rounded-2xl p-8 text-center text-sm text-muted">
            No departments yet.
        </div>
    @else

            <table class="w-full min-w-[560px] text-sm">
                <thead>
                    <tr class="text-left text-xs text-muted border-b border-border">
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Code</th>
                        <th class="px-4 py-3 font-medium">Parent</th>
                        <th class="px-4 py-3 font-medium">Users</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->departments as $dept)
                        <tr wire:key="dept-row-{{ $dept->id }}" class="border-b border-border-subtle last:border-0 hover:bg-subtle/40 transition-colors">
                            <td class="px-4 py-3 font-medium text-heading">{{ $dept->name }}</td>
                            <td class="px-4 py-3 text-muted">{{ $dept->code }}</td>
                            <td class="px-4 py-3 text-muted">{{ $dept->parent?->name ?? '-' }}</td>
                            <td class="px-4 py-3 text-muted">{{ $dept->users_count }}</td>
                            <td class="px-4 py-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $dept->is_active ? 'bg-success-soft text-success' : 'bg-subtle text-faint' }} text-[11px] font-medium">
                                    {{ $dept->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="openEdit({{ $dept->id }})" class="text-muted hover:text-primary" aria-label="Edit">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Create / Edit modal --}}
    @if($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">{{ $editingId ? 'Edit Department' : 'Add Department' }}</h3>
                    <button wire:click="cancel" class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle" aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form wire:submit="save" class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-muted">Name</label>
                        <input type="text" wire:model="name" class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('name') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Code</label>
                        <input type="text" wire:model="code" placeholder="e.g. FIN" class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('code') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Parent Department (optional)</label>
                        <select wire:model="parentDepartmentId" class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                            <option value="">None</option>
                            @foreach($this->parentOptions as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" wire:model="isActive" />
                        <span class="text-sm text-text">Active</span>
                    </label>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cancel" class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit" class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
