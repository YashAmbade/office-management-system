<div data-tasks data-view="{{ $view }}" class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!" x-data="{ draggedTaskId: null }"
    x-on:task-move-rejected.window="
        Swal.fire({
            icon: 'error',
            title: 'Can\'t move task',
            text: $event.detail.message,
            confirmButtonText: 'Got it'
        });
    ">
    {{-- ============================================================ --}}
    {{-- Header + toolbar                                              --}}
    {{-- ============================================================ --}}
    <div class="bg-surface border border-border rounded-xl p-4 md:p-5 bs-cards">
        <div class="flex flex-wrap items-start justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-heading">Tasks</h2>
                <p class="text-sm text-muted mt-0.5">Manage and track your team's work</p>
            </div>
            @can('create', \App\Models\Task::class)
                <button wire:click="openAddTaskModal"
                    class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-gold text-white text-sm font-medium hover:bg-primary-strong transition-colors bs-buttons">
                    <i class="ph ph-plus text-base"></i>Add New Task
                </button>
            @endcan
        </div>

        <div class="mt-4 pt-4 border-t border-border-subtle flex flex-wrap items-center justify-between gap-3">
            {{-- View switch - Kanban only available to Employees --}}
            @if (auth()->user()->hasRole('Employee'))
                <div class="inline-flex items-center gap-1 p-1 rounded-xl bg-subtle">
                    <button wire:click="$set('view', 'kanban')"
                        data-active="{{ $view === 'kanban' ? 'true' : 'false' }}"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium text-muted data-[active=true]:bg-surface data-[active=true]:text-heading data-[active=true]:shadow-sm transition-colors">
                        <i class="ph ph-kanban text-sm"></i>Kanban
                    </button>
                    <button wire:click="$set('view', 'list')" data-active="{{ $view === 'list' ? 'true' : 'false' }}"
                        class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium text-muted data-[active=true]:bg-surface data-[active=true]:text-heading data-[active=true]:shadow-sm transition-colors">
                        <i class="ph ph-list-bullets text-sm"></i>List
                    </button>
                </div>
            @endif

            {{-- Filters --}}
            <div class="flex flex-wrap items-center gap-2">
                <select wire:model.live="filter"
                    class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary bg-surface box-filter">
                    <option value="all">All Tasks</option>
                    <option value="mine">My Tasks</option>
                    <option value="high_priority">High Priority</option>
                    <option value="due_today">Due Today</option>
                    <option value="due_this_week">Due This Week</option>
                </select>

                <select wire:model.live="filterStatus"
                    class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary bg-surface">
                    <option value="all">Status: Any</option>
                    <option value="pending">To Do</option>
                    <option value="in_progress">In Progress</option>
                    <option value="on_hold">On Hold</option>
                    <option value="delayed">Delayed</option>
                    <option value="pending_review">Completed</option>
                    <option value="completed">Approved</option>
                    <option value="cancelled">Cancelled</option>
                </select>

                <div x-data="{
                    open: false,
                    search: '',
                    highlighted: 0,
                    clients: {{ $this->filterClientOptions->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toJson() }},
                    selectedName: '{{ $this->filterClientOptions->firstWhere('id', $filterClientId)?->name ?? '' }}',
                    get filtered() {
                        if (!this.search) return this.clients;
                        return this.clients.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
                    },
                    select(client) {
                        this.selectedName = client.name;
                        this.search = '';
                        this.open = false;
                        this.highlighted = 0;
                        $wire.set('filterClientId', client.id);
                    },
                    clear() {
                        this.selectedName = '';
                        this.search = '';
                        $wire.set('filterClientId', null);
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
                }" x-on:input="highlighted = 0" class="relative w-40">
                    <input type="text" x-model="search" x-on:focus="open = true; search = ''; highlighted = 0"
                        x-on:click.stop="open = true; search = ''; highlighted = 0"
                        x-on:keydown.down.prevent="open = true; moveDown()"
                        x-on:keydown.up.prevent="open = true; moveUp()" x-on:keydown.enter.prevent="chooseHighlighted()"
                        x-on:keydown.escape="open = false; search = ''" :placeholder="selectedName || 'Client: Any'"
                        class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary bg-surface w-full" />
                    <template x-if="selectedName && !open">
                        <button type="button" x-on:click="clear()"
                            class="absolute right-2 top-1/2 -translate-y-1/2 text-muted hover:text-danger"
                            aria-label="Clear">
                            <i class="ph ph-x-circle text-sm"></i>
                        </button>
                    </template>

                    <div x-show="open" x-cloak x-on:click.outside="open = false; search = ''" x-ref="dropdownPanel"
                        class="absolute z-20 mt-1 w-full rounded-lg border border-border bg-surface shadow-lg"
                        style="max-height: 200px; overflow-y: auto;">
                        <template x-if="filtered.length === 0">
                            <p class="px-3 py-2 text-xs text-muted">No clients found.</p>
                        </template>
                        <template x-for="(client, index) in filtered" :key="client.id">
                            <button type="button" x-on:click="select(client)" x-on:mouseenter="highlighted = index"
                                :data-highlighted="highlighted === index"
                                :class="highlighted === index ? 'bg-subtle' : ''"
                                class="w-full text-left px-3 py-2 text-xs text-text hover:bg-subtle transition-colors"
                                x-text="client.name"></button>
                        </template>
                    </div>
                </div>

                @can('viewAssigneeFilters', \App\Models\Task::class)
                    <select wire:model.live="filterAssignee"
                        class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary bg-surface box-filter">
                        <option value="">Assignee: Any</option>
                        @foreach ($this->assignableUsers as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>

                    <select wire:model.live="filterAssigner"
                        class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary bg-surface box-filter">
                        <option value="">Assigned By: Any</option>
                        @foreach ($this->assignerOptions as $u)
                            <option value="{{ $u->id }}">{{ $u->name }}</option>
                        @endforeach
                    </select>
                @endcan

                <input type="date" wire:model.live="filterDateFrom"
                    class="h-9 px-3 rounded-lg border border-border text-xs text-text-secondary bg-surface box-filter"
                    title="Due from" />
                <span class="text-xs text-muted">to</span>
                <input type="date" wire:model.live="filterDateTo"
                    class="h-9 px-3 rounded-lg border border-border text-xs text-text-secondary bg-surface box-filter"
                    title="Due to" />

                @if (
                    $filterStatus !== 'all' ||
                        $filterClientId ||
                        $filterAssignee ||
                        $filterAssigner ||
                        $filterDateFrom ||
                        $filterDateTo)
                    <button wire:click="clearFilters" ...>Clear</button>
                @endif
            </div>
        </div>
    </div>

    @php $escalatedCount = $this->escalatedTasks->count(); @endphp
    @if ($escalatedCount > 0)
        <div
            class="rounded-xl border border-danger/30 bg-danger-soft/40 p-3.5 flex items-start justify-between gap-3 flex-wrap">
            <div class="flex items-start gap-2.5">
                <i class="ph ph-warning-circle text-danger text-lg mt-0.5"></i>
                <p class="text-sm text-danger">
                    <strong>{{ $escalatedCount }} task{{ $escalatedCount !== 1 ? 's were' : ' was' }}</strong> still
                    pending as of yesterday and {{ $escalatedCount !== 1 ? 'have' : 'has' }} been automatically
                    escalated to <strong>High Priority</strong>.
                </p>
            </div>
            <a wire:click="$set('showEscalatedModal', true)"
                class="h-6 px-3 text-sm text-danger font-medium transition-colors flex-shrink-0">
                <strong>View Tasks <i
                        class="ph ph-arrow-square-out text-sm opacity-1 group-hover:opacity-60 transition-opacity flex-shrink-0"></i></strong>
            </a>
        </div>
    @endif

    @if (!$filterDateFrom && !$filterDateTo && $this->tasks->count() >= 30)
        <p class="text-xs text-muted flex items-center gap-1.5">
            <i class="ph ph-info"></i>Showing the 30 most recent tasks. Apply a date range to see more.
        </p>
    @endif

    {{-- ============================================================ --}}
    {{-- Kanban view                                                   --}}
    {{-- ============================================================ --}}
    @if ($view === 'kanban')
        <div class="task-pane pane-kanban">
            <div class="flex gap-4 overflow-x-auto pb-2">
                @foreach ($this->kanbanColumns as $columnKey => $column)
                    <div class="w-[280px] flex-shrink-0 flex flex-col bg-info-kanban rounded-xl p-3 lg:h-[calc(100dvh-16rem)]"
                        x-on:dragover.prevent
                        @if ($column['dropTarget']->value === 'on_hold') x-on:drop="let r = prompt('Please provide a reason for putting this on hold:'); if (r) $wire.moveTask(draggedTaskId, '{{ $column['dropTarget']->value }}', r)"
                        @else
                            x-on:drop="$wire.moveTask(draggedTaskId, '{{ $column['dropTarget']->value }}')" @endif>
                        <div class="flex items-center justify-between px-1 mb-3">
                            <div class="flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-muted"></span>
                                <h3 class="text-sm font-semibold text-heading">{{ $column['label'] }}</h3>
                                <span
                                    class="text-[11px] font-medium text-muted bg-surface border border-border rounded-full px-2 py-0.5">
                                    {{ $this->tasksByColumn[$columnKey]->count() }}
                                </span>
                            </div>
                        </div>

                        <div class="flex-1 overflow-y-auto space-y-3 pr-0.5 rounded-lg transition-colors">
                            @foreach ($this->tasksByColumn[$columnKey] as $task)
                                @php $cardTransitions = $this->availableTransitionsFor($task); @endphp
                                <article wire:key="kanban-card-{{ $task->id }}" draggable="true"
                                    x-data="{ menuOpen: false, menuTop: 0, menuLeft: 0 }" x-on:dragstart="draggedTaskId = {{ $task->id }}"
                                    x-on:contextmenu.prevent="
                                        @if ($cardTransitions->isNotEmpty()) menuTop = $event.clientY; menuLeft = $event.clientX; menuOpen = true; @endif
                                    "
                                    class="box-filter-kanban bg-surface border border-border rounded-xl p-3.5 space-y-3 cursor-grab active:cursor-grabbing hover:shadow-md transition-shadow {{ $task->isOverdue() ? 'ring-1 ring-danger/40' : '' }}">
                                    <div class="flex items-center justify-between">
                                        <span
                                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full {{ $task->priority->badgeClasses() }} text-[11px] font-medium">
                                            {{ $task->priority->label() }}
                                            @if ($task->priority_auto_escalated)
                                                <i class="ph ph-trend-up"
                                                    title="Auto-escalated: was pending as of yesterday"></i>
                                            @endif
                                        </span>
                                        <div class="flex items-center gap-1.5">
                                            @if ($columnKey === 'done')
                                                <span
                                                    class="inline-flex items-center px-1.5 py-0.5 rounded-full {{ $task->status->badgeClasses() }} text-[10px] font-medium">
                                                    {{ $task->status->label() }}
                                                </span>
                                            @endif
                                            @if ($task->task_type === 'add_on')
                                                <span
                                                    class="inline-flex items-center gap-1 px-1.5 py-0.5 rounded-full bg-warning-soft text-warning text-[10px] font-medium"
                                                    title="Add-on / urgent task">
                                                    <i class="ph ph-lightning"></i>Add-on
                                                </span>
                                            @endif
                                            <a href="{{ route('tasks.show', $task) }}"
                                                class="text-muted hover:text-heading" aria-label="Open task">
                                                <i class="ph ph-arrow-square-out"></i>
                                            </a>
                                            @can('delete', $task)
                                                <button wire:click="confirmDeleteTask({{ $task->id }})"
                                                    class="text-muted hover:text-danger" aria-label="Delete">
                                                    <i class="ph ph-trash"></i>
                                                </button>
                                            @endcan
                                        </div>
                                    </div>
                                        @if ($task->client)
                                            <div class="flex items-center gap-1 text-[11px] text-muted">
                                                <i class="ph ph-briefcase text-sm"></i>{{ $task->client->name }}
                                            </div>
                                        @endif
                                        <div>
                                            <a href="{{ route('tasks.show', $task) }}"
                                                class="text-sm font-semibold text-heading hover:text-primary">
                                                {{ $task->title }}
                                            </a>
                                            <p class="text-xs text-muted mt-1 line-clamp-2">{{ $task->description }}</p>
                                        </div>

                                    @if ($task->activeInterruption())
                                        <p class="text-[11px] text-warning flex items-center gap-1">
                                            <i class="ph ph-pause-circle"></i>Paused - interrupted by add-on task
                                        </p>
                                    @endif
                                    <div class="flex items-center justify-between pt-0.5">
                                        <span
                                            class="inline-flex items-center gap-1 text-[11px] {{ $task->isOverdue() ? 'text-danger font-medium' : 'text-muted' }}">
                                            <i class="ph ph-calendar-blank"></i>{{ $task->due_date->format('M j') }}
                                        </span>
                                        <div class="flex items-center gap-2.5">
                                            @if ($assignee = $task->currentAssignee())
                                                <span
                                                    class="w-6 h-6 rounded-full bg-primary/10 text-primary text-[10px] font-semibold flex items-center justify-center ring-2 ring-surface"
                                                    title="{{ $assignee->name }}">
                                                    {{ strtoupper(substr($assignee->name, 0, 2)) }}
                                                </span>
                                            @endif
                                            <span class="inline-flex items-center gap-1 text-[11px] text-muted">
                                                <i class="ph ph-chat-circle"></i>{{ $task->comments->count() }}
                                            </span>
                                        </div>
                                    </div>

                                    @if ($cardTransitions->isNotEmpty())
                                        <template x-teleport="body">
                                            <div x-show="menuOpen" x-cloak x-transition
                                                x-on:click.outside="menuOpen = false"
                                                class="fixed z-50 w-48 rounded-xl border border-border bg-surface shadow-lg py-1"
                                                :style="`top: ${menuTop}px; left: ${menuLeft}px;`">
                                                <p
                                                    class="px-3 py-1.5 text-[11px] font-medium text-muted border-b border-border-subtle">
                                                    Change status</p>
                                                @foreach ($cardTransitions as $t)
                                                    @php
                                                        $needsReason =
                                                            in_array($t->value, ['on_hold', 'cancelled'], true) ||
                                                            ($task->status->value === 'pending_review' &&
                                                                $t->value === 'in_progress');
                                                    @endphp
                                                    <button type="button"
                                                        x-on:click="
                                                                    menuOpen = false;
                                                                    @if ($needsReason) let r = prompt('Please provide a reason for this change:');
                                                                        if (r) { $wire.moveTask({{ $task->id }}, '{{ $t->value }}', r); }
                                                                    @else
                                                                        $wire.moveTask({{ $task->id }}, '{{ $t->value }}'); @endif
                                                                "
                                                        class="w-full text-left px-3 py-1.5 text-xs text-text hover:bg-subtle transition-colors"
                                                        style="text-decoration: none;">
                                                        {{ $t->label() }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </template>
                                    @endif
                                </article>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- List / table view                                             --}}
    {{-- ============================================================ --}}
    @if ($view === 'list')
        <div class="bg-surface border border-border rounded-xl overflow-x-auto bs-cards">
            <table class="w-full min-w-[760px] text-sm">
                <thead>
                    <tr class="text-left text-sm text-black border-b border-border bg-info-listhead">
                        <th class="px-4 py-3 font-medium">Task</th>
                        <th class="px-4 py-3 font-medium">Assignee</th>
                        @can('viewAssignerColumn', \App\Models\Task::class)
                            <th class="px-4 py-3 font-medium">Assigned By</th>
                        @endcan
                        <th class="px-4 py-3 font-medium">Client / Platform</th>
                        <th class="px-4 py-3 font-medium">Priority</th>
                        <th class="px-4 py-3 font-medium">Status</th>
                        <th class="px-4 py-3 font-medium">Due</th>
                        <th class="px-4 py-3 font-medium"></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($this->tasks as $task)
                        <tr wire:key="list-row-{{ $task->id }}"
                            class="hover:bg-subtle/40 transition-colors border-b border-border-subtle last:border-0 bg-info-lightgray">
                            <td class="px-4 py-3">
                                <a href="{{ route('tasks.show', $task) }}"
                                    class="font-medium text-heading hover:text-primary">{{ $task->title }}</a>
                            </td>
                            <td class="px-4 py-3 text-muted">{{ $task->currentAssignee()?->name ?? '-' }}</td>
                            @can('viewAssignerColumn', \App\Models\Task::class)
                                <td class="px-4 py-3 text-muted">{{ $task->creator->name }}</td>
                            @endcan
                            <td class="px-4 py-3 text-muted text-xs">
                                @if ($task->client)
                                    {{ $task->client->name }}
                                    @if ($task->platforms->isNotEmpty())
                                        · {{ $task->platforms->pluck('platform')->join(', ') }}
                                        ({{ ucfirst($task->content_type) }})
                                    @endif
                                @else
                                    -
                                @endif
                                @if (!empty($this->quotaWarnings))
                                    <div
                                        class="rounded-xl border border-warning/30 bg-warning-soft/40 p-3 flex items-start gap-2">
                                        <i class="ph ph-warning text-warning mt-0.5"></i>
                                        <p class="text-xs text-warning">
                                            This client's plan for
                                            <strong>{{ implode(', ', $this->quotaWarnings) }}</strong> is already at or
                                            over quota.
                                            This task will be marked as an <strong>extra delivery</strong>, beyond the
                                            agreed plan.
                                        </p>
                                    </div>
                                @endif
                            </td>
                            <td class="px-4 py-3">
                                <span
                                    class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full {{ $task->priority->badgeClasses() }} text-[11px] font-medium">
                                    {{ $task->priority->label() }}
                                    @if ($task->priority_auto_escalated)
                                        <i class="ph ph-trend-up" title="Auto-escalated"></i>
                                    @endif
                                </span>
                            </td>
                            <td class="px-4 py-3">
                                @php $transitions = $this->availableTransitionsFor($task); @endphp
                                @if (auth()->user()->hasRole(['Super Admin', 'Manager', 'Team Lead']) && $transitions->isNotEmpty())
                                    <div x-data="{ open: false, top: 0, left: 0 }" class="inline-block">
                                        <button type="button"
                                            x-on:click="
                                                    let r = $el.getBoundingClientRect();
                                                    top = r.bottom + window.scrollY + 4;
                                                    left = r.left + window.scrollX;
                                                    open = !open;
                                                "
                                            class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full {{ $task->status->badgeClasses() }} text-[11px] font-medium cursor-pointer">
                                            {{ $task->status->label() }}
                                            <i class="ph ph-caret-down text-[10px]"></i>
                                        </button>

                                        <template x-teleport="body">
                                            <div x-show="open" x-cloak x-transition x-on:click.outside="open = false"
                                                class="fixed z-50 w-40 rounded-xl border border-border bg-surface shadow-lg py-1"
                                                :style="`top: ${top}px; left: ${left}px;`">
                                                @foreach ($transitions as $t)
                                                    @php
                                                        $isSendBack = $task->status->value === 'pending_review' && $t->value === 'in_progress';
                                                        $needsReason = in_array($t->value, ['on_hold', 'cancelled'], true) || $isSendBack;
                                                        $isReopen = $task->status->value === 'completed';
                                                    @endphp
                                                    <button type="button"
                                                        x-on:click="
                                                            open = false;
                                                            @if($isSendBack)
                                                                confirmSendBack('{{ $task->due_date->format('Y-m-d\TH:i') }}').then(function(result) {
                                                                    if (result === false) return;
                                                                    $wire.changeStatusFromList({{ $task->id }}, '{{ $t->value }}', result.reason, result.dueDate);
                                                                });
                                                            @else
                                                                confirmStatusChange('{{ $t->label() }}', {
                                                                    needsReason: {{ $needsReason ? 'true' : 'false' }},
                                                                    isReopen: {{ $isReopen ? 'true' : 'false' }}
                                                                }).then(function(result) {
                                                                    if (result === false) return;
                                                                    $wire.changeStatusFromList({{ $task->id }}, '{{ $t->value }}', typeof result === 'string' ? result : null);
                                                                });
                                                            @endif
                                                        "
                                                        class="w-full text-left px-3 py-1.5 text-xs text-text hover:bg-subtle transition-colors"
                                                        style="text-decoration: none;">
                                                        {{ $t->label() }}
                                                    </button>
                                                @endforeach
                                            </div>
                                        </template>
                                    </div>
                                @else
                                    <span
                                        class="inline-flex items-center px-2 py-0.5 rounded-full {{ $task->status->badgeClasses() }} text-[11px] font-medium">
                                        {{ $task->status->label() }}
                                    </span>
                                @endif
                            </td>
                            <td class="px-4 py-3 {{ $task->isOverdue() ? 'text-danger font-medium' : 'text-muted' }}">
                                {{ $task->due_date->format('M j, Y') }}
                            </td>
                            <td class="px-4 py-3 text-right">
                                <a href="{{ route('tasks.show', $task) }}" class="text-muted hover:text-heading"><i
                                        class="ph ph-arrow-square-out"></i></a>
                                @can('delete', $task)
                                    <button wire:click="confirmDeleteTask({{ $task->id }})"
                                        class="text-muted hover:text-danger" aria-label="Delete">
                                        <i class="ph ph-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif


    @if ($deletingTaskId)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-md bg-surface rounded-xl border border-border shadow-2xl">
                <div class="p-5">
                    <div class="flex items-center gap-2 text-danger mb-3">
                        <i class="ph ph-warning text-xl"></i>
                        <h3 class="text-base font-semibold text-heading">Delete this task?</h3>
                    </div>
                    <p class="text-sm text-muted mb-4">This removes it from the board, but it stays recorded for audit
                        purposes - visible on the All Tasks log with your name and reason attached.</p>
                    <textarea wire:model="deletingReason" rows="2" placeholder="Reason for deletion…"
                        class="w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
                    @error('deletingReason')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-end gap-2 mt-4">
                        <button wire:click="$set('deletingTaskId', null)"
                            class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary">Cancel</button>
                        <button wire:click="deleteTask"
                            class="h-9 px-4 rounded-lg bg-danger text-white text-xs font-medium hover:bg-red-600">Delete
                            Task</button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- pending modal --}}
    @if ($showEscalatedModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-2xl bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading flex items-center gap-2">
                        <i class="ph ph-warning-circle text-danger"></i>Overdue &amp; Still Pending
                    </h3>
                    <button wire:click="$set('showEscalatedModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <div class="p-5 max-h-[70vh] overflow-y-auto space-y-2.5">
                    @forelse($this->escalatedTasks as $task)
                        <div wire:key="escalated-{{ $task->id }}"
                            class="flex items-center justify-between gap-3 rounded-xl border border-border p-3.5">
                            <div class="min-w-0">
                                <a href="{{ route('tasks.show', $task) }}"
                                    class="text-sm font-medium text-heading hover:text-primary">{{ $task->title }}</a>
                                <div class="flex items-center gap-2 mt-1 text-xs text-muted">
                                    <span>{{ $task->currentAssignee()?->name ?? 'Unassigned' }}</span>
                                    <span class="text-faint">·</span>
                                    <span class="text-danger">Due {{ $task->due_date->format('M j, Y') }}</span>
                                </div>
                            </div>
                            @if (auth()->user()->hasRole(['Super Admin', 'Manager', 'Team Lead']))
                                <button
                                    x-on:click="
                                    confirmStatusChange('Completed', { isReopen: false }).then(function(result) {
                                        if (result === false) return;
                                        $wire.markPendingComplete({{ $task->id }});
                                    });
                                "
                                    class="h-8 px-3 rounded-lg bg-success text-white text-xs font-medium hover:bg-green-600 transition-colors flex-shrink-0">
                                    <i class="ph ph-check"></i> Mark Complete
                                </button>
                            @endif
                        </div>
                    @empty
                        <p class="text-sm text-muted text-center py-6">All caught up - nothing pending anymore.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================ --}}
    {{-- Add Task modal                                                --}}
    {{-- ============================================================ --}}
    @if ($showAddTaskModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4" style="overflow-y: scroll">
            <div class="w-full max-w-lg bg-surface rounded-xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">Add New Task</h3>
                    <button wire:click="$set('showAddTaskModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form wire:submit="createTask" class="p-5 space-y-4">
                    <div>
                        <label class="text-xs font-medium text-muted">Title</label>
                        <input type="text" wire:model="newTitle"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                        @error('newTitle')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Description</label>
                        <textarea wire:model="newDescription" rows="2"
                            class="mt-1 w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="text-xs font-medium text-muted">Priority</label>
                            <select wire:model="newPriority"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                                <option value="critical">Critical</option>
                            </select>
                        </div>
                        <div>
                            <label class="text-xs font-medium text-muted">Due Date</label>
                            <input type="datetime-local" wire:model="newDueDate"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                            @error('newDueDate')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Assign To</label>
                        <select wire:model.live="newAssigneeId"
                            class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                            <option value="">Select employee…</option>
                            @foreach ($this->assignableUsers as $u)
                                <option value="{{ $u->id }}">{{ $u->name }}</option>
                            @endforeach
                        </select>
                        @error('newAssigneeId')
                            <p class="text-xs text-danger mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div x-data="{
                        open: false,
                        search: '',
                        highlighted: 0,
                        clients: {{ $this->clientOptions->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toJson() }},
                        selectedName: '{{ $this->clientOptions->firstWhere('id', $newClientId)?->name ?? '' }}',
                        get filtered() {
                            if (!this.search) return this.clients;
                            return this.clients.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
                        },
                        select(client) {
                            this.selectedName = client.name;
                            this.search = '';
                            this.open = false;
                            this.highlighted = 0;
                            $wire.set('newClientId', client.id);
                        },
                        clear() {
                            this.selectedName = '';
                            this.search = '';
                            $wire.set('newClientId', null);
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
                    }" x-on:input="highlighted = 0" class="relative">
                        <label class="text-xs font-medium text-muted">Client*</label>
                        <div class="relative mt-1">
                            <input type="text" x-model="search"
                                x-on:focus="open = true; search = ''; highlighted = 0"
                                x-on:click.stop="open = true; search = ''; highlighted = 0"
                                x-on:keydown.down.prevent="open = true; moveDown()"
                                x-on:keydown.up.prevent="open = true; moveUp()"
                                x-on:keydown.enter.prevent="chooseHighlighted()"
                                x-on:keydown.escape="open = false; search = ''"
                                :placeholder="selectedName || 'Search client…'"
                                class="w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                            <template x-if="selectedName && !open">
                                <button type="button" x-on:click="clear()"
                                    class="absolute right-2 top-1/2 -translate-y-1/2 text-muted hover:text-danger"
                                    aria-label="Clear">
                                    <i class="ph ph-x-circle"></i>
                                </button>
                            </template>

                            <div x-show="open" x-cloak x-on:click.outside="open = false; search = ''"
                                x-ref="dropdownPanel"
                                class="absolute z-20 mt-1 w-full rounded-lg border border-border bg-surface shadow-lg"
                                style="max-height: 200px; overflow-y: auto;">
                                <template x-if="filtered.length === 0">
                                    <p class="px-3 py-2 text-xs text-muted">No clients found.</p>
                                </template>
                                <template x-for="(client, index) in filtered" :key="client.id">
                                    <button type="button" x-on:click="select(client)"
                                        x-on:mouseenter="highlighted = index" :data-highlighted="highlighted === index"
                                        :class="highlighted === index ? 'bg-subtle' : ''"
                                        class="w-full text-left px-3 py-2 text-sm text-text hover:bg-subtle transition-colors"
                                        x-text="client.name"></button>
                                </template>
                            </div>
                        </div>
                    </div>

                    @if ($newClientId && $this->selectedClientPlatforms->isNotEmpty())
                        <div>
                            <label class="text-xs font-medium text-muted">Platforms</label>
                            <div class="mt-1.5 flex flex-wrap gap-2">
                                @foreach ($this->selectedClientPlatforms as $p)
                                    <label
                                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg border border-border text-xs cursor-pointer has-[:checked]:bg-primary-soft has-[:checked]:border-primary has-[:checked]:text-primary">
                                        <input type="checkbox" wire:model.live="newPlatforms"
                                            value="{{ $p->platform }}" class="rounded" />
                                        {{ $p->platform }}
                                    </label>
                                @endforeach
                            </div>
                            @error('newPlatforms')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if (!empty($this->quotaWarnings))
                            <div
                                class="rounded-xl border border-warning/30 bg-warning-soft/40 p-3 flex items-start gap-2">
                                <i class="ph ph-warning text-warning mt-0.5"></i>
                                <p class="text-xs text-warning">
                                    This client's plan for <strong>{{ implode(', ', $this->quotaWarnings) }}</strong>
                                    is already at or over quota.
                                    This task will be marked as an <strong>extra delivery</strong>, beyond the agreed
                                    plan.
                                </p>
                            </div>
                        @endif

                        @if (!empty($newPlatforms) && $this->availableContentTypes->isNotEmpty())
                            <div>
                                <label class="text-xs font-medium text-muted">Content Type</label>
                                <select wire:model.live="newContentTypeId"
                                    class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                                    <option value="">Select…</option>
                                    @foreach ($this->availableContentTypes as $ct)
                                        <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                                    @endforeach
                                </select>
                                @error('newContentTypeId')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif
                    @endif


                    @if ($this->candidateInterruptedTasks->isNotEmpty())
                        <div class="rounded-xl border border-warning/30 bg-warning-soft p-3.5 space-y-3">
                            <label class="flex items-start gap-2 text-sm">
                                <input type="checkbox" wire:model.live="isAddOn" class="mt-0.5" />
                                <span>
                                    <span class="font-medium text-heading">Mark as Add-on / Urgent Task</span><br>
                                    <span class="text-xs text-muted">This employee is currently working on another
                                        task. Marking this as an add-on will auto-pause it and record the interruption
                                        so the delay is justified.</span>
                                </span>
                            </label>

                            @if ($isAddOn)
                                <div>
                                    <label class="text-xs font-medium text-muted">Which task is being
                                        interrupted?</label>
                                    <select wire:model="interruptedTaskId"
                                        class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                                        <option value="">Select task…</option>
                                        @foreach ($this->candidateInterruptedTasks as $ct)
                                            <option value="{{ $ct->id }}">{{ $ct->title }}</option>
                                        @endforeach
                                    </select>
                                    @error('interruptedTaskId')
                                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                                <div>
                                    <label class="text-xs font-medium text-muted">Reason (optional)</label>
                                    <input type="text" wire:model="addOnReason"
                                        class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm"
                                        placeholder="e.g. Client escalation" />
                                </div>
                            @endif
                        </div>
                    @endif

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="$set('showAddTaskModal', false)"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong">Create
                            Task</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
