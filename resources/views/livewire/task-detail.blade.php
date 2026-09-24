<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm">
        <a aria-label="Back"
            class="w-8 h-8 -ml-1 rounded-lg flex items-center justify-center text-muted hover:bg-subtle transition-colors"
            href="{{ route('tasks.index') }}">
            <i class="ph ph-arrow-left text-lg"></i>
        </a>
        <a class="text-muted hover:text-primary transition-colors" href="{{ route('tasks.index') }}">Tasks</a>
        <i class="ph ph-caret-right text-xs text-faint"></i>
        <span class="text-heading font-medium truncate max-w-[240px] sm:max-w-none">{{ $task->title }}</span>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4 items-start">
        {{-- ==================================================== --}}
        {{-- Main column                                           --}}
        {{-- ==================================================== --}}
        <div class="xl:col-span-2 space-y-4">

            {{-- Task header card --}}
            <div class="bg-surface border border-border rounded-2xl overflow-hidden">
                <div
                    class="h-1.5 {{ match ($task->priority->value) {
                        'critical' => 'bg-danger',
                        'high' => 'bg-warning',
                        'medium' => 'bg-info',
                        default => 'bg-muted',
                    } }}">
                </div>

                <div class="p-4 md:p-5">
                    <div class="flex items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="flex items-center gap-2 mb-2.5 flex-wrap">
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $task->status->badgeClasses() }} text-xs font-medium">
                                    <span
                                        class="w-1.5 h-1.5 rounded-full bg-current"></span>{{ $task->status->label() }}
                                </span>
                                <span
                                    class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full {{ $task->priority->badgeClasses() }} text-xs font-medium">
                                    {{ $task->priority->label() }} Priority
                                </span>
                                @if ($task->task_type === 'add_on')
                                    <span
                                        class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-warning-soft text-warning text-xs font-medium">
                                        <i class="ph ph-lightning"></i>Add-on
                                    </span>
                                @endif
                            </div>
                            <h1 class="text-lg md:text-xl font-semibold text-heading leading-snug">{{ $task->title }}
                            </h1>
                            <div class="flex items-center gap-1.5 text-sm text-muted mt-1.5 flex-wrap">
                                <i class="ph ph-buildings text-base"></i>
                                <span>{{ $task->department?->name ?? 'No department' }}</span>
                                <span class="text-faint">·</span>
                                <i class="ph ph-clock text-base"></i>
                                <span>Created {{ $task->created_at->format('M j, Y') }}</span>
                                @if ($task->client)
                                    <span class="text-faint">·</span>
                                    <i class="ph ph-briefcase text-base"></i>
                                    <span>{{ $task->client->name }}</span>
                                @endif
                            </div>
                        </div>
                        <div class="flex items-center gap-1 flex-shrink-0">
                            @if ($this->isReviewer)
                                <button wire:click="openEditTask"
                                    class="w-9 h-9 rounded-lg flex items-center justify-center text-muted hover:bg-subtle hover:text-heading transition-colors"
                                    aria-label="Edit">
                                    <i class="ph ph-pencil-simple text-lg"></i>
                                </button>
                            @endif
                        </div>
                    </div>

                    @if ($task->platforms->isNotEmpty())
                        <div class="mt-3 flex flex-wrap gap-1.5">
                            @foreach ($task->platforms as $p)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full bg-primary-soft text-primary text-[11px] font-medium">
                                    {{ $p->platform }}
                                </span>
                            @endforeach
                            @if ($task->content_type)
                                <span
                                    class="inline-flex items-center px-2 py-0.5 rounded-full bg-subtle text-text-secondary text-[11px] font-medium">
                                    {{ ucfirst($task->content_type) }}
                                </span>
                            @endif
                        </div>
                    @endif

                    {{-- Attachments --}}
                    @if ($task->attachments->isNotEmpty())
                        <div class="mt-5 pt-5 border-t border-border-subtle">
                            <h3 class="text-sm font-semibold text-heading mb-3 flex items-center gap-1.5">
                                <i class="ph ph-paperclip text-base"></i>Attachments
                                <span class="text-muted font-normal">({{ $task->attachments->count() }})</span>
                            </h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                                @foreach ($task->attachments as $attachment)
                                    <div class="flex items-center gap-3 p-3 rounded-xl border border-border">
                                        <span
                                            class="w-10 h-10 rounded-lg bg-primary-soft text-primary flex items-center justify-center flex-shrink-0">
                                            <i class="ph ph-file text-xl"></i>
                                        </span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-sm font-medium text-heading truncate">
                                                {{ $attachment->file_name }}</p>
                                            <p class="text-[11px] text-muted">
                                                {{ number_format($attachment->file_size / 1024, 0) }} KB</p>
                                        </div>
                                        <a href="{{ Storage::url($attachment->file_path) }}"
                                            class="text-muted hover:text-primary flex-shrink-0" aria-label="Download">
                                            <i class="ph ph-download-simple text-lg"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    {{-- Description --}}
                    <div class="mt-5 pt-5 border-t border-border-subtle">
                        <h3 class="text-sm font-semibold text-heading mb-2 flex items-center gap-1.5">
                            <i class="ph ph-text-align-left text-base"></i>Description
                        </h3>
                        <div class="text-sm text-text leading-relaxed">
                            <p>{{ $task->description ?: 'No description provided.' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs card --}}
            <div data-tabs class="bg-surface border border-border rounded-2xl p-4 md:p-5">
                <div class="flex items-center gap-1 p-1 rounded-xl bg-subtle w-full sm:w-auto sm:inline-flex">
                    <button data-tab="activity" data-active="true"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium text-muted data-[active=true]:bg-surface data-[active=true]:text-heading data-[active=true]:shadow-sm transition-colors">
                        <i class="ph ph-clock-clockwise text-sm"></i>Activity
                    </button>
                    <button data-tab="comments"
                        class="fflex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium text-muted data-[active=true]:bg-surface data-[active=true]:text-heading data-[active=true]:shadow-sm transition-colors">
                        <i class="ph ph-chat-circle text-sm"></i>Comments
                        @if ($task->comments->count() > 0)
                            <span class="text-[10px]">({{ $task->comments->count() }})</span>
                        @endif
                    </button>
                    <button data-tab="delay-requests"
                        class="flex-1 sm:flex-none inline-flex items-center justify-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium text-muted data-[active=true]:bg-surface data-[active=true]:text-heading data-[active=true]:shadow-sm transition-colors">
                        <i class="ph ph-clock-countdown text-sm"></i>Delay Requests
                        @if ($this->delayRequests->where('status', 'pending')->isNotEmpty())
                            <span class="w-1.5 h-1.5 rounded-full bg-danger"></span>
                        @endif
                    </button>

                </div>

                {{-- Activity panel --}}
                <div data-tab-panel="activity" class="mt-5">
    <div class="overflow-x-auto rounded-lg border border-border-subtle">
        <table class="w-full text-sm">
            <thead>
                <tr class="bg-subtle/50 text-left text-xs text-muted uppercase tracking-wide">
                    <th class="px-4 py-2.5 font-medium">User</th>
                    <th class="px-4 py-2.5 font-medium">Status Change</th>
                    <th class="px-4 py-2.5 font-medium">Reason</th>
                    <th class="px-4 py-2.5 font-medium text-right">When</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-border-subtle">
                @forelse ($this->activity as $entry)
                    @php $aColors = $entry->changedBy?->roleColorClasses() ?? ['bg' => 'bg-subtle', 'text' => 'text-text-secondary']; @endphp
                    <tr wire:key="activity-{{ $entry->id }}" class="hover:bg-subtle/30 transition-colors">
                        <td class="px-4 py-3">
                            <div class="flex items-center gap-2.5">
                                <span
                                    class="w-7 h-7 rounded-full {{ $aColors['bg'] }} {{ $aColors['text'] }} flex items-center justify-center flex-shrink-0 text-[11px] font-semibold">
                                    @if ($entry->changedBy)
                                        {{ strtoupper(substr($entry->changedBy->name, 0, 2)) }}
                                    @else
                                        <i class="ph ph-robot"></i>
                                    @endif
                                </span>
                                <span class="font-medium {{ $aColors['text'] }} whitespace-nowrap">
                                    {{ $entry->changedBy?->name ?? 'System' }}
                                </span>
                            </div>
                        </td>
                        <td class="px-4 py-3 text-text whitespace-nowrap">
                            @if ($entry->old_status)
                                <span class="text-muted">{{ ucfirst(str_replace('_', ' ', $entry->old_status)) }}</span>
                                <i class="ph ph-arrow-right text-faint mx-1"></i>
                            @endif
                            <span class="font-medium">{{ ucfirst(str_replace('_', ' ', $entry->new_status)) }}</span>
                        </td>
                        <td class="px-4 py-3 text-muted">
                            @if ($entry->reason)
                                <span class="text-xs bg-subtle/50 rounded-lg px-2.5 py-1 inline-block">
                                    {{ $entry->reason }}
                                </span>
                            @else
                                <span class="text-faint text-xs">-</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-[11px] text-faint text-right whitespace-nowrap">
                            {{ $entry->changed_at->diffForHumans() }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center py-8">
                            <i class="ph ph-clock-clockwise text-3xl text-faint"></i>
                            <p class="text-sm text-muted mt-2">No activity yet.</p>
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

                {{-- Comments panel --}}
                <div data-tab-panel="comments" class="mt-5 hidden">
                    <ul class="space-y-4">
                        @forelse($this->comments as $comment)
                            @php $cColors = $comment->user->roleColorClasses(); @endphp
                            <li class="flex gap-3" wire:key="comment-{{ $comment->id }}">
                                <span
                                    class="w-9 h-9 rounded-full {{ $cColors['bg'] }} {{ $cColors['text'] }} text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                    {{ strtoupper(substr($comment->user->name, 0, 2)) }}
                                </span>
                                <div class="min-w-0 flex-1 bg-subtle/40 rounded-xl p-3">
                                    <div class="flex items-center gap-2">
                                        <p class="text-sm font-semibold {{ $cColors['text'] }}">
                                            {{ $comment->user->name }}</p>
                                        <span
                                            class="text-[11px] text-faint">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <p class="text-sm text-text mt-1">{{ $comment->comment }}</p>
                                </div>
                            </li>
                        @empty
                            <li class="text-center py-8">
                                <i class="ph ph-chat-circle-dots text-3xl text-faint"></i>
                                <p class="text-sm text-muted mt-2">No comments yet - be the first to add one.</p>
                            </li>
                        @endforelse
                    </ul>

                    <form wire:submit="postComment" class="mt-4 flex items-start gap-3">
                        <span
                            class="w-9 h-9 rounded-full bg-primary/10 text-primary text-xs font-semibold flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                        </span>
                        <div
                            class="flex-1 rounded-xl border border-border bg-subtle/40 focus-within:border-primary transition-colors">
                            <textarea wire:model="newComment" rows="2" placeholder="Write a comment…"
                                class="w-full px-3 py-2 bg-transparent text-sm text-text placeholder:text-faint focus:outline-none resize-none"></textarea>
                            <div class="flex items-center justify-end px-2 pb-2">
                                <button type="submit"
                                    class="inline-flex items-center gap-1.5 h-8 px-4 rounded-lg bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                                    <i class="ph ph-paper-plane-tilt text-sm"></i>Send
                                </button>
                            </div>
                        </div>
                    </form>
                    @error('newComment')
                        <p class="text-xs text-danger mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Delay Requests panel --}}
                <div data-tab-panel="delay-requests" class="mt-5 hidden space-y-3">
                    @forelse ($this->delayRequests as $dr)
                        @php $rColors = $dr->requester->roleColorClasses(); @endphp
                        <div wire:key="delay-{{ $dr->id }}" class="rounded-xl border border-border p-4">
                            <div class="flex items-start justify-between gap-2">
                                <div class="flex items-center gap-2.5">
                                    <span
                                        class="w-8 h-8 rounded-full {{ $rColors['bg'] }} {{ $rColors['text'] }} text-xs font-semibold flex items-center justify-center flex-shrink-0">
                                        {{ strtoupper(substr($dr->requester->name, 0, 2)) }}
                                    </span>
                                    <div>
                                        <p class="text-sm font-medium text-heading">{{ $dr->requester->name }}</p>
                                        <p class="text-[11px] text-faint">{{ $dr->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <span @class([
                                    'inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[11px] font-medium flex-shrink-0',
                                    'bg-warning-soft text-warning' => $dr->status === 'pending',
                                    'bg-success-soft text-success' => $dr->status === 'approved',
                                    'bg-danger-soft text-danger' => $dr->status === 'rejected',
                                ])>
                                    <i
                                        class="ph {{ match ($dr->status) {'pending' => 'ph-hourglass','approved' => 'ph-check-circle',default => 'ph-x-circle'} }}"></i>
                                    {{ ucfirst($dr->status) }}
                                </span>
                            </div>

                            <p class="text-sm text-text mt-3">{{ $dr->reason }}</p>

                            <div class="flex items-center gap-1.5 text-xs text-muted mt-2">
                                <i class="ph ph-calendar-blank"></i>
                                Requested: {{ $dr->requested_new_due_date->format('M j, Y g:ia') }}
                                <span class="text-faint">(was {{ $dr->original_due_date->format('M j, Y') }})</span>
                            </div>

                            @if ($dr->status === 'pending' && $this->isReviewer)
                                @if ($reviewingDelayId === $dr->id)
                                    <div class="mt-3 space-y-2 pt-3 border-t border-border-subtle">
                                        <textarea wire:model="reviewComment" rows="2" placeholder="Review comment (optional)…"
                                            class="w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
                                        <div class="flex gap-2">
                                            <button wire:click="decideDelay('approved')"
                                                class="h-8 px-3 rounded-lg bg-success text-white text-xs font-medium inline-flex items-center gap-1"><i
                                                    class="ph ph-check"></i>Approve</button>
                                            <button wire:click="decideDelay('rejected')"
                                                class="h-8 px-3 rounded-lg bg-danger text-white text-xs font-medium inline-flex items-center gap-1"><i
                                                    class="ph ph-x"></i>Reject</button>
                                            <button wire:click="$set('reviewingDelayId', null)"
                                                class="h-8 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary">Cancel</button>
                                        </div>
                                    </div>
                                @else
                                    <button wire:click="startReview({{ $dr->id }})"
                                        class="mt-3 h-8 px-3 rounded-lg bg-primary text-white text-xs font-medium">Review
                                        Request</button>
                                @endif
                            @endif

                            @if ($dr->status !== 'pending')
                                <p class="text-xs text-muted mt-2.5 pt-2.5 border-t border-border-subtle">
                                    Reviewed by <span class="font-medium">{{ $dr->reviewer?->name }}</span> on
                                    {{ $dr->reviewed_at?->format('M j, Y') }}
                                    @if ($dr->review_comment)
                                        - "{{ $dr->review_comment }}"
                                    @endif
                                </p>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-8">
                            <i class="ph ph-clock-countdown text-3xl text-faint"></i>
                            <p class="text-sm text-muted mt-2">No delay requests on this task.</p>
                        </div>
                    @endforelse

                    @if ($this->isAssignee && in_array($task->status->value, ['in_progress', 'on_hold']))
                        <div class="rounded-xl border border-dashed border-border p-4 space-y-2">
                            <h4 class="text-sm font-semibold text-heading flex items-center gap-1.5">
                                <i class="ph ph-plus-circle text-base"></i>Request a Delay
                            </h4>
                            <textarea wire:model="delayReason" rows="2" placeholder="Reason for delay…"
                                class="w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
                            @error('delayReason')
                                <p class="text-xs text-danger">{{ $message }}</p>
                            @enderror
                            <div>
                                <label class="text-xs text-muted">New due date</label>
                                <input type="datetime-local" wire:model="delayNewDueDate"
                                    class="mt-1 w-full h-9 px-3 rounded-lg border border-border bg-surface text-sm" />
                                @error('delayNewDueDate')
                                    <p class="text-xs text-danger">{{ $message }}</p>
                                @enderror
                            </div>
                            <button wire:click="submitDelayRequest"
                                class="h-9 px-4 rounded-lg bg-primary text-white text-sm font-medium">Submit
                                Request</button>
                        </div>
                    @endif
                </div>

            </div>
        </div>

        {{-- ==================================================== --}}
        {{-- Properties side panel                                --}}
        {{-- ==================================================== --}}
        <aside class="bg-surface border border-border rounded-2xl p-4 md:p-5 sticky top-20 space-y-5">
            <h3 class="text-sm font-semibold text-heading">Details</h3>

            <div class="space-y-2.5">
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-subtle/40">
                    <span
                        class="w-8 h-8 rounded-lg bg-surface flex items-center justify-center flex-shrink-0 text-text-secondary">
                        <i class="ph ph-flag text-base"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] text-muted">Status</p>
                        <span
                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full {{ $task->status->badgeClasses() }} text-xs font-medium mt-0.5">
                            {{ $task->status->label() }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-subtle/40">
                    <span
                        class="w-8 h-8 rounded-lg bg-surface flex items-center justify-center flex-shrink-0 text-text-secondary">
                        <i class="ph ph-warning text-base"></i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] text-muted">Priority</p>
                        <span
                            class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full {{ $task->priority->badgeClasses() }} text-xs font-medium mt-0.5">
                            {{ $task->priority->label() }}
                        </span>
                    </div>
                </div>

                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-subtle/40">
                    @if ($assignee = $task->currentAssignee())
                        @php $assigneeColors = $assignee->roleColorClasses(); @endphp
                        <span
                            class="w-8 h-8 rounded-full {{ $assigneeColors['bg'] }} {{ $assigneeColors['text'] }} text-xs font-semibold flex items-center justify-center flex-shrink-0">
                            {{ strtoupper(substr($assignee->name, 0, 2)) }}
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] text-muted">Assignee</p>
                            <p class="text-sm text-text font-medium truncate">{{ $assignee->name }}</p>
                        </div>
                    @else
                        <span
                            class="w-8 h-8 rounded-lg bg-surface flex items-center justify-center flex-shrink-0 text-faint">
                            <i class="ph ph-user text-base"></i>
                        </span>
                        <div class="min-w-0 flex-1">
                            <p class="text-[11px] text-muted">Assignee</p>
                            <p class="text-sm text-muted">Unassigned</p>
                        </div>
                    @endif
                </div>

                @php $creatorColors = $task->creator->roleColorClasses(); @endphp
                <div class="flex items-center gap-3 p-2.5 rounded-xl bg-subtle/40">
                    <span
                        class="w-8 h-8 rounded-full {{ $creatorColors['bg'] }} {{ $creatorColors['text'] }} text-xs font-semibold flex items-center justify-center flex-shrink-0">
                        {{ strtoupper(substr($task->creator->name, 0, 2)) }}
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-[11px] text-muted">Assigned By</p>
                        <p class="text-sm text-text font-medium truncate">{{ $task->creator->name }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-2.5">
                    <div class="p-2.5 rounded-xl bg-subtle/40">
                        <p class="text-[11px] text-muted">Started</p>
                        <p class="text-sm text-text font-medium mt-0.5">
                            {{ $task->started_at?->format('M j, Y') ?? '-' }}</p>
                    </div>
                    <div class="p-2.5 rounded-xl bg-subtle/40">
                        <p class="text-[11px] text-muted">Due Date</p>
                        <p class="text-sm font-medium mt-0.5 {{ $task->isOverdue() ? 'text-danger' : 'text-text' }}">
                            {{ $task->due_date->format('M j, Y') }}</p>
                    </div>
                </div>
            </div>

            @if ($task->activeInterruption())
                <div class="rounded-xl bg-warning-soft p-3 flex items-start gap-2">
                    <i class="ph ph-pause-circle text-warning mt-0.5"></i>
                    <p class="text-xs text-warning">This task is paused - the assignee was interrupted by an urgent
                        add-on task.</p>
                </div>
            @endif

            @error('status')
                <p class="text-xs text-danger">{{ $message }}</p>
            @enderror

            {{-- Status action buttons --}}
            @if ($this->availableTransitions->isNotEmpty())
                <div class="pt-4 border-t border-border-subtle space-y-2" x-data>
                    <p class="text-[11px] font-medium text-muted uppercase tracking-wide mb-1">Actions</p>
                    @foreach ($this->availableTransitions as $target)
                        @php
                            $isSendBack = $task->status->value === 'pending_review' && $target->value === 'in_progress';
                            $needsReason = in_array($target->value, ['on_hold', 'cancelled'], true) || $isSendBack;
                            $isReopen = $task->status->value === 'completed';
                        @endphp
                        <button type="button"
                            x-on:click="
                @if($isSendBack)
                    confirmSendBack('{{ $task->due_date->format('Y-m-d\TH:i') }}').then(function(result) {
                        if (result === false) return;
                        $wire.changeStatus('{{ $target->value }}', result.reason, result.dueDate);
                    });
                @else
                confirmStatusChange('{{ $target->label() }}', {
                    needsReason: {{ $needsReason ? 'true' : 'false' }},
                    isReopen: {{ $isReopen ? 'true' : 'false' }}
                }).then(function(result) {
                    if (result === false) return;
                    $wire.changeStatus('{{ $target->value }}', typeof result === 'string' ? result : null);
                }); @endif
        "
                            class="w-full inline-flex items-center justify-center gap-1.5 h-10 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                            <i class="ph ph-arrow-right text-base"></i>Mark as {{ $target->label() }}
                        </button>
                    @endforeach
                </div>
            @endif
        </aside>

        @if ($showEditTaskModal)
            <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
                <div class="w-full max-w-lg bg-surface rounded-2xl border border-border shadow-2xl">
                    <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                        <h3 class="text-base font-semibold text-heading">Edit Task</h3>
                        <button wire:click="$set('showEditTaskModal', false)"
                            class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                            aria-label="Close">
                            <i class="ph ph-x text-lg"></i>
                        </button>
                    </div>

                    <form wire:submit="saveTaskEdit" class="p-5 space-y-4">
                        <div>
                            <label class="text-xs font-medium text-muted">Title</label>
                            <input type="text" wire:model="editTitle"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                            @error('editTitle')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="text-xs font-medium text-muted">Description</label>
                            <textarea wire:model="editDescription" rows="3"
                                class="mt-1 w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="text-xs font-medium text-muted">Priority</label>
                                <select wire:model="editPriority"
                                    class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                                    <option value="low">Low</option>
                                    <option value="medium">Medium</option>
                                    <option value="high">High</option>
                                    <option value="critical">Critical</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-xs font-medium text-muted">Due Date</label>
                                <input type="datetime-local" wire:model="editDueDate"
                                    class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                                @error('editDueDate')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div>
                            <label class="text-xs font-medium text-muted">Assignee</label>
                            <select wire:model.live="editAssigneeId"
                                class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm">
                                @foreach ($this->reassignableUsers as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }}</option>
                                @endforeach
                            </select>
                            @error('editAssigneeId')
                                <p class="text-xs text-danger mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if ($editAssigneeId !== $task->currentAssignee()?->id)
                            <div>
                                <label class="text-xs font-medium text-muted">Reason for reassignment</label>
                                <textarea wire:model="reassignReason" rows="2" placeholder="Why is this task being reassigned?"
                                    class="mt-1 w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
                                @error('reassignReason')
                                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                                @enderror
                            </div>
                        @endif

                        <div class="flex justify-end gap-2 pt-2">
                            <button type="button" wire:click="$set('showEditTaskModal', false)"
                                class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                            <button type="submit"
                                class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong">Save
                                Changes</button>
                        </div>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div>
