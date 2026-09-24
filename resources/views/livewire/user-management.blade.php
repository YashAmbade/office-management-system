<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div
        class="bg-surface border border-border rounded-2xl p-4 md:p-5 flex items-center justify-between gap-3 flex-wrap bs-cards">
        <div>
            <h2 class="text-base font-semibold text-heading">Manage Employees</h2>
            <p class="text-sm text-muted mt-0.5">View and update team member profiles and roles</p>
        </div>
        <div class="flex items-center gap-2">
            @if (auth()->user()->hasRole(['Super Admin', 'Manager']))
                <button wire:click="openRoleModal"
                    class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors">
                    <i class="ph ph-tag"></i> Manage Roles
                </button>
            @endif
            <button wire:click="openCreateModal"
                class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                <i class="ph ph-plus"></i>Add User
            </button>
        </div>


        @if ($this->manageableUsers->isEmpty())
            <div class="bg-surface border border-border rounded-2xl p-8 text-center text-sm text-muted">
                No users to manage yet.
            </div>
        @else
            <table class="w-full min-w-[640px] text-sm mt-6">
                <thead>
                    <tr class="text-left text-xs text-muted border-b border-border">
                        <th class="px-4 py-3 font-medium">Emp Code</th>
                        <th class="px-4 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Email</th>
                        <th class="px-4 py-3 font-medium">Role</th>
                        <th class="px-4 py-3 font-medium">Department</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->manageableUsers as $u)
                        <tr wire:key="user-row-{{ $u->id }}"
                            class="border-b border-border-subtle last:border-0 hover:bg-subtle/40 transition-colors">
                            <td class="px-4 py-3 text-muted">{{ $u->employee_code }}</td>
                            <td class="px-4 py-3 flex items-center gap-2.5">
                                <span
                                    class="w-8 h-8 rounded-full bg-primary/10 text-primary text-[11px] font-semibold flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($u->name, 0, 2)) }}
                                </span>
                                <span class="font-medium text-heading">{{ $u->name }}</span>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $u->email }}</td>

                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full bg-primary-soft text-primary text-[11px] font-medium">
                                    {{ $u->roles->first()?->name ?? '—' }}
                                </span>
                                @if ($u->auto_approve_tasks)
                                    <span
                                        class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full bg-success-soft text-success text-[11px] font-medium ml-1"
                                        title="Trusted employee">
                                        <i class="ph ph-shield-check"></i>Auto-Approve
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $u->department?->name ?? '-' }}</td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full {{ $u->is_active ? 'bg-success-soft text-success' : 'bg-subtle text-faint' }} text-[11px] font-medium">
                                    {{ $u->is_active ? 'Active' : 'Inactive' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-right">
                                <button wire:click="edit({{ $u->id }})" class="text-muted hover:text-primary"
                                    aria-label="Edit">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
    </div>
    @endif

    {{-- Edit modal --}}
    @if ($editingUserId)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-lg bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Edit User</h3>
                    <button wire:click="cancelEdit"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form wire:submit="save" class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div>
                        <label class="text-xs font-medium text-muted">Name</label>
                        <input type="text" wire:model="editName"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('editName')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Email</label>
                        <input type="email" wire:model="editEmail"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('editEmail')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Phone</label>
                        <input type="tel" wire:model="editPhone"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-muted">Role</label>
                            <select wire:model="editRole"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                                @foreach ($this->assignableRoles as $role)
                                    <option value="{{ $role }}">{{ $role }}</option>
                                @endforeach
                            </select>
                            @error('editRole')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium text-muted">Department</label>
                            <select wire:model="editDepartmentId"
                                @if (! auth()->user()->hasRole('Super Admin')) disabled @endif
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm disabled:bg-subtle disabled:cursor-not-allowed">
                                <option value="">Select…</option>
                                @foreach ($this->departments as $d)
                                    <option value="{{ $d->id }}">{{ $d->name }}</option>
                                @endforeach
                            </select>
                            @error('editDepartmentId')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" wire:model="editIsActive" />
                        <span class="text-sm text-text">Active</span>
                    </label>

                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" :checked="@js($editAutoApprove)" wire:click="toggleAutoApprove"
                            class="rounded border-border text-primary focus:ring-primary">
                        <span class="text-sm text-body">Auto-approve completed tasks</span>
                    </label>

                    <div class="pt-3 border-t border-border-subtle">
                        <label class="text-xs font-medium text-muted">Reset Password (optional)</label>
                        <input type="password" wire:model="editNewPassword"
                            placeholder="Leave blank to keep current password"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('editNewPassword')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cancelEdit"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong">Save
                            Changes</button>
                    </div>
                </form>
            </div>

            @if ($showAutoApproveConfirm)
                <div class="absolute inset-0 z-10 flex items-center justify-center bg-overlay p-4">
                    <div class="w-full max-w-xs bg-surface rounded-2xl border border-border shadow-2xl" style="max-width: 440px; padding-bottom: 10px;">
                        <div class="flex items-center gap-3 px-5 h-14 border-b border-border-subtle">
                            <i class="ph ph-warning-circle text-warning text-xl"></i>
                            <h3 class="text-base font-semibold text-heading">Confirm Auto-Approve</h3>
                        </div>

                        <div class="p-5">
                            <p class="text-sm text-body leading-relaxed">
                                Tasks marked as completed by this employee will be considered
                                <span class="font-medium text-heading">final and automatically approved</span> -
                                no manager/teamlead review will be required. Are you sure you want to enable this?
                            </p>
                        </div>

                        <div class="flex items-center justify-end gap-2 px-5 pb-5">
                            <button wire:click="cancelAutoApproveConfirm"
                                class="px-4 py-2 rounded-lg text-sm font-medium text-muted hover:bg-subtle transition-colors">
                                Cancel
                            </button>
                            <button wire:click="confirmAutoApprove"
                                class="px-4 py-2 rounded-lg text-sm font-medium bg-danger text-white hover:bg-danger/90 transition-colors">
                                Yes, Enable
                            </button>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    @endif

    {{-- Create User modal --}}
    @if ($showCreateModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
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
                        <p class="text-[11px] text-muted mt-1">Auto-assigned - next available code.</p>
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

    {{-- Manage Roles modal (Super Admin only) --}}
    @if ($showRoleModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-sm bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Manage Roles</h3>
                    <button wire:click="$set('showRoleModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    <ul class="space-y-1.5">
                        @foreach ($this->allRoles as $role)
                            <li class="flex items-center gap-2 text-sm text-text px-3 py-2 rounded-lg bg-subtle/50">
                                <i class="ph ph-tag text-muted"></i>{{ $role->name }}
                            </li>
                        @endforeach
                    </ul>

                    <form wire:submit="addRole" class="flex items-center gap-2 pt-2 border-t border-border-subtle">
                        <input type="text" wire:model="newRoleName" placeholder="New role name…"
                            class="flex-1 h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        <button type="submit"
                            class="h-10 px-4 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-strong">Add</button>
                    </form>
                    @error('newRoleName')
                        <p class="text-xs text-danger">{{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>
    @endif

    {{-- Manage Departments modal (Super Admin only) --}}
    @if ($showDepartmentModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-sm bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Manage Departments</h3>
                    <button wire:click="$set('showDepartmentModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    <ul class="space-y-1.5">
                        @foreach ($this->allDepartments as $dept)
                            <li
                                class="flex items-center justify-between gap-2 text-sm text-text px-3 py-2 rounded-lg bg-subtle/50">
                                <span class="flex items-center gap-2"><i
                                        class="ph ph-buildings text-muted"></i>{{ $dept->name }}</span>
                                <span class="text-[11px] text-muted">{{ $dept->code }}</span>
                            </li>
                        @endforeach
                    </ul>

                    <form wire:submit="addDepartment" class="space-y-2 pt-2 border-t border-border-subtle">
                        <input type="text" wire:model="newDepartmentName" placeholder="Department name…"
                            class="w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('newDepartmentName')
                            <p class="text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <input type="text" wire:model="newDepartmentCode" placeholder="Short code (e.g. FIN)"
                            class="w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('newDepartmentCode')
                            <p class="text-xs text-danger">{{ $message }}</p>
                        @enderror

                        <button type="submit"
                            class="w-full h-10 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-strong">Add
                            Department</button>
                    </form>
                </div>
            </div>
        </div>
    @endif

</div>
