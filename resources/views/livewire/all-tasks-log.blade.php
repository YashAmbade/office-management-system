<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5">
        <h2 class="text-base font-semibold text-heading">All Tasks</h2>
        <p class="text-sm text-muted mt-0.5">Full task log including deleted tasks, for oversight and audit purposes.</p>

        <div class="flex items-center gap-1 p-1 rounded-xl bg-subtle w-fit mt-4">
            @foreach(['all' => 'All', 'active' => 'Active', 'deleted' => 'Deleted'] as $value => $label)
                <button wire:click="$set('filter', '{{ $value }}')" data-active="{{ $filter === $value ? 'true' : 'false' }}"
                    class="inline-flex items-center gap-1.5 h-8 px-3 rounded-lg text-xs font-medium text-muted data-[active=true]:bg-surface data-[active=true]:text-heading data-[active=true]:shadow-sm transition-colors">
                    {{ $label }}
                </button>
            @endforeach
        </div>
    </div>

    <div class="bg-surface border border-border rounded-2xl overflow-x-auto">
        <table class="w-full min-w-[900px] text-sm">
            <thead>
                <tr class="text-left text-xs text-muted border-b border-border">
                    <th class="px-4 py-3 font-medium">Task</th>
                    <th class="px-4 py-3 font-medium">Assignee</th>
                    <th class="px-4 py-3 font-medium">Assigned By</th>
                    <th class="px-4 py-3 font-medium">Status</th>
                    <th class="px-4 py-3 font-medium">Deleted By</th>
                    <th class="px-4 py-3 font-medium">Reason</th>
                    <th class="px-4 py-3 font-medium"></th>
                </tr>
            </thead>
            <tbody>
                @foreach($this->tasks as $task)
                    <tr class="border-b border-border-subtle last:border-0 {{ $task->trashed() ? 'bg-danger-soft/20' : '' }}" wire:key="log-{{ $task->id }}">
                        <td class="px-4 py-3">
                            @if(!$task->trashed())
                                <a href="{{ route('tasks.show', $task) }}" class="font-medium text-heading hover:text-primary">{{ $task->title }}</a>
                            @else
                                <span class="font-medium text-muted line-through">{{ $task->title }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-muted">{{ $task->currentAssignee()?->name ?? '-' }}</td>
                        <td class="px-4 py-3 text-muted">{{ $task->creator->name }}</td>
                        <td class="px-4 py-3">
                            @if($task->trashed())
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full bg-danger-soft text-danger text-[11px] font-medium">Deleted</span>
                            @else
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full {{ $task->status->badgeClasses() }} text-[11px] font-medium">{{ $task->status->label() }}</span>
                            @endif
                        </td>
                        <td class="px-4 py-3 text-muted text-xs">
                            @if($task->deletedBy)
                                {{ $task->deletedBy->name }}<br>
                                <span class="text-faint">{{ $task->deleted_at->format('M j, Y g:ia') }}</span>
                            @else
                                -
                            @endif
                        </td>
                        <td class="px-4 py-3 text-muted text-xs max-w-[200px] truncate" title="{{ $task->deletion_reason }}">{{ $task->deletion_reason ?? '-' }}</td>
                        <td class="px-4 py-3 text-right">
                            @if($task->trashed())
                                <button wire:click="restoreTask({{ $task->id }})" class="text-xs text-primary font-medium hover:text-primary-strong">Restore</button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
