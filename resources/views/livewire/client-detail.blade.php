<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm">
        <a aria-label="Back"
            class="w-8 h-8 -ml-1 rounded-lg flex items-center justify-center text-muted hover:bg-subtle transition-colors"
            href="{{ route('clients.index') }}">
            <i class="ph ph-arrow-left text-lg"></i>
        </a>
        <a class="text-muted hover:text-primary transition-colors" href="{{ route('clients.index') }}">Clients</a>
        <i class="ph ph-caret-right text-xs text-faint"></i>
        <span class="text-heading font-medium">{{ $client->name }}</span>
    </div>

    {{-- ==================================================== --}}
    {{-- Hero banner                                           --}}
    {{-- ==================================================== --}}
    <div @class([
        'relative rounded-2xl overflow-hidden text-white',
        'bg-active' => $client->status === 'active',
        'bg-hold' => $client->status === 'hold',
        'bg-inactive' => $client->status === 'inactive',
    ])>
        {{-- decorative pattern layer --}}
        <div class="absolute inset-0 opacity-10 pointer-events-none"></div>

        <div class="relative p-5 md:p-6 bs-cards">
            <div class="flex flex-wrap items-start justify-between gap-4">
                <div class="flex items-center gap-4 min-w-0">
                    <span
                        class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-md flex items-center justify-center flex-shrink-0 font-bold text-2xl">
                        {{ strtoupper(substr($client->name, 0, 2)) }}
                    </span>
                    <div class="min-w-0">
                        <div
                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full bg-white/20 text-[11px] font-medium uppercase tracking-wide mb-1.5">
                            <span class="w-1.5 h-1.5 rounded-full bg-white"></span>{{ ucfirst($client->status) }} Client
                        </div>
                        <h1 class="text-xl md:text-2xl font-bold truncate">{{ $client->name }}</h1>
                        <p class="text-white/80 text-sm mt-0.5 truncate">{{ $client->company ?? 'No company listed' }}
                        </p>
                    </div>
                </div>

                @can('manage', $client)
                    @if (!$editMode)
                        <button wire:click="enterEditMode" style="display: none;"
                            class="h-10 px-4 rounded-xl bg-white/20 backdrop-blur-md text-sm font-medium hover:bg-white/30 transition-colors inline-flex items-center gap-1.5 flex-shrink-0">
                            <i class="ph ph-pencil-simple"></i>Edit Client
                        </button>
                    @endif
                @endcan
            </div>

            {{-- Stat strip --}}
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mt-6">
                <div class="bg-white/15 backdrop-blur-md rounded-xl p-3">
                    <p class="text-[11px] text-white/70 flex items-center gap-1"><i
                            class="ph ph-buildings"></i>Department</p>
                    <p class="text-sm font-semibold mt-1 truncate">{{ $client->department?->name ?? '-' }}</p>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-xl p-3">
                    <p class="text-[11px] text-white/70 flex items-center gap-1"><i
                            class="ph ph-calendar-blank"></i>Engagement</p>
                    <p class="text-sm font-semibold mt-1">
                        @if ($client->start_date)
                            {{ (int) $client->start_date->diffInDays($client->end_date ?? now()) }} days
                        @else
                            -
                        @endif
                    </p>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-xl p-3">
                    <p class="text-[11px] text-white/70 flex items-center gap-1"><i
                            class="ph ph-broadcast"></i>Platforms</p>
                    <p class="text-sm font-semibold mt-1">{{ $client->planItems->count() }} active</p>
                </div>
                <div class="bg-white/15 backdrop-blur-md rounded-xl p-3">
                    <p class="text-[11px] text-white/70 flex items-center gap-1"><i
                            class="ph ph-squares-four"></i>Services</p>
                    <p class="text-sm font-semibold mt-1">{{ $this->attachedServices->count() }} selected</p>
                </div>
            </div>
        </div>
    </div>

    @if ($editMode)
        {{-- ==================================================== --}}
        {{-- Edit mode - full width form                          --}}
        {{-- ==================================================== --}}
        <div class="bg-surface rounded-2xl p-4 md:p-5 bs-cards">
            <div class="flex items-center justify-between gap-3 mb-4">
                <h3 class="text-base font-semibold text-heading flex items-center gap-2">
                    <i class="ph ph-pencil-simple"></i>Edit Client
                </h3>
                <div class="flex items-center gap-2">
                    <button wire:click="cancelEdit"
                        class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary hover:bg-subtle transition-colors">Cancel</button>
                    <button wire:click="save"
                        class="h-9 px-4 rounded-lg bg-primary text-white text-xs font-medium hover:bg-primary-strong transition-colors">Save
                        Changes</button>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                <div>
                    <label class="text-xs font-medium text-muted">Client Name</label>
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
                    <label class="text-xs font-medium text-muted">Email</label>
                    <input type="email" wire:model="email"
                        class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                    @error('email')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div>
                    <label class="text-xs font-medium text-muted">Phone</label>
                    <input type="tel" wire:model="phone"
                        class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 1rem; margin-top: 1rem;">
                <div>
                    <label class="text-xs font-medium text-muted">Status</label>
                    <select wire:model="status"
                        class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                        <option value="active">Active</option>
                        <option value="hold">On Hold</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>
                <div>
                    <label class="text-xs font-medium text-muted">Department</label>
                    <select wire:model="departmentId" @if ($this->departments->count() <= 1) disabled @endif
                        class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm disabled:bg-subtle disabled:cursor-not-allowed">
                        @foreach ($this->departments as $d)
                            <option value="{{ $d->id }}">{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('departmentId')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <div style="display: flex; align-items: flex-end; padding-bottom: 0.625rem;">
                    <label class="flex items-center gap-2 cursor-pointer select-none">
                        <input type="checkbox" wire:model="isPriority" />
                        <span class="text-sm text-text flex items-center gap-1"><i
                                class="ph ph-star text-warning"></i>Priority</span>
                    </label>
                </div>
            </div>

            <div x-data="{
                start: '{{ $startDate }}',
                end: '{{ $endDate }}',
                get days() {
                    if (!this.start || !this.end) return null;
                    let diff = Math.round((new Date(this.end) - new Date(this.start)) / (1000 * 60 * 60 * 24));
                    return diff >= 0 ? diff : null;
                }
            }" class="mt-4">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem;">
                    <div>
                        <label class="text-xs font-medium text-muted">Start Date</label>
                        <input type="date" wire:model="startDate" x-on:input="start = $event.target.value"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('startDate')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                    <div>
                        <label class="text-xs font-medium text-muted">End Date</label>
                        <input type="date" wire:model="endDate" x-on:input="end = $event.target.value"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('endDate')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p x-show="days !== null" x-cloak class="text-xs text-muted mt-1.5 flex items-center gap-1">
                    <i class="ph ph-clock"></i> Engagement length: <span class="font-medium text-text"
                        x-text="days"></span> day<span x-show="days !== 1">s</span>
                </p>
            </div>

            <div class="mt-4">
                <label class="text-xs font-medium text-muted">Notes</label>
                <textarea wire:model="notes" rows="2"
                    class="mt-1 w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
            </div>

            {{-- Delivery Plan --}}
            <div class="mt-5 pt-5 border-t border-border-subtle">
                <div class="flex items-center justify-between mb-3">
                    <label class="text-xs font-medium text-muted flex items-center gap-1.5"><i
                            class="ph ph-chart-bar"></i>Delivery Plan</label>
                    <button type="button" wire:click="addPlanItem"
                        class="text-xs text-primary font-medium hover:text-primary-strong">+ Add platform</button>
                </div>
                <div class="space-y-1.5">
                    @foreach ($planItems as $index => $item)
                        <div style="display: grid; grid-template-columns: 1.2fr 80px 80px 1.6fr auto auto; gap: 0.5rem; align-items: center;"
                            wire:key="edit-plan-item-{{ $index }}">
                            <select wire:model="planItems.{{ $index }}.platform"
                                class="h-9 px-2 rounded-lg border border-border bg-surface text-xs">
                                <option value="">Platform…</option>
                                @foreach ($this->platformOptions as $option)
                                    <option value="{{ $option }}">{{ $option }}</option>
                                @endforeach
                            </select>
                            <input type="number" min="0"
                                wire:model="planItems.{{ $index }}.reels_count" placeholder="Reels"
                                class="h-6 px-2 rounded-lg border border-border bg-surface text-xs w-full" />
                            <input type="number" min="0"
                                wire:model="planItems.{{ $index }}.posts_count" placeholder="Posts"
                                class="h-6 px-2 rounded-lg border border-border bg-surface text-xs w-full" />
                            <input type="text" wire:model="planItems.{{ $index }}.notes"
                                placeholder="Notes"
                                class="h-6 px-2 rounded-lg border border-border bg-surface text-xs w-full" />
                            <label
                                class="flex items-center justify-center h-6 w-9 rounded-lg border border-border cursor-pointer"
                                title="Setup Done">
                                <input type="checkbox" wire:model="planItems.{{ $index }}.setup_done"
                                    class="rounded" />
                            </label>
                            @if (count($planItems) > 1)
                                <button type="button" wire:click="removePlanItem({{ $index }})"
                                    class="h-9 w-9 flex items-center justify-center text-muted hover:text-danger flex-shrink-0"
                                    aria-label="Remove">
                                    <i class="ph ph-trash text-sm"></i>
                                </button>
                            @else
                                <span></span>
                            @endif
                        </div>
                    @endforeach
                </div>
                <p class="text-[10px] text-muted mt-2">Platform · Reels · Posts · Notes · Setup ✓</p>
            </div>
        </div>
    @else
        {{-- ==================================================== --}}
        {{-- Read view - two-column layout                        --}}
        {{-- ==================================================== --}}
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 items-start ">
            {{-- Left / main column --}}
            <div class="lg:col-span-2 space-y-4">

                {{-- Delivery Plan - highlighted --}}
                <div class="bg-surface border-primary/30 rounded-2xl p-5 md:p-6 relative overflow-hidden bs-cards">
                    <div class="absolute top-0 left-0 w-2 h-full bg-primary"></div>

                    <div class="flex items-center justify-between mb-5 pl-2">
                        <h3 class="text-lg font-bold text-heading flex items-center gap-3">
                            <span
                                class="w-11 h-11 rounded-xl bg-primary text-white flex items-center justify-center"><i
                                    class="ph ph-chart-bar text-xl"></i></span>
                            Delivery Plan
                        </h3>
                        @if ($client->planItems->isNotEmpty())
                            <span class="text-sm font-semibold text-primary bg-primary-soft px-3 py-1.5 rounded-full">
                                {{ $client->planItems->count() }}
                                platform{{ $client->planItems->count() !== 1 ? 's' : '' }}
                            </span>
                        @endif
                    </div>

                    @if ($client->planItems->isNotEmpty())
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pl-2">
                            @foreach ($client->planItems->load('contentType')->groupBy('platform') as $platform => $items)
                                @php
                                    $platformIcon = match (true) {
                                        str_contains(strtolower($platform), 'facebook') => 'ph-facebook-logo',
                                        str_contains(strtolower($platform), 'instagram') => 'ph-instagram-logo',
                                        str_contains(strtolower($platform), 'linkedin') => 'ph-linkedin-logo',
                                        str_contains(strtolower($platform), 'youtube') => 'ph-youtube-logo',
                                        str_contains(strtolower($platform), 'tiktok') => 'ph-tiktok-logo',
                                        str_contains(strtolower($platform), 'twitter'),
                                        str_contains(strtolower($platform), 'x (')
                                            => 'ph-x-logo',
                                        str_contains(strtolower($platform), 'pinterest') => 'ph-pinterest-logo',
                                        default => 'ph-broadcast',
                                    };
                                @endphp
                                <div class="rounded-2xl border-2 border-border bg-subtle/20 p-4">
                                    <div class="flex items-center gap-2.5 mb-4">
                                        <span
                                            class="w-10 h-10 rounded-xl bg-surface border border-border flex items-center justify-center text-primary flex-shrink-0">
                                            <i class="ph {{ $platformIcon }} text-xl"></i>
                                        </span>
                                        <p class="text-base font-bold text-heading">{{ $platform }}</p>
                                    </div>

                                    <div class="space-y-4">
                                        @foreach ($items as $item)
                                            @php
                                                $done = $client->completedCountFor($platform, $item->content_type_id);
                                                $total = max((int) $item->quantity, 0);
                                                $pct = $total > 0 ? min(100, round(($done / $total) * 100)) : 0;
                                                $icon = match (strtolower($item->contentType->name)) {
                                                    'reel' => 'ph-film-strip',
                                                    'post' => 'ph-image',
                                                    default => 'ph-sparkle',
                                                };
                                            @endphp
                                            <div>
                                                <div class="flex items-center justify-between mb-1.5">
                                                    <span
                                                        class="text-sm text-muted font-medium flex items-center gap-1.5"><i
                                                            class="ph {{ $icon }} text-base"></i>{{ $item->contentType->name }}</span>
                                                    <span
                                                        class="text-lg font-bold text-heading">{{ $done }}<span
                                                            class="text-sm font-medium text-muted">/{{ $total }}</span></span>
                                                </div>
                                                <div class="h-2 rounded-full bg-subtle overflow-hidden">
                                                    <div class="h-full bg-primary rounded-full transition-all"
                                                        style="width: {{ max($pct, 4) }}%"></div>
                                                </div>
                                                @if ($item->notes)
                                                    <p class="text-xs text-muted mt-1 italic">{{ $item->notes }}</p>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-10 pl-2">
                            <i class="ph ph-chart-bar text-4xl text-faint"></i>
                            <p class="text-sm text-muted mt-2">No delivery plan set for this client yet.</p>
                        </div>
                    @endif
                </div>

                {{-- Services --}}
                <div class="bg-surface border border-border rounded-2xl p-4 md:p-5 bs-cards">
                    <h3 class="text-sm font-semibold text-heading flex items-center gap-2 mb-1">
                        <span
                            class="w-8 h-8 rounded-lg bg-primary-soft text-primary flex items-center justify-center"><i
                                class="ph ph-squares-four text-base"></i></span>
                        Services selected by client as follows:
                    </h3>
                    <p class="text-xs text-muted mb-4 ml-10">The services this client has engaged us for.</p>

                    @if ($this->attachedServices->isNotEmpty())
                        <ol class="space-y-2 mb-5 list-none">
                            @foreach ($this->attachedServices as $index => $service)
                                <li class="flex items-center justify-between gap-3 px-4 py-3 rounded-xl border border-border-subtle bg-subtle/30 hover:bg-subtle/60 transition-colors"
                                    wire:key="service-{{ $service->id }}">
                                    <span class="flex items-center gap-3">
                                        <span
                                            class="w-7 h-7 rounded-full bg-primary text-white text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                            {{ $index + 1 }}
                                        </span>
                                        <span class="text-sm font-medium text-heading">{{ $service->name }}</span>
                                    </span>
                                    @can('manageServices', \App\Models\Client::class)
                                        <button wire:click="removeService({{ $service->id }})"
                                            class="text-muted hover:text-danger transition-colors" aria-label="Remove">
                                            <i class="ph ph-trash text-base"></i>
                                        </button>
                                    @endcan
                                </li>
                            @endforeach
                        </ol>
                    @else
                        <div class="text-center py-8">
                            <i class="ph ph-squares-four text-3xl text-faint"></i>
                            <p class="text-sm text-muted mt-2">No services have been added for this client yet.</p>
                        </div>
                    @endif

                    @can('manageServices', \App\Models\Client::class)
                        <form wire:submit="addService" class="flex items-center gap-2 pt-4 border-t border-border-subtle">
                            <input type="text" wire:model="newServiceName" list="service-suggestions"
                                placeholder="Type a service - e.g. SEO, GMB, Website Development"
                                class="flex-1 h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                            <datalist id="service-suggestions">
                                @foreach ($this->allServices as $s)
                                    <option value="{{ $s->name }}"></option>
                                @endforeach
                            </datalist>
                            <button type="submit"
                                class="h-10 px-4 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-strong whitespace-nowrap transition-colors">
                                <i class="ph ph-plus"></i> Add
                            </button>
                        </form>
                        @error('newServiceName')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-[11px] text-muted mt-2">Start typing to pick an existing service, or enter a new one
                            - it'll be added to the shared list for future clients too.</p>
                    @endcan
                </div>

                {{-- Task History --}}
                <div class="bg-surface border border-border rounded-2xl p-4 md:p-5 bs-cards">
                    <h3 class="text-sm font-semibold text-heading flex items-center gap-2 mb-4">
                        <span
                            class="w-8 h-8 rounded-lg bg-primary-soft text-primary flex items-center justify-center"><i
                                class="ph ph-list-checks text-base"></i></span>
                        Task History
                        @if ($this->clientTasks->isNotEmpty())
                            <span class="text-xs font-normal text-muted">({{ $this->clientTasks->count() }})</span>
                        @endif
                    </h3>

                    <div class="flex flex-wrap items-center gap-2 mb-4">
                        <select wire:model.live="taskStatusFilter"
                            class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary bg-surface">
                            <option value="all">All Statuses</option>
                            <option value="pending">To Do</option>
                            <option value="in_progress">In Progress</option>
                            <option value="on_hold">On Hold</option>
                            <option value="delayed">Delayed</option>
                            <option value="pending_review">In Review</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>

                        <select wire:model.live="taskContentTypeFilter"
                            class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary bg-surface">
                            <option value="all">All Content Types</option>
                            <option value="reel">Reel</option>
                            <option value="post">Post</option>
                        </select>

                        <input type="date" wire:model.live="taskDateFrom"
                            class="h-9 px-3 rounded-lg border border-border text-xs text-text-secondary bg-surface"
                            title="Created from" />
                        <span class="text-xs text-muted">to</span>
                        <input type="date" wire:model.live="taskDateTo"
                            class="h-9 px-3 rounded-lg border border-border text-xs text-text-secondary bg-surface"
                            title="Created to" />

                        @if ($taskStatusFilter !== 'all' || $taskContentTypeFilter !== 'all' || $taskDateFrom || $taskDateTo)
                            <button wire:click="clearTaskFilters"
                                class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-danger hover:bg-danger-soft transition-colors">
                                Clear
                            </button>
                        @endif
                    </div>

                    @if ($this->clientTasks->isNotEmpty())
                        <div class="rounded-xl border border-border overflow-x-auto">
                            <table class="w-full text-sm border-collapse min-w-[640px]">
                                <thead>
                                    <tr class="bg-subtle/50 text-xs text-muted text-left">
                                        <th
                                            class="px-4 py-2.5 font-medium border-r border-border-subtle whitespace-nowrap">
                                            Task</th>
                                        <th
                                            class="px-4 py-2.5 font-medium border-r border-border-subtle whitespace-nowrap">
                                            Status</th>
                                        <th
                                            class="px-4 py-2.5 font-medium border-r border-border-subtle whitespace-nowrap">
                                            Assignee</th>
                                        <th
                                            class="px-4 py-2.5 font-medium border-r border-border-subtle whitespace-nowrap">
                                            Assigned By</th>
                                        <th
                                            class="px-4 py-2.5 font-medium border-r border-border-subtle whitespace-nowrap">
                                            Assigned On</th>
                                        <th class="px-4 py-2.5 font-medium whitespace-nowrap">Completed On</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($this->clientTasks as $task)
                                        <tr wire:key="client-task-{{ $task->id }}"
                                            class="border-t border-border-subtle hover:bg-subtle/40 transition-colors cursor-pointer"
                                            onclick="window.location='{{ route('tasks.show', $task) }}'">
                                            <td class="px-4 py-3 border-r border-border-subtle">
                                                <a href="{{ route('tasks.show', $task) }}"
                                                    class="font-medium text-heading hover:text-primary">{{ $task->title }}</a>
                                            </td>
                                            <td class="px-4 py-3 border-r border-border-subtle">
                                                <span
                                                    class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full {{ $task->status->badgeClasses() }} text-[11px] font-medium">
                                                    {{ $task->status->label() }}
                                                </span>
                                            </td>
                                            <td class="px-4 py-3 border-r border-border-subtle text-text">
                                                {{ $task->currentAssignee()?->name ?? '-' }}</td>
                                            <td class="px-4 py-3 border-r border-border-subtle text-text">
                                                {{ $task->creator->name }}</td>
                                            <td class="px-4 py-3 border-r border-border-subtle text-muted text-xs">
                                                {{ $task->currentAssignment()->first()?->assigned_at?->format('M j, Y') ?? $task->created_at->format('M j, Y') }}
                                            </td>
                                            <td
                                                class="px-4 py-3 text-xs {{ $task->completed_at ? 'text-success font-medium' : 'text-faint' }}">
                                                {{ $task->completed_at?->format('M j, Y') ?? '-' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <i class="ph ph-list-checks text-3xl text-faint"></i>
                            <p class="text-sm text-muted mt-2">No tasks have been created for this client yet.</p>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Right / sidebar column --}}
            <aside class="bg-surface border border-border rounded-2xl p-4 md:p-5 sticky top-20 space-y-4 bs-cards"
                style="display: none;">
                <h3 class="text-sm font-semibold text-heading">Contact Info</h3>

                <div class="space-y-2.5">
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-subtle/40">
                        <span
                            class="w-9 h-9 rounded-lg bg-surface flex items-center justify-center flex-shrink-0 text-text-secondary">
                            <i class="ph ph-envelope-simple text-base"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-muted">Email</p>
                            <p class="text-sm text-text font-medium truncate">{{ $client->email ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-subtle/40">
                        <span
                            class="w-9 h-9 rounded-lg bg-surface flex items-center justify-center flex-shrink-0 text-text-secondary">
                            <i class="ph ph-phone text-base"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-muted">Phone</p>
                            <p class="text-sm text-text font-medium truncate">{{ $client->phone ?? '-' }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-subtle/40">
                        <span
                            class="w-9 h-9 rounded-lg bg-surface flex items-center justify-center flex-shrink-0 text-text-secondary">
                            <i class="ph ph-buildings text-base"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-muted">Department</p>
                            <p class="text-sm text-text font-medium truncate">{{ $client->department?->name ?? '-' }}
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center gap-3 p-2.5 rounded-xl bg-subtle/40">
                        <span
                            class="w-9 h-9 rounded-lg bg-surface flex items-center justify-center flex-shrink-0 text-text-secondary">
                            <i class="ph ph-calendar-blank text-base"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-muted">Engagement</p>
                            <p class="text-sm text-text font-medium">
                                @if ($client->start_date)
                                    {{ $client->start_date->format('M j, Y') }} –
                                    {{ $client->end_date?->format('M j, Y') ?? 'Ongoing' }}
                                @else
                                    -
                                @endif
                            </p>
                        </div>
                    </div>
                </div>

                @if ($client->notes)
                    <div class="pt-4 border-t border-border-subtle">
                        <p class="text-[11px] text-muted mb-1.5 flex items-center gap-1.5"><i
                                class="ph ph-note"></i>Notes</p>
                        <p class="text-sm text-text leading-relaxed">{{ $client->notes }}</p>
                    </div>
                @endif
            </aside>
        </div>
    @endif
</div>
