<div>
    @can('generateTasks', \App\Models\Client::class)
        <button wire:click="open" class="h-10 px-4 rounded-xl border border-primary/30 bg-primary-soft text-primary text-sm font-medium hover:bg-primary-soft/80 transition-colors inline-flex items-center gap-1.5">
            <i class="ph ph-magic-wand"></i>Generate This Month's Tasks
        </button>
    @endcan

    @if(session('generatedCount'))
        <div class="mt-2 rounded-xl bg-success-soft text-success text-sm px-3.5 py-2.5">
            {{ session('generatedCount') }} task(s) created and assigned.
        </div>
    @endif

    @if($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-lg bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Generate This Month's Tasks</h3>
                    <button wire:click="$set('showModal', false)" class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle" aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form wire:submit="generate" class="p-5 space-y-4" style="max-height: 75vh; overflow-y: auto;">
                    @if($this->itemsWithRemaining->isEmpty())
                        <div class="text-center py-8">
                            <i class="ph ph-check-circle text-3xl text-success"></i>
                            <p class="text-sm text-muted mt-2">This client's plan is already fully generated for this month.</p>
                        </div>
                    @else
                        <div>
                            <label class="text-xs font-medium text-muted mb-2 block">What to generate</label>
                            <div class="space-y-1.5">
                                @foreach($this->itemsWithRemaining as $item)
                                    <label class="flex items-center justify-between gap-3 p-2.5 rounded-xl border border-border cursor-pointer has-[:checked]:border-primary has-[:checked]:bg-primary-soft/40">
                                        <span class="flex items-center gap-2 text-sm text-text">
                                            <input type="checkbox" wire:model="selectedPlanItemIds" value="{{ $item->id }}" class="rounded" />
                                            {{ $item->platform }} - {{ $item->contentType->name }}
                                        </span>
                                        <span class="text-xs text-muted">{{ $item->remainingThisMonth() }} remaining of {{ $item->quantity }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-muted">Assign To</label>
                            <select wire:model="assigneeId" class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                                <option value="">Select employee…</option>
                                @foreach($this->assignableUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            @error('assigneeId') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-muted">Due Date (applies to all generated tasks)</label>
                            <input type="datetime-local" wire:model="dueDate" class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                            @error('dueDate') <p class="text-xs text-danger mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" wire:click="$set('showModal', false)" class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                            <button type="submit" class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong">Generate Tasks</button>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    @endif
</div>
