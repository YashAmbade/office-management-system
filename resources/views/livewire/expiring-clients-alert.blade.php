<div>
    @if ($showExpiringModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-lg bg-surface rounded-2xl border border-border shadow-2xl">
                <div
                    class="flex items-center justify-between gap-3 px-5 h-14 rounded-t-2xl border-b border-border-subtle bg-gradient-to-r from-rose-50 via-orange-50 to-amber-50">
                    <h3 class="text-base font-semibold text-heading flex items-center gap-2">
                        <i class="ph ph-bell-ringing text-rose-500"></i>Plan Expires Soon
                    </h3>
                    <button wire:click="$set('showExpiringModal', false)"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-white/60 transition-colors"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <div class="p-5 max-h-[70vh] overflow-y-auto space-y-2.5">
                    @forelse($this->expiringClients as $client)
                        @php
                            $daysLeft = now()
                                ->startOfDay()
                                ->diffInDays($client->end_date->copy()->startOfDay(), false);
                        @endphp
                        <a href="{{ route('clients.show', $client) }}" target="_blank"
                            class="flex items-center justify-between gap-3 rounded-xl border border-border p-3.5 hover:border-warning/40 transition-colors">
                            <div class="min-w-0">
                                <p class="text-sm font-medium text-heading">{{ $client->name }}</p>
                                <p class="text-xs text-muted mt-0.5">Ends {{ $client->end_date->format('M j, Y') }}</p>
                            </div>
                            <span @class([
                                'inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-medium flex-shrink-0',
                                'bg-danger-soft text-danger' => $daysLeft <= 1,
                                'bg-warning-soft text-warning' => $daysLeft > 1,
                            ])>
                                {{ $daysLeft <= 0 ? 'Ends today' : $daysLeft . ' day' . ($daysLeft !== 1 ? 's' : '') . ' left' }}
                            </span>
                        </a>
                    @empty
                        <p class="text-sm text-muted text-center py-6">Nothing ending soon.</p>
                    @endforelse
                </div>
            </div>
        </div>
    @endif
</div>
