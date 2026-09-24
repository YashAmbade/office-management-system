<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5 bs-cards">
        <h2 class="text-base font-semibold text-heading">Social Media Team - Activity Report</h2>
        <p class="text-sm text-muted mt-0.5">Per-employee post history: day, time, platform, and status</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-[280px_1fr] gap-4">
        {{-- Employee selector (searchable dropdown) --}}
<div class="bg-surface border border-border rounded-2xl p-4 md:p-5">
    <label class="text-xs font-medium text-muted">Employee</label>

    <div x-data="{
        open: false,
        search: '',
        highlighted: 0,
        employees: {{ $this->employees->map(fn($e) => ['id' => $e->id, 'name' => $e->name, 'count' => $e->tasks_count])->toJson() }},
        selectedName: '{{ $this->selectedEmployee?->name ?? '' }}',
        get filtered() {
            if (!this.search) return this.employees;
            return this.employees.filter(e => e.name.toLowerCase().includes(this.search.toLowerCase()));
        },
        select(emp) {
            this.selectedName = emp.name;
            this.search = '';
            this.open = false;
            this.highlighted = 0;
            $wire.selectEmployee(emp.id);
        },
        moveDown() {
            if (this.highlighted < this.filtered.length - 1) this.highlighted++;
            this.scrollToHighlighted();
        },
        moveUp() {
            if (this.highlighted > 0) this.highlighted--;
            this.scrollToHighlighted();
        },
        chooseHighlighted() {
            if (this.filtered.length > 0 && this.filtered[this.highlighted]) {
                this.select(this.filtered[this.highlighted]);
            }
        },
        scrollToHighlighted() {
            this.$nextTick(() => {
                let el = this.$refs.dropdownPanel?.querySelector('[data-highlighted=true]');
                if (el) el.scrollIntoView({ block: 'nearest' });
            });
        }
    }" x-on:input="highlighted = 0" class="relative mt-1">
        <input type="text" x-model="search"
            x-on:focus="open = true; search = ''; highlighted = 0"
            x-on:click.stop="open = true; search = ''; highlighted = 0"
            x-on:keydown.down.prevent="open = true; moveDown()"
            x-on:keydown.up.prevent="open = true; moveUp()"
            x-on:keydown.enter.prevent="chooseHighlighted()"
            x-on:keydown.escape="open = false; search = ''"
            :placeholder="selectedName || 'Search employee…'"
            class="w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />

        <div x-show="open" x-cloak x-on:click.outside="open = false; search = ''" x-ref="dropdownPanel"
            class="absolute z-20 mt-1 w-full rounded-lg border border-border bg-surface shadow-lg"
            style="max-height: 260px; overflow-y: auto;">
            <template x-if="filtered.length === 0">
                <p class="px-3 py-2 text-xs text-muted">No employees found.</p>
            </template>
            <template x-for="(emp, index) in filtered" :key="emp.id">
                <button type="button" x-on:click.stop="select(emp)" x-on:click="select(emp)" x-on:mouseenter="highlighted = index"
                    :data-highlighted="highlighted === index"
                    :class="highlighted === index ? 'bg-subtle' : ''"
                    class="w-full flex items-center justify-between gap-2 px-3 py-2 text-sm text-text hover:bg-subtle transition-colors">
                    <span class="flex items-center gap-2.5 min-w-0">
                        <span class="w-7 h-7 rounded-full bg-primary/10 text-primary text-[10px] font-semibold flex items-center justify-center flex-shrink-0"
                            x-text="emp.name.substring(0, 2).toUpperCase()"></span>
                        <span class="truncate" x-text="emp.name"></span>
                    </span>
                    <span class="text-[11px] text-muted flex-shrink-0" x-text="emp.count"></span>
                </button>
            </template>
        </div>
    </div>
</div>

        {{-- Report panel --}}
        <div class="bg-surface border border-border rounded-2xl overflow-hidden">

            {{-- Empty State --}}
            @if (!$this->selectedEmployee)

                <div class="flex flex-col items-center justify-center text-center py-20 px-6">
                    <div
                        class="w-12 h-12 rounded-full bg-primary/10 text-primary flex items-center justify-center mb-3">
                        <i class="ph ph-user-circle text-2xl"></i>
                    </div>

                    <h3 class="text-sm font-semibold text-heading">
                        Select an Employee
                    </h3>

                    <p class="text-xs text-muted mt-1 max-w-sm">
                        Choose an employee from the list to view their post activity and status history.
                    </p>
                </div>
            @else
                {{-- Header --}}
                <div class="p-4 md:p-5 border-b border-border">

                    <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-4">

                        {{-- Employee --}}
                        <div class="flex items-center gap-3">

                            <div
                                class="w-10 h-10 rounded-full bg-primary/10 text-primary
                                flex items-center justify-center text-sm font-semibold">
                                {{ strtoupper(substr($this->selectedEmployee->name, 0, 2)) }}
                            </div>

                            <div>
                                <h3 class="text-base font-semibold text-heading">
                                    {{ $this->selectedEmployee->name }}
                                </h3>

                                <p class="text-xs text-muted mt-0.5">
                                    Post activity and status history
                                </p>
                            </div>

                        </div>

                        {{-- Filters --}}
                        <div class="flex items-center justify-between flex-wrap gap-3">
                            <h3 class="text-base font-semibold text-heading">{{ $this->selectedEmployee->name }}'s Report
                            </h3>

                            <div class="flex items-center gap-2 flex-wrap">
                                <select wire:model.live="statusFilter"
                                    class="h-9 px-3 rounded-lg border border-border bg-surface text-sm">
                                    <option value="all">All statuses</option>
                                    <option value="pending">Pending</option>
                                    <option value="in_progress">In Progress</option>
                                    <option value="on_hold">On Hold</option>
                                    <option value="delayed">Delayed</option>
                                    <option value="pending_review">Submitted</option>
                                    <option value="completed">Completed</option>
                                    <option value="cancelled">Cancelled</option>
                                </select>

                                {{-- Basis toggle: Assigned vs Completed --}}
                                

                                {{-- Mode toggle: Single Day vs Range --}}
                                <div class="flex items-center h-9 rounded-lg border border-border overflow-hidden">
                                    <button type="button" wire:click="setDateMode('single')"
                                        class="h-full px-3 text-xs font-medium transition-colors {{ $dateMode === 'single' ? 'bg-primary text-white' : 'text-muted hover:bg-subtle' }}">
                                        Single Day
                                    </button>
                                    <button type="button" wire:click="setDateMode('range')"
                                        class="h-full px-3 text-xs font-medium transition-colors {{ $dateMode === 'range' ? 'bg-primary text-white' : 'text-muted hover:bg-subtle' }}">
                                        Date Range
                                    </button>
                                </div>

                                @if ($dateMode === 'single')
                                    <input type="date" wire:model.live="selectedDate"
                                        class="h-9 px-3 rounded-lg border border-border bg-surface text-sm">
                                @else
                                    <div class="flex items-center gap-1.5">
                                        <input type="date" wire:model.live="fromDate" placeholder="From"
                                            class="h-9 px-3 rounded-lg border border-border bg-surface text-sm">
                                        <span class="text-xs text-muted">to</span>
                                        <input type="date" wire:model.live="toDate" placeholder="To"
                                            class="h-9 px-3 rounded-lg border border-border bg-surface text-sm">
                                    </div>
                                @endif

                                @if ($selectedDate || $fromDate || $toDate)
                                    <button wire:click="clearDateFilter"
                                        class="w-9 h-9 rounded-lg flex items-center justify-center text-muted hover:bg-subtle transition-colors"
                                        title="Clear date filter">
                                        <i class="ph ph-x text-sm"></i>
                                    </button>
                                @endif
                            </div>
                        </div>

                        {{-- Daily performance summary --}}
                        @if ($this->dayStats)
                        <div class="px-4 md:px-5 pb-4">
                            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">
                                <div class="bg-subtle/50 rounded-xl p-3 text-center">
                                    <p class="text-lg font-semibold text-heading">{{ $this->dayStats['total'] }}</p>
                                    <p class="text-[11px] text-muted mt-0.5">Total</p>
                                </div>
                                <div class="bg-subtle/50 rounded-xl p-3 text-center">
                                    <p class="text-lg font-semibold text-heading">{{ $this->dayStats['pending'] }}</p>
                                    <p class="text-[11px] text-muted mt-0.5">Pending</p>
                                </div>
                                <div class="bg-warning-soft rounded-xl p-3 text-center">
                                    <p class="text-lg font-semibold text-warning">{{ $this->dayStats['pending_review'] }}</p>
                                    <p class="text-[11px] text-warning/80 mt-0.5">Submitted</p>
                                </div>
                                <div class="bg-success-soft rounded-xl p-3 text-center">
                                    <p class="text-lg font-semibold text-success">{{ $this->dayStats['approved'] }}</p>
                                    <p class="text-[11px] text-success/80 mt-0.5">Approved</p>
                                </div>
                                <div class="bg-primary-soft rounded-xl p-3 text-center">
                                    <p class="text-lg font-semibold text-primary">{{ $this->dayStats['still_pending'] }}</p>
                                    <p class="text-[11px] text-primary/80 mt-0.5">Still In Progress</p>
                                </div>
                                <div class="bg-subtle/50 rounded-xl p-3 text-center">
                                    <p class="text-lg font-semibold text-heading">{{ $this->dayStats['completion_rate'] }}%</p>
                                    <p class="text-[11px] text-muted mt-0.5">Done From Their Side</p>
                                </div>
                            </div>
                        </div>
                        @endif

                    </div>

                </div>


                {{-- Task List --}}
<div class="overflow-x-auto">

    @if ($this->tasks->isEmpty())
        <div class="flex flex-col items-center justify-center text-center py-16">
            <div class="w-11 h-11 rounded-full bg-subtle flex items-center justify-center mb-3">
                <i class="ph ph-file-search text-xl text-muted"></i>
            </div>
            <h4 class="text-sm font-semibold text-heading">No posts found</h4>
            <p class="text-xs text-muted mt-1">Try changing the status or date filters.</p>
        </div>
    @else
        <table class="w-full min-w-[720px] text-sm">
            <thead>
                <tr class="text-left text-xs text-muted border-b border-border">
                    <th class="px-4 py-2.5 font-medium">Task</th>
                    <th class="px-4 py-2.5 font-medium">Client / Platform</th>
                    <th class="px-4 py-2.5 font-medium w-28">Assigned</th>
                    <th class="px-4 py-2.5 font-medium w-28">Submitted</th>
                    <th class="px-4 py-2.5 font-medium w-32">Status</th>
                    <th class="px-4 py-2.5 font-medium w-8"></th>
                </tr>
            </thead>
            <tbody>
                @foreach ($this->tasks as $task)
                    <tr wire:key="task-row-{{ $task->id }}"
                        wire:click="toggleHistory({{ $task->id }})"
                        class="border-b border-border-subtle last:border-0 hover:bg-subtle/40 transition-colors cursor-pointer {{ $expandedTaskId === $task->id ? 'bg-subtle/40' : '' }}">

                        <td class="px-4 py-2.5">
                            <div class="flex items-center gap-2.5 min-w-0">
                                <div class="w-7 h-7 rounded-lg bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                                    <i class="ph ph-file-text text-sm"></i>
                                </div>
                                <span class="font-medium text-heading truncate">{{ $task->title }}</span>
                            </div>
                        </td>

                        <td class="px-4 py-2.5 text-xs text-muted">
                            {{ $task->client?->name ?? 'No client' }}
                            @if ($task->platforms->isNotEmpty())
                                · {{ $task->platforms->pluck('platform')->join(', ') }}
                            @endif
                        </td>

                        <td class="px-4 py-2.5 text-xs">
                            @if ($task->activeAssignment)
                                <span class="text-heading font-medium">{{ \Carbon\Carbon::parse($task->activeAssignment->assigned_at)->format('M j') }}</span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($task->activeAssignment->assigned_at)->format('g:i A') }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-2.5 text-xs">
                            @php
                                $submittedEntry = $task->statusHistory->first(fn ($h) =>
                                    ($h->new_status instanceof \App\Enums\TaskStatus ? $h->new_status->value : $h->new_status) === 'pending_review'
                                );
                            @endphp
                        
                            @if ($task->status->value === 'completed' && $task->completed_at)
                                <span class="text-heading font-medium">{{ $task->completed_at->format('M j') }}</span>
                                <span class="text-muted">{{ $task->completed_at->format('g:i A') }}</span>
                            @elseif ($task->status->value === 'pending_review' && $submittedEntry)
                                <span class="text-heading font-medium">{{ \Carbon\Carbon::parse($submittedEntry->changed_at)->format('M j') }}</span>
                                <span class="text-muted">{{ \Carbon\Carbon::parse($submittedEntry->changed_at)->format('g:i A') }}</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>

                        <td class="px-4 py-2.5">
                            <span @class([
                                'inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[11px] font-semibold whitespace-nowrap',
                                'bg-success-soft text-success' => $task->status->value === 'completed',
                                'bg-warning-soft text-warning' => in_array($task->status->value, ['pending_review', 'delayed', 'on_hold']),
                                'bg-primary-soft text-primary' => $task->status->value === 'in_progress',
                                'bg-subtle text-faint' => in_array($task->status->value, ['pending', 'cancelled']),
                            ])>
                                <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                                {{ $task->status->value === 'pending_review' ? 'Submitted' : str($task->status->value)->replace('_', ' ')->title() }}
                            </span>
                        </td>

                        <td class="px-4 py-2.5 text-right">
                            <i class="ph {{ $expandedTaskId === $task->id ? 'ph-caret-up' : 'ph-caret-down' }} text-muted text-sm"></i>
                        </td>
                    </tr>

                    @if ($expandedTaskId === $task->id)
                        <tr wire:key="task-history-{{ $task->id }}">
                            <td colspan="6" class="p-0">
                                <div class="bg-subtle/30 border-b border-border-subtle px-4 py-3">
                                    <div class="flex items-center gap-2 mb-3">
                                        <i class="ph ph-clock-counter-clockwise text-muted"></i>
                                        <p class="text-[11px] font-semibold text-muted uppercase tracking-wide">Status History</p>
                                    </div>

                                    @forelse ($task->statusHistory as $entry)
                                        @php
                                            $oldStatusValue = $entry->old_status instanceof \App\Enums\TaskStatus ? $entry->old_status->value : $entry->old_status;
                                            $newStatusValue = $entry->new_status instanceof \App\Enums\TaskStatus ? $entry->new_status->value : $entry->new_status;
                                        @endphp
                                        <div class="flex gap-3">
                                            <div class="flex flex-col items-center">
                                                <div class="w-2 h-2 rounded-full bg-primary mt-1.5"></div>
                                                @if (!$loop->last)
                                                    <div class="w-px flex-1 bg-border my-1"></div>
                                                @endif
                                            </div>

                                            <div class="pb-3 min-w-0 flex-1">
                                                <div class="flex flex-wrap items-center gap-x-2 gap-y-1">
                                                    <span class="text-xs font-medium text-heading">
                                                        @if ($entry->old_status)
                                                            {{ $oldStatusValue === 'pending_review' ? 'Submitted' : str($oldStatusValue)->replace('_', ' ')->title() }}
                                                            <span class="text-muted mx-1">→</span>
                                                        @endif
                                                        {{ $newStatusValue === 'pending_review' ? 'Submitted' : str($newStatusValue)->replace('_', ' ')->title() }}
                                                    </span>
                                                    <span class="text-[11px] text-muted">
                                                        {{ \Carbon\Carbon::parse($entry->changed_at)->format('M j, Y · g:i A') }}
                                                    </span>
                                                </div>
                                                @if ($entry->reason)
                                                    <p class="text-xs text-muted mt-1">{{ $entry->reason }}</p>
                                                @endif
                                            </div>
                                        </div>
                                    @empty
                                        <div class="flex items-center gap-2 text-xs text-muted">
                                            <i class="ph ph-info"></i>No status history recorded.
                                        </div>
                                    @endforelse
                                </div>
                            </td>
                        </tr>
                    @endif
                @endforeach
            </tbody>
        </table>
    @endif
</div>

            @endif

        </div>
    </div>
</div>
