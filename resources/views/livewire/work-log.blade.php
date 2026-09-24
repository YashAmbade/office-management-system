@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
@endpush
<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
     @if (auth()->user()->hasRole(['Super Admin', 'Manager']))
    <a href="{{ route('work-log.report') }}"
        class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors inline-flex items-center gap-1.5">
        <i class="ph ph-table"></i>All Entries
    </a>
    <a href="{{ route('work-log.clients') }}"
        class="h-10 px-4 rounded-xl border border-border text-sm font-medium text-text-secondary hover:border-border-strong transition-colors inline-flex items-center gap-1.5">
        <i class="ph ph-briefcase"></i>Manage Clients
    </a>
@endif

    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5">
        <div class="flex items-center justify-between mb-1">
            <h3 class="text-sm font-semibold text-heading">
                {{ $editingId ? 'Edit Entry' : 'Log New Entry' }}
            </h3>
            @if ($editingId)
                <button type="button" wire:click="cancelEdit"
                    class="text-xs text-muted hover:text-heading inline-flex items-center gap-1">
                    <i class="ph ph-x"></i> Cancel edit
                </button>
            @endif
        </div>

        <form wire:submit="submit" class="space-y-4">
            <div>
    <label class="text-xs font-medium text-muted">Client</label>

    <div x-data="{
    open: false,
    search: '',
    highlighted: 0,
    clientId: @entangle('clientId'),
    clients: {{ $this->myClients->map(fn($c) => ['id' => $c->id, 'name' => $c->name])->toJson() }},
    get selectedName() {
        return this.clients.find(c => c.id === this.clientId)?.name ?? '';
    },
    get filtered() {
        if (!this.search) return this.clients;
        return this.clients.filter(c => c.name.toLowerCase().includes(this.search.toLowerCase()));
    },
    get exactMatch() {
        return this.clients.find(c => c.name.toLowerCase() === this.search.trim().toLowerCase());
    },
    get canAddNew() {
        return this.search.trim().length > 0 && !this.exactMatch;
    },
    select(client) {
        this.clientId = client.id;
        this.search = '';
        this.open = false;
        this.highlighted = 0;
    },
    async addNew() {
        if (!this.canAddNew) return;
        const name = this.search.trim();
        this.open = false;
        this.search = '';
        await $wire.set('newClientName', name);
        await $wire.quickAddClient();
    },
    moveDown() {
        const max = this.filtered.length - 1 + (this.canAddNew ? 1 : 0);
        if (this.highlighted < max) this.highlighted++;
    },
    moveUp() {
        if (this.highlighted > 0) this.highlighted--;
    },
    chooseHighlighted() {
        if (this.highlighted < this.filtered.length && this.filtered[this.highlighted]) {
            this.select(this.filtered[this.highlighted]);
        } else if (this.canAddNew) {
            this.addNew();
        }
    }
}" x-on:input="highlighted = 0"
   x-on:work-log-client-saved.window="
       if (!clients.find(c => c.id === $event.detail.id)) {
           clients.push({ id: $event.detail.id, name: $event.detail.name });
           clients.sort((a, b) => a.name.localeCompare(b.name));
       }
   "
   class="relative mt-1">

        <input type="text" x-model="search"
    x-on:focus="open = true; search = ''"
    x-on:click.stop="open = true"
    x-on:keydown.down.prevent="open = true; moveDown()"
    x-on:keydown.up.prevent="open = true; moveUp()"
    x-on:keydown.enter.prevent="chooseHighlighted()"
    x-on:keydown.escape="open = false; search = ''"
    :placeholder="selectedName || 'Search or add a client…'"
    :class="selectedName ? 'placeholder:text-heading placeholder:font-medium' : 'placeholder:text-muted'"
    class="w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm text-heading" />

        <div x-show="open" x-cloak x-on:click.outside="open = false; search = ''"
            class="absolute z-20 mt-1 w-full rounded-lg border border-border bg-surface shadow-lg"
            style="max-height: 240px; overflow-y: auto;z-index:9;">

            <template x-if="filtered.length === 0 && !canAddNew">
                <p class="px-3 py-2 text-xs text-muted">No clients found.</p>
            </template>

            <template x-for="(client, index) in filtered" :key="client.id">
                <button type="button" x-on:click.stop="select(client)" x-on:mouseenter="highlighted = index"
                    :class="highlighted === index ? 'bg-subtle' : ''"
                    class="w-full text-left px-3 py-2 text-sm text-text hover:bg-subtle transition-colors"
                    x-text="client.name"></button>
            </template>

            <template x-if="canAddNew">
                <button type="button" x-on:click.stop="addNew()"
                    :class="highlighted === filtered.length ? 'bg-primary-soft' : ''"
                    class="w-full text-left px-3 py-2 text-sm text-primary hover:bg-primary-soft transition-colors border-t border-border-subtle flex items-center gap-1.5">
                    <i class="ph ph-plus"></i>
                    <span>Add "<span x-text="search.trim()"></span>" as new client</span>
                </button>
            </template>
        </div>
    </div>

    @error('clientId')
        <p class="text-xs text-danger mt-1">{{ $message }}</p>
    @enderror
    @error('newClientName')
        <p class="text-xs text-danger mt-1">{{ $message }}</p>
    @enderror

    @if ($this->myClients->isEmpty())
        <p class="text-xs text-muted mt-1">No clients yet — type a name above to add your first one.</p>
    @endif
</div>

            <div>
                <label class="text-xs font-medium text-muted">Date</label>
                <input type="date" wire:model="loggedAtDate" max="{{ now()->format('Y-m-d') }}"
                    class="mt-1 w-full h-10 px-3 rounded-lg border border-border bg-surface text-sm" />
                @error('loggedAtDate')
                    <p class="text-xs text-danger mt-1">{{ $message }}</p>
                @enderror
                <p class="text-[11px] text-muted mt-1">Defaults to today - change it if you're logging something from an
                    earlier day.</p>
            </div>

            <div>
    <label class="text-xs font-medium text-muted">What did you work on?</label>

    <div wire:ignore x-data="quillField(@entangle('description').live)" x-init="init()" class="mt-1 wl-editor">
    <div x-ref="toolbar">
        <span class="ql-formats">
            <select class="ql-header">
                <option value="">Normal</option>
                <option value="2">Heading</option>
                <option value="3">Subheading</option>
            </select>
        </span>
        <span class="ql-formats">
            <button class="ql-bold" title="Bold"></button>
            <button class="ql-italic" title="Italic"></button>
            <button class="ql-underline" title="Underline"></button>
            <button class="ql-strike" title="Strikethrough"></button>
        </span>
        <span class="ql-formats">
            <select class="ql-color" title="Text color"></select>
            <select class="ql-background" title="Highlight"></select>
        </span>
        <span class="ql-formats">
            <button class="ql-list" value="ordered" title="Numbered list"></button>
            <button class="ql-list" value="bullet" title="Bullet list"></button>
            <button class="ql-indent" value="-1" title="Decrease indent"></button>
            <button class="ql-indent" value="+1" title="Increase indent"></button>
        </span>
        <span class="ql-formats">
            <button class="ql-clean" title="Clear formatting"></button>
        </span>
    </div>
    <div x-ref="editor"></div>
</div>

    @error('description')
        <p class="text-xs text-danger mt-1">{{ $message }}</p>
    @enderror
</div>

@once
    <script>
        function quillField(description) {
            return {
                description: description,
                quill: null,
                init() {
                    this.quill = new Quill(this.$refs.editor, {
                        theme: 'snow',
                        modules: { toolbar: this.$refs.toolbar },
                    });

                    if (this.description) {
                        this.quill.clipboard.dangerouslyPasteHTML(this.description);
                    }

                    this.quill.on('text-change', () => {
                        const html = this.quill.root.innerHTML === '<p><br></p>' ? '' : this.quill.root.innerHTML;
                        this.description = html;
                    });

                    // Keep the editor in sync when the server changes description
                    // (e.g. clicking "Edit" on an existing entry, or after submit clears it).
                    this.$watch('description', (value) => {
                        const current = this.quill.root.innerHTML === '<p><br></p>' ? '' : this.quill.root.innerHTML;
                        if (value !== current) {
                            this.quill.clipboard.dangerouslyPasteHTML(value || '');
                        }
                    });
                }
            }
        }
    </script>
@endonce

            <button type="submit"
                class="h-10 px-4 rounded-xl bg-primary text-white text-sm font-medium hover:bg-primary-strong transition-colors">
                <i class="ph ph-{{ $editingId ? 'floppy-disk' : 'plus' }}"></i>
                {{ $editingId ? 'Save Changes' : 'Log Entry' }}
            </button>
        </form>
    </div>

    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5">
        <div class="flex items-center justify-between gap-3 flex-wrap mb-4">
            <h3 class="text-sm font-semibold text-heading">Your Entries</h3>

            <div class="flex items-center gap-2 flex-wrap">
                <input type="date" wire:model.live="rangeStart" max="{{ now()->format('Y-m-d') }}"
                    class="h-9 px-3 rounded-lg border border-border bg-surface text-sm" />
                <span class="text-muted text-xs">to</span>
                <input type="date" wire:model.live="rangeEnd" max="{{ now()->format('Y-m-d') }}"
                    class="h-9 px-3 rounded-lg border border-border bg-surface text-sm" />
                <button type="button" wire:click="setLast7Days"
                    class="h-9 px-3 rounded-lg border border-border text-xs font-medium text-text-secondary hover:border-border-strong transition-colors">
                    Last 7 days
                </button>
            </div>
        </div>

        @if (empty($this->myRangeDays))
            <p class="text-sm text-danger text-center py-6">Start date must be before or equal to end date.</p>
        @else
            <div class="overflow-x-auto rounded-xl border border-border-subtle">
    <table class="w-full text-sm border-collapse min-w-[720px]">
        <thead>
            <tr class="bg-subtle/50 text-left text-xs text-muted">
                <th class="px-4 py-3 font-medium border-r border-border-subtle whitespace-nowrap">Day</th>
                <th class="px-4 py-3 font-medium border-r border-border-subtle whitespace-nowrap">Date</th>
                <th class="px-4 py-3 font-medium border-r border-border-subtle whitespace-nowrap">Client</th>
                <th class="px-4 py-3 font-medium">Work Done</th>
                <th class="px-2 py-3 w-10"></th>
            </tr>
        </thead>
        <tbody>
            @foreach ($this->myRangeDays as $day)
                @php
                    $dayKey = $day->format('Y-m-d');
                    $entries = $this->myEntriesByDate->get($dayKey, collect());
                    $isWeekend = $day->isWeekend();
                    $rowSpan = max($entries->count(), 1);
                @endphp

                @forelse ($entries as $entry)
                    <tr wire:key="entry-{{ $entry->id }}"
                        class="border-t border-border-subtle {{ $isWeekend ? 'bg-subtle/30' : '' }} {{ $editingId === $entry->id ? 'bg-subtle/40' : '' }}">
                        @if ($loop->first)
                            <td rowspan="{{ $rowSpan }}"
                                class="px-4 py-3 font-medium text-heading whitespace-nowrap border-r border-border-subtle align-top">
                                {{ $day->format('l') }}
                            </td>
                            <td rowspan="{{ $rowSpan }}"
                                class="px-4 py-3 text-muted whitespace-nowrap border-r border-border-subtle align-top">
                                {{ $day->format('d-m-Y') }}
                            </td>
                        @endif
                        <td class="px-4 py-3 font-semibold text-heading whitespace-nowrap border-r border-border-subtle align-top">
                            {{ $entry->client->name }}
                        </td>
                        <td class="align-top">
                            <div class="text-sm text-text wl-editor">
                                <div class="ql-editor !p-0 !min-h-0">
                                    {!! str_contains($entry->description, '<') ? $entry->description : nl2br(e($entry->description)) !!}
                                </div>
                            </div>
                        </td>
                        <td class="px-2 py-3 align-top text-right">
                            <button type="button" wire:click="edit({{ $entry->id }})"
                                class="text-muted hover:text-primary" aria-label="Edit entry">
                                <i class="ph ph-pencil-simple"></i>
                            </button>
                        </td>
                    </tr>
                @empty
                    <tr wire:key="day-{{ $dayKey }}"
                        class="border-t border-border-subtle {{ $isWeekend ? 'bg-subtle/30' : '' }}">
                        <td class="px-4 py-3 font-medium text-heading whitespace-nowrap border-r border-border-subtle">
                            {{ $day->format('l') }}
                        </td>
                        <td class="px-4 py-3 text-muted whitespace-nowrap border-r border-border-subtle">
                            {{ $day->format('d-m-Y') }}
                        </td>
                        <td class="px-4 py-3 text-faint border-r border-border-subtle">-</td>
                        <td class="px-4 py-3 text-faint">-</td>
                        <td></td>
                    </tr>
                @endforelse
            @endforeach
        </tbody>
    </table>
</div>
        @endif
    </div>
</div>

{{-- ok --}}
