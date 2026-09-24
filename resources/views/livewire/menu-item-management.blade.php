<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div
        class="bg-surface border border-border rounded-2xl p-4 md:p-5 flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h2 class="text-base font-semibold text-heading">Sidebar Menu Items</h2>
            <p class="text-sm text-muted mt-0.5">Add, edit, reorder, or restrict sidebar links - permissions per
                role/department are set separately.</p>
        </div>
        <button wire:click="openCreate"
            class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
            <i class="ph ph-plus"></i>Add Menu Item
        </button>
    </div>

    <div class="space-y-3">
        @forelse ($this->topLevelItems as $item)
            <div wire:key="menu-item-{{ $item->id }}"
                class="bg-surface border border-border rounded-2xl overflow-hidden {{ !$item->is_active ? 'opacity-50' : '' }}">
                <div
                    class="flex items-center justify-between gap-3 px-4 py-3 bg-subtle/50 border-b border-border-subtle">
                    <div class="flex items-center gap-2 min-w-0">
                        <div class="flex flex-col flex-shrink-0">
                            <button wire:click="moveItem({{ $item->id }}, 'up')"
                                class="text-muted hover:text-heading"><i class="ph ph-caret-up text-xs"></i></button>
                            <button wire:click="moveItem({{ $item->id }}, 'down')"
                                class="text-muted hover:text-heading"><i class="ph ph-caret-down text-xs"></i></button>
                        </div>
                        <i class="ph {{ $item->icon }} text-lg text-muted flex-shrink-0"></i>
                        <div class="min-w-0">
                            <h3 class="text-sm font-semibold text-heading truncate">{{ $item->label }}</h3>
                            <p class="text-[11px] text-muted truncate">
                                key: <code>{{ $item->key }}</code>
                                @if ($item->section)
                                    · section: {{ $item->section }}
                                @endif
                                @if ($item->restrictedDepartment)
                                    · <span class="text-warning">restricted to
                                        {{ $item->restrictedDepartment->name }}</span>
                                @endif
                                @if ($item->route_name)
                                    · route: {{ $item->route_name }}
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-1 flex-shrink-0">

                        <button wire:click="openCreate({{ $item->id }})"
                            class="h-8 px-3 rounded-lg text-xs font-medium text-primary hover:bg-primary-soft transition-colors">
                            <i class="ph ph-plus"></i> Sub-item
                        </button>
                        <button wire:click="toggleActive({{ $item->id }})"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:text-warning"
                            title="{{ $item->is_active ? 'Hide' : 'Show' }}">
                            <i class="ph {{ $item->is_active ? 'ph-eye' : 'ph-eye-slash' }}"></i>
                        </button>
                        <button wire:click="openEdit({{ $item->id }})"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:text-primary"><i
                                class="ph ph-pencil-simple"></i></button>
                        <button wire:click="delete({{ $item->id }})"
                            onclick="return confirm('Delete this menu item{{ $item->children->isNotEmpty() ? ' and all its sub-items' : '' }}? This also removes any related permission settings.')"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:text-danger"><i
                                class="ph ph-trash"></i></button>
                    </div>
                </div>

                @if ($item->children->isNotEmpty())
                    <div class="divide-y divide-border-subtle">
                        @foreach ($item->children as $child)
                            <div wire:key="menu-child-{{ $child->id }}"
                                class="flex items-center justify-between px-4 py-2.5 pl-10 {{ !$child->is_active ? 'opacity-50' : '' }}">
                                <div class="flex items-center gap-2 min-w-0">
                                    <i class="ph {{ $child->icon }} text-sm text-muted flex-shrink-0"></i>
                                    <div class="min-w-0">
                                        <span class="text-sm text-text">{{ $child->label }}</span>
                                        <span class="text-[11px] text-muted ml-1.5">({{ $child->key }} ·
                                            {{ $child->route_name }})</span>
                                    </div>
                                </div>
                                <div class="flex items-center gap-1 flex-shrink-0">
                                    <button wire:click="toggleActive({{ $child->id }})"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-muted hover:text-warning">
                                        <i class="ph {{ $child->is_active ? 'ph-eye' : 'ph-eye-slash' }} text-sm"></i>
                                    </button>
                                    <button wire:click="openEdit({{ $child->id }})"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-muted hover:text-primary"><i
                                            class="ph ph-pencil-simple text-sm"></i></button>
                                    <button wire:click="delete({{ $child->id }})"
                                        onclick="return confirm('Delete this sub-item?')"
                                        class="w-7 h-7 rounded-lg flex items-center justify-center text-muted hover:text-danger"><i
                                            class="ph ph-trash text-sm"></i></button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        @empty
            <div class="bg-surface border border-border rounded-2xl p-8 text-center text-sm text-muted">
                No menu items yet.
            </div>
        @endforelse
    </div>

    {{-- Add/Edit modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">{{ $editingId ? 'Edit' : 'Add' }} Menu Item</h3>
                    <button wire:click="$set('showModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"><i
                            class="ph ph-x"></i></button>
                </div>

                <form wire:submit="save" class="p-5 space-y-4 max-h-[70vh] overflow-y-auto">
                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-muted">Key (unique, code-safe)</label>
                            <input type="text" wire:model="key" placeholder="gmb_checklist"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm font-mono" />
                            @error('key')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <div>
                            <label class="text-xs font-medium text-muted">Label (shown to users)</label>
                            <input type="text" wire:model="label" placeholder="Checklist"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                            @error('label')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-muted">Phosphor Icon Class</label>
                            <input type="text" wire:model="icon" placeholder="ph-check-square"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm font-mono" />
                        </div>
                        <div>
                            <label class="text-xs font-medium text-muted">Route Name</label>
                            <input type="text" wire:model="routeName" placeholder="gmb.checklist"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm font-mono" />
                            <p class="text-[11px] text-muted mt-1">Leave blank for a parent-only item with sub-items.
                            </p>
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Parent Item</label>
                        <select wire:model="parentId"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                            <option value="">- Top-level item -</option>
                            @foreach ($this->parentOptions as $p)
                                @if ($p->id !== $editingId)
                                    <option value="{{ $p->id }}">{{ $p->label }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>

                    @if (!$parentId)
                        <div>
                            <label class="text-xs font-medium text-muted">Sidebar Section Heading</label>
                            <input type="text" wire:model="section" placeholder="GMB / SEO"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                            <p class="text-[11px] text-muted mt-1">Leave blank to group under "Main" with no heading.
                            </p>
                        </div>
                    @endif

                    <div>
                        <label class="text-xs font-medium text-muted">Restrict to Department (optional)</label>
                        <select wire:model="restrictedDepartmentId"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                            <option value="">No restriction - visible to all departments</option>
                            @foreach ($this->departments as $d)
                                <option value="{{ $d->id }}">{{ $d->name }}</option>
                            @endforeach
                        </select>
                        <p class="text-[11px] text-muted mt-1">Super Admin & Manager always bypass this restriction.
                        </p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Visible to Roles (default, all
                            departments)</label>
                        <div class="mt-1.5 space-y-1">
                            @foreach (\App\Models\MenuPermission::roles() as $role)
                                <label
                                    class="flex items-center justify-between px-3 py-2 rounded-lg hover:bg-subtle cursor-pointer">
                                    <span class="text-sm text-text">{{ $role }}</span>
                                    <input type="checkbox" wire:model="roleVisibility.{{ $role }}"
                                        class="rounded border-border" />
                                </label>
                            @endforeach
                        </div>
                        <p class="text-[11px] text-muted mt-1">
                            Need a department-specific exception (e.g. only Marketing's Team Leads)? Use the
                            <a href="{{ route('settings.permissions') }}"
                                class="text-primary hover:underline">Permissions page</a> for that.
                        </p>
                    </div>

                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" wire:model="isActive" class="rounded border-border" />
                        <span class="text-sm text-text">Active (visible in sidebar)</span>
                    </label>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
