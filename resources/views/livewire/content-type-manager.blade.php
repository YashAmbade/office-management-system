<div>
    @can('viewAny', \App\Models\ContentType::class)
        <button wire:click="open" class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors">
            <i class="ph ph-tag"></i> Content Types
        </button>
    @endcan

    @if($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-sm bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Content Types</h3>
                    <button wire:click="$set('showModal', false)" class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle" aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <div class="p-5 space-y-4">
                    @can('toggleTeamLeadAccess', \App\Models\ContentType::class)
                        <label class="flex items-center justify-between gap-3 p-3 rounded-xl bg-subtle/40 cursor-pointer">
                            <span class="text-sm text-text">Allow Team Leads to create new content types</span>
                            <input type="checkbox" wire:click="toggleTlAccess" @checked($this->tlAccessEnabled) />
                        </label>
                    @endcan

                    <ul class="space-y-1.5">
                        @foreach($this->types as $type)
                            <li class="flex items-center justify-between gap-2 text-sm text-text px-3 py-2 rounded-lg bg-subtle/50">
                                <span class="flex items-center gap-2"><i class="ph ph-tag text-muted"></i>{{ $type->name }}</span>
                                @if($type->is_system)
                                    <span class="text-[10px] text-faint uppercase">Default</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                    @can('create', \App\Models\ContentType::class)
                        <form wire:submit="addType" class="flex items-center gap-2 pt-2 border-t border-border-subtle">
                            <input type="text" wire:model="newTypeName" placeholder="e.g. AI Video" class="flex-1 h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                            <button type="submit" class="h-10 px-4 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-strong">Add</button>
                        </form>
                        @error('newTypeName') <p class="text-xs text-danger">{{ $message }}</p> @enderror
                    @endcan
                </div>
            </div>
        </div>
    @endif
</div>
