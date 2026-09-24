<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div
        class="bg-surface border border-border rounded-2xl p-4 md:p-5 flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-2xl bg-primary/10 text-primary flex items-center justify-center flex-shrink-0">
                <i class="ph ph-users-three text-xl"></i>
            </div>
            <div>
                <h2 class="text-base font-semibold text-heading">Clients</h2>
                <p class="text-sm text-muted mt-0.5">Manage client accounts and their delivery plans</p>
            </div>
        </div>
        @can('create', \App\Models\Client::class)
            <div class="flex gap-2">
                <button wire:click="openCreate"
                    class="inline-flex items-center gap-1.5 h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium shadow-sm hover:bg-primary hover:shadow transition-all bs-buttons">
                    <i class="ph ph-plus"></i>Add Client
                </button>
                @livewire('content-type-manager')
            </div>
        @endcan
    </div>

    @if ($this->expiringClients->isNotEmpty())
        <div
            class="rounded-xl border border-warning/30 bg-warning-soft/40 p-3.5 flex items-start justify-between gap-3 flex-wrap">
            <div class="flex items-start gap-2.5">
                <i class="ph ph-bell-ringing text-warning text-lg mt-0.5"></i>
                <p class="text-sm text-warning">
                    <strong>{{ $this->expiringClients->count() }} client
                        engagement{{ $this->expiringClients->count() !== 1 ? 's are' : ' is' }}</strong> ending within
                    the next 5 days.
                </p>
            </div>
            <button wire:click="$set('showExpiringModal', true)"
                class="h-8 px-3 rounded-lg bg-danger text-white text-xs font-medium hover:bg-amber-600 transition-colors flex-shrink-0">
                View Clients
            </button>
        </div>
    @endif

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

    <div class="flex items-center justify-between gap-3 flex-wrap">
        <div class="flex items-center gap-1 p-1 rounded-xl bg-subtle  w-fit">
            @foreach (['all' => 'All', 'active' => 'Active', 'hold' => 'On Hold', 'inactive' => 'Inactive'] as $value => $label)
                <button wire:click="$set('statusFilter', '{{ $value }}')"
                    data-active="{{ $statusFilter === $value ? 'true' : 'false' }}"
                    class="inline-flex items-center gap-1.5 h-8 px-3.5 rounded-lg text-xs font-semibold text-muted hover:text-heading data-[active=true]:bg-surface data-[active=true]:text-primary data-[active=true]:shadow-sm transition-all">
                    {{ $label }}
                </button>
            @endforeach
        </div>

        <div
            class="topbar-search relative hidden md:flex items-center w-56 xl:w-72 h-10 px-3 rounded-xl bg-surface border border-border group focus-within:border-primary/50 focus-within:ring-4 focus-within:ring-primary/10 transition-all">
            <i
                class="ph ph-magnifying-glass text-faint text-lg group-focus-within:text-primary transition-colors flex-shrink-0"></i>
            <input id="topbar-search" type="text" placeholder="Search clients..."
                wire:model.live.debounce.300ms="search"
                class="flex-1 min-w-0 h-full bg-transparent px-2.5 text-sm text-text placeholder:text-faint focus:outline-none" />
        </div>
    </div>

    @if ($this->clients->isEmpty())
        <div class="bg-surface border border-border rounded-xl p-12 text-center">
            <div class="w-12 h-12 rounded-full bg-subtle flex items-center justify-center mx-auto mb-3">
                <i class="ph ph-users-three text-xl text-faint"></i>
            </div>
            <p class="text-sm font-medium text-heading">No clients found</p>
            <p class="text-xs text-muted mt-1">Try adjusting your filters or search.</p>
        </div>
    @else
        <div class="bg-surface border border-border rounded-xl shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm border-collapse min-w-[860px]">
                    <thead>
                        <tr class="text-left border-b border-border bg-mblack" style="line-height: 35px;">
                            <th
                                class="px-5 py-3.5 font-semibold text-[10px] tracking-wide uppercase text-white whitespace-nowrap">
                                Client</th>
                            <th
                                class="px-4 py-3.5 font-semibold text-[10px] tracking-wide uppercase text-white whitespace-nowrap">
                                Status</th>

                            @foreach ($this->allPlatforms as $platform)
                                <th
                                    class="px-3 py-3.5 font-semibold text-[10px] tracking-wide uppercase text-white text-center whitespace-nowrap">
                                    <span class="inline-flex items-center gap-1.5">
                                        <i
                                            class="ph {{ match (strtolower($platform)) {
                                                'facebook' => 'ph-facebook-logo',
                                                'instagram' => 'ph-instagram-logo',
                                                'linkedin' => 'ph-linkedin-logo',
                                                default => 'ph-globe',
                                            } }} text-sm text-white"></i>
                                        {{ $platform }}
                                    </span>
                                </th>
                            @endforeach
                            <th
                                class="px-4 py-3.5 font-semibold text-[10px] tracking-wide uppercase text-white whitespace-nowrap">
                                Engagement</th>
                            <th class="px-4 py-3.5 w-12"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-border-subtle">
                        @foreach ($this->clients as $client)
                            @php
                                $itemsByPlatform = $client->planItems->load('contentType')->groupBy('platform');
                                $statusMeta = match ($client->status) {
                                    'active' => [
                                        'label' => 'Active',
                                        'rowBg' => 'bg-active-soft',
                                        'dotColor' => '#408F4F',
                                        'textColor' => '#2F6B39',
                                        'accent' => 'border-active',
                                    ],
                                    'hold' => [
                                        'label' => 'On Hold',
                                        'rowBg' => 'bg-hold-soft',
                                        'dotColor' => '#BC9123',
                                        'textColor' => '#8F6F1B',
                                        'accent' => 'border-hold',
                                    ],
                                    'inactive' => [
                                        'label' => 'Inactive',
                                        'rowBg' => 'bg-inactive-soft',
                                        'dotColor' => '#D45656',
                                        'textColor' => '#B23A3A',
                                        'accent' => 'border-inactive',
                                    ],
                                    default => [
                                        'label' => ucfirst($client->status),
                                        'rowBg' => 'bg-subtle',
                                        'dotColor' => '#8B87A3',
                                        'textColor' => '#5B5675',
                                        'accent' => 'border-faint',
                                    ],
                                };
                                $initials = collect(explode(' ', $client->name))
                                    ->map(fn($w) => mb_substr($w, 0, 1))
                                    ->take(2)
                                    ->implode('');
                                $daysRemaining = $client->end_date
                                    ? now()
                                        ->startOfDay()
                                        ->diffInDays($client->end_date->copy()->startOfDay(), false)
                                    : null;
                            @endphp
                            <tr wire:key="client-row-{{ $client->id }}"
                                class="align-top transition-colors group border-l-[3px] {{ $statusMeta['accent'] }} {{ $statusMeta['rowBg'] }} ct-row-hover">
                                <td class="px-5 py-3.5 whitespace-nowrap">
                                    <div class="flex items-center gap-3">

                                        <div class="min-w-0">
                                            @if ($client->is_priority)
                                                <i class="ph ph-star text-yellow-300" title="Priority Client"></i>
                                            @endif
                                            <a href="{{ route('clients.show', $client) }}" target="_blank"
                                                class="inline-flex items-center gap-1 font-normal text-heading hover:text-primary transition-colors max-w-full">
                                                <span class="truncate text-[12px]">{{ $client->name }}</span>
                                                <i
                                                    class="ph ph-arrow-square-out text-sm opacity-1 group-hover:opacity-60 transition-opacity flex-shrink-0"></i>
                                            </a>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-4 py-3.5 whitespace-nowrap">
                                    <div class="inline-flex items-center gap-1.5 px-2 py-1 rounded-full text-[11px] font-medium bg-surface/60"
                                        style="color: {{ $statusMeta['textColor'] }};">
                                        <span class="w-1.5 h-1.5 rounded-full"
                                            style="background-color: {{ $statusMeta['dotColor'] }};"></span>
                                        {{ $statusMeta['label'] }}
                                    </div>
                                </td>

                                @foreach ($this->allPlatforms as $platform)
                                    @php $itemsForPlatform = $itemsByPlatform->get($platform, collect()); @endphp
                                    <td class="px-3 py-3.5 text-center whitespace-nowrap">
                                        @if ($itemsForPlatform->isNotEmpty())
                                            <div class="inline-flex flex-col gap-2 text-left w-[110px]">
                                                @foreach ($itemsForPlatform as $item)
                                                    @php
                                                        $done = $client->completedCountFor(
                                                            $platform,
                                                            $item->content_type_id,
                                                        );
                                                        $total = max((int) $item->quantity, 0);
                                                        $pct = $total > 0 ? min(100, round(($done / $total) * 100)) : 0;
                                                    @endphp
                                                    <div>
                                                        <div
                                                            class="flex items-center justify-between text-[11px] mb-0 mt-1">
                                                            <span
                                                                class="text-muted font-medium">{{ $item->contentType->name }}
                                                                &nbsp;</span>
                                                            <span
                                                                class="font-semibold {{ $pct >= 100 ? 'text-active' : 'text-heading' }}">{{ $done }}/{{ $total }}</span>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @else
                                            <span class="text-faint">-</span>
                                        @endif
                                    </td>
                                @endforeach

                                <td class="px-4 py-3.5 whitespace-nowrap text-text-secondary">
                                    @if ($client->start_date)
                                        <div class="flex items-center gap-1.5 text-sm">
                                            <i class="ph ph-calendar-blank text-sm text-muted"></i>
                                            <span style="font-size: 12px;">{{ $client->start_date->format('M j, Y') }}
                                                –
                                                {{ $client->end_date?->format('M j, Y') ?? 'Ongoing' }}</span>
                                        </div>
                                        @if ($daysRemaining !== null)
                                            @php
                                                $remainingMeta = match (true) {
                                                    $daysRemaining < 0 => [
                                                        'label' => abs($daysRemaining) . ' days overdue',
                                                        'class' => 'bg-inactive/10 text-inactive',
                                                    ],
                                                    $daysRemaining === 0 => [
                                                        'label' => 'Ends today',
                                                        'class' => 'bg-hold/10 text-hold',
                                                    ],
                                                    $daysRemaining <= 7 => [
                                                        'label' =>
                                                            $daysRemaining .
                                                            ' day' .
                                                            ($daysRemaining === 1 ? '' : 's') .
                                                            ' left',
                                                        'class' => 'bg-hold/10 text-hold',
                                                    ],
                                                    default => [
                                                        'label' => $daysRemaining . ' days left',
                                                        'class' => 'bg-subtle text-faint',
                                                    ],
                                                };
                                            @endphp
                                            <span
                                                class="inline-block mt-1 px-1.5 py-0.5 rounded-md text-[10px] font-medium {{ $remainingMeta['class'] }}">
                                                {{ $remainingMeta['label'] }}
                                            </span>
                                        @else
                                            <span
                                                class="inline-block mt-1 px-1.5 py-0.5 rounded-md bg-subtle text-muted text-[10px] font-medium">
                                                Ongoing
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3.5 text-right">
                                    @can('manage', $client)
                                        <button wire:click="openEdit({{ $client->id }})"
                                            class="w-8 h-8 rounded-lg inline-flex items-center justify-center text-muted hover:text-primary hover:bg-primary/10 transition-colors"
                                            aria-label="Edit">
                                            <i class="ph ph-pencil-simple text-base"></i>
                                        </button>
                                    @endcan
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- Create / Edit modal --}}
    @if ($showModal)
        <div class="fixed inset-0 z-[70] flex items-center justify-center bg-overlay p-4">
            <div class="w-full max-w-2xl bg-surface rounded-2xl border border-border shadow-2xl">
                <div class="flex items-center justify-between gap-3 px-5 h-14 border-b border-border-subtle">
                    <h3 class="text-base font-semibold text-heading">{{ $editingId ? 'Edit Client' : 'Add Client' }}
                    </h3>
                    <button wire:click="cancel"
                        class="w-8 h-8 rounded-lg flex items-center justify-center text-muted hover:bg-subtle"
                        aria-label="Close">
                        <i class="ph ph-x text-lg"></i>
                    </button>
                </div>

                <form wire:submit="save" class="p-5 space-y-3.5" style="max-height: 80vh; overflow-y: auto;">
                    <div class="grid grid-cols-2 gap-3">
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
                    </div>

                    <div class="grid grid-cols-2 gap-3">
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

                    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 0.75rem;">
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
                    }">
                        <div class="grid grid-cols-2 gap-3">
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

                        <p x-show="days !== null" x-cloak class="text-xs text-muted mt-1.5">
                            <i class="ph ph-clock"></i> Engagement length: <span class="font-medium text-text"
                                x-text="days"></span> day<span x-show="days !== 1">s</span>
                        </p>
                        <p x-show="start && end && days === null" x-cloak class="text-xs text-danger mt-1.5">
                            End date must be after start date.
                        </p>
                    </div>

                    <div>
                        <label class="text-xs font-medium text-muted">Notes</label>
                        <textarea wire:model="notes" rows="2"
                            class="mt-1 w-full px-3 py-2 rounded-lg border border-border bg-surface text-sm"></textarea>
                    </div>

                    {{-- Plan items --}}
                    <div class="pt-3 border-t border-border-subtle">
                        <div class="flex items-center justify-between mb-2">
                            <label class="text-xs font-medium text-muted">Delivery Plan</label>
                            <button type="button" wire:click="addPlatformBlock"
                                class="text-xs text-primary font-medium">+ Add platform</button>
                        </div>

                        <div class="space-y-3">
                            @foreach ($planItems as $platformIndex => $block)
                                <div class="rounded-xl border border-border p-3"
                                    wire:key="platform-block-{{ $platformIndex }}">
                                    <div class="flex items-center gap-2 mb-2.5">
                                        <select wire:model="planItems.{{ $platformIndex }}.platform"
                                            class="flex-1 h-9 px-2 rounded-lg border border-border bg-surface text-sm">
                                            <option value="">Select platform…</option>
                                            @foreach (\App\Enums\Platform::options() as $option)
                                                <option value="{{ $option }}">{{ $option }}</option>
                                            @endforeach
                                        </select>
                                        @if (count($planItems) > 1)
                                            <button type="button"
                                                wire:click="removePlatformBlock({{ $platformIndex }})"
                                                class="text-muted hover:text-danger flex-shrink-0"
                                                aria-label="Remove platform">
                                                <i class="ph ph-trash"></i>
                                            </button>
                                        @endif
                                    </div>

                                    <div class="space-y-1.5 pl-1">
                                        @foreach ($block['items'] as $itemIndex => $item)
                                            <div style="display: grid; grid-template-columns: 1fr 70px 1.3fr auto auto; gap: 0.5rem; align-items: center;"
                                                wire:key="platform-{{ $platformIndex }}-item-{{ $itemIndex }}">
                                                <select
                                                    wire:model="planItems.{{ $platformIndex }}.items.{{ $itemIndex }}.content_type_id"
                                                    class="h-8 px-2 rounded-lg border border-border bg-surface text-xs">
                                                    <option value="">Category…</option>
                                                    @foreach ($this->contentTypeOptions as $ct)
                                                        <option value="{{ $ct->id }}">{{ $ct->name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <input type="number" min="0"
                                                    wire:model="planItems.{{ $platformIndex }}.items.{{ $itemIndex }}.quantity"
                                                    placeholder="Qty"
                                                    class="h-8 px-2 rounded-lg border border-border bg-surface text-xs w-full" />
                                                <input type="text"
                                                    wire:model="planItems.{{ $platformIndex }}.items.{{ $itemIndex }}.notes"
                                                    placeholder="Notes"
                                                    class="h-8 px-2 rounded-lg border border-border bg-surface text-xs w-full" />
                                                <label
                                                    class="flex items-center justify-center h-8 w-8 rounded-lg border border-border cursor-pointer"
                                                    title="Setup Done">
                                                    <input type="checkbox"
                                                        wire:model="planItems.{{ $platformIndex }}.items.{{ $itemIndex }}.setup_done"
                                                        class="rounded" />
                                                </label>
                                                @if (count($block['items']) > 1)
                                                    <button type="button"
                                                        wire:click="removeCategoryRow({{ $platformIndex }}, {{ $itemIndex }})"
                                                        class="h-8 w-8 flex items-center justify-center text-muted hover:text-danger flex-shrink-0"
                                                        aria-label="Remove category">
                                                        <i class="ph ph-trash text-sm"></i>
                                                    </button>
                                                @else
                                                    <span></span>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <button type="button" wire:click="addCategoryRow({{ $platformIndex }})"
                                        class="text-xs text-primary font-medium mt-2 pl-1">+ Add category</button>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex justify-end gap-2 pt-2">
                        <button type="button" wire:click="cancel"
                            class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary">Cancel</button>
                        <button type="submit"
                            class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong">Save
                            Client</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
