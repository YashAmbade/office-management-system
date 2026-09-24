<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5">
        <h2 class="text-base font-semibold text-heading">Menu Permissions</h2>
        <p class="text-sm text-muted mt-0.5">Control which roles can see each module in the sidebar. Set defaults for everyone, or override per department. Changes apply immediately.</p>
    </div>

    <div class="bg-surface border border-border rounded-2xl p-3 md:p-4">
        <div class="flex items-center gap-1 p-1 rounded-xl bg-subtle w-fit flex-wrap">
            <button
                wire:click="selectScope(null)"
                data-active="{{ $departmentId === null ? 'true' : 'false' }}"
                class="inline-flex items-center gap-1.5 h-8 px-3.5 rounded-lg text-xs font-semibold text-muted hover:text-heading data-[active=true]:bg-surface data-[active=true]:text-primary data-[active=true]:shadow-sm transition-all"
            >
                Default (All Departments)
            </button>
            @foreach($this->departments as $d)
                <button
                    wire:click="selectScope({{ $d->id }})"
                    data-active="{{ $departmentId === $d->id ? 'true' : 'false' }}"
                    class="inline-flex items-center gap-1.5 h-8 px-3.5 rounded-lg text-xs font-semibold text-muted hover:text-heading data-[active=true]:bg-surface data-[active=true]:text-primary data-[active=true]:shadow-sm transition-all"
                >
                    {{ $d->name }}
                </button>
            @endforeach
        </div>

        @if($departmentId !== null)
            <p class="text-xs text-muted mt-2.5">
                <i class="ph ph-info"></i>
                Showing effective settings for <span class="font-medium text-heading">{{ $this->departments->firstWhere('id', $departmentId)?->name }}</span>.
                Toggling a cell creates an override for this department only - other departments keep the default.
            </p>
        @endif
    </div>

    <div class="bg-surface border border-border rounded-2xl overflow-x-auto">
        <table class="w-full text-sm border-collapse min-w-[560px]">
            <thead>
                <tr class="bg-subtle/50 text-left text-xs text-muted">
                    <th class="px-4 py-3 font-medium border-r border-border-subtle">Module</th>
                    @foreach(\App\Models\MenuPermission::roles() as $role)
                        <th class="px-4 py-3 font-medium text-center border-r border-border-subtle last:border-r-0">{{ $role }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($this->grid as $row)
                    @php $item = $row['item']; @endphp

                    {{-- Parent row --}}
                    <tr class="border-t border-border-subtle {{ $row['children']->isNotEmpty() ? 'bg-subtle/30' : '' }}">
                        <td class="px-4 py-3 font-medium text-heading border-r border-border-subtle">
                            {{ $item->label }}
                        </td>
                        @foreach(\App\Models\MenuPermission::roles() as $role)
                            @php
                                $cell = $row['roles'][$role];
                                $locked = $role === 'Super Admin' && in_array($item->key, ['dashboard', 'settings'], true);
                            @endphp
                            <td class="px-4 py-3 text-center border-r border-border-subtle last:border-r-0">
                                <div class="inline-flex items-center gap-1.5">
                                    <input
                                        type="checkbox"
                                        @checked($cell['value'])
                                        @disabled($locked)
                                        wire:click="toggle('{{ $item->key }}', '{{ $role }}')"
                                        title="{{ $locked ? 'Super Admin always keeps this' : '' }}"
                                    />
                                    @if($departmentId !== null && $cell['overridden'])
                                        <button
                                            type="button"
                                            wire:click="resetOverride('{{ $item->key }}', '{{ $role }}')"
                                            class="w-4 h-4 rounded-full bg-hold/20 text-hold flex items-center justify-center flex-shrink-0"
                                            title="Overridden for this department - click to reset to default"
                                        >
                                            <i class="ph ph-arrow-counter-clockwise text-[10px]"></i>
                                        </button>
                                    @endif
                                </div>
                            </td>
                        @endforeach
                    </tr>

                    {{-- Child rows (indented, only if this item has children) --}}
                    @foreach($row['children'] as $childRow)
                        @php $child = $childRow['item']; @endphp
                        <tr class="border-t border-border-subtle">
                            <td class="px-4 py-2.5 pl-8 text-muted border-r border-border-subtle">
                                <i class="ph ph-arrow-elbow-down-right text-xs mr-1.5"></i>{{ $child->label }}
                            </td>
                            @foreach(\App\Models\MenuPermission::roles() as $role)
                                @php
                                    $cell = $childRow['roles'][$role];
                                    $locked = $role === 'Super Admin' && in_array($child->key, ['dashboard', 'settings'], true);
                                @endphp
                                <td class="px-4 py-2.5 text-center border-r border-border-subtle last:border-r-0">
                                    <div class="inline-flex items-center gap-1.5">
                                        <input
                                            type="checkbox"
                                            @checked($cell['value'])
                                            @disabled($locked)
                                            wire:click="toggle('{{ $child->key }}', '{{ $role }}')"
                                            title="{{ $locked ? 'Super Admin always keeps this' : '' }}"
                                        />
                                        @if($departmentId !== null && $cell['overridden'])
                                            <button
                                                type="button"
                                                wire:click="resetOverride('{{ $child->key }}', '{{ $role }}')"
                                                class="w-4 h-4 rounded-full bg-hold/20 text-hold flex items-center justify-center flex-shrink-0"
                                                title="Overridden for this department - click to reset to default"
                                            >
                                                <i class="ph ph-arrow-counter-clockwise text-[10px]"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
