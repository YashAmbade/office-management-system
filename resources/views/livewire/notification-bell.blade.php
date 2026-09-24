<div class="relative" x-data="{ open: false }" wire:poll.15s="$refresh">
    <button type="button" x-on:click="open = !open" x-on:click.outside="open = false"
        class="topbar-icon-btn relative w-9 h-9 sm:w-10 sm:h-10 border border-border bs-buttons"
        aria-label="Notifications">
        @if ($this->unreadCount > 0)
            <span
                class="absolute -top-0.5 -right-0.5 min-w-[16px] h-4 px-1 rounded-full bg-danger text-white text-[10px] font-semibold leading-none flex items-center justify-center ring-2 ring-surface">
                {{ $this->unreadCount > 9 ? '9+' : $this->unreadCount }}
            </span>
        @endif
        <i class="ph ph-bell text-lg sm:text-xl"></i>
    </button>

    <div x-show="open" x-cloak x-transition
        class="fixed left-2 right-2 top-16 sm:absolute sm:left-auto sm:right-0 sm:top-full sm:mt-3 sm:w-80 bg-surface rounded-2xl shadow-xl border border-border z-50">
        <div class="p-4 border-b border-border flex items-center justify-between gap-2">
            <h3 class="font-semibold text-heading">
                Notifications
                @if ($this->unreadCount > 0)
                    <span
                        class="ml-1 align-middle text-[11px] font-medium text-primary bg-primary-soft rounded-full px-1.5 py-0.5">
                        {{ $this->unreadCount }} new
                    </span>
                @endif
            </h3>
            @if ($this->unreadCount > 0)
                <button type="button" wire:click="markAllRead"
                    class="text-xs text-primary hover:text-primary-strong font-medium flex-shrink-0">
                    Mark all read
                </button>
            @endif
        </div>

        <div class="max-h-[60vh] sm:max-h-96 overflow-y-auto divide-y divide-border-subtle">
            @forelse($this->notifications as $notification)
                <a href="{{ $notification->data['url'] ?? '#' }}" wire:click="markAsRead('{{ $notification->id }}')"
                    class="w-full text-left flex items-start gap-3 p-4 hover:bg-subtle transition-colors {{ $notification->read_at ? '' : 'bg-primary-soft/20' }}">
                    <span
                        class="w-9 h-9 rounded-full bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                        <i
                            class="ph {{ match ($notification->data['type'] ?? '') {
                                'task_assigned' => 'ph-clipboard-text',
                                'delay_request_submitted' => 'ph-clock-countdown',
                                'delay_request_reviewed' => 'ph-check-circle',
                                'add_on_linked' => 'ph-lightning',
                                'task_sent_back' => 'ph-arrow-u-up-left',
                                default => 'ph-bell',
                            } }} text-lg">
                            </i>
                    </span>
                    <div class="min-w-0 flex-1">
                        <p class="text-sm text-text">{{ $notification->data['message'] ?? '' }}</p>
                        <p class="text-[11px] text-muted mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                    </div>
                    @unless ($notification->read_at)
                        <span class="w-2 h-2 rounded-full bg-primary flex-shrink-0 mt-1.5"></span>
                    @endunless
                </a>
            @empty
                <p class="text-sm text-muted text-center py-8">No notifications yet.</p>
            @endforelse
        </div>
    </div>
</div>
