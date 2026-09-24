@push('styles')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.snow.min.css" rel="stylesheet">
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quill/1.3.7/quill.min.js"></script>
@endpush
<div class="p-3 md:p-5 space-y-4 pb-20! lg:pb-5!">
    <div class="bg-surface border border-border rounded-2xl p-4 md:p-5 flex items-center justify-between gap-3 flex-wrap">
        <div>
            <h2 class="text-base font-semibold text-heading">Work Log - Employee Entries</h2>
            <p class="text-sm text-muted mt-0.5">
                @if($this->userId)
                    Every logged entry for the selected employee, day by day.
                @else
                    Select an employee to view their logged entries.
                @endif
            </p>
        </div>

        <div class="flex items-center gap-2 flex-wrap">
            {{-- Employee selector --}}
            <select
                wire:model.live="userId"
                class="h-9 rounded-lg border border-border bg-surface px-3 text-sm text-heading min-w-[180px]"
            >
                <option value="">Select employee...</option>
                @foreach($this->employees as $employee)
                    <option value="{{ $employee->id }}">{{ $employee->name }}</option>
                @endforeach
            </select>

            <button wire:click="previousMonth" class="w-9 h-9 rounded-lg border border-border flex items-center justify-center text-muted hover:text-heading" aria-label="Previous month">
                <i class="ph ph-caret-left"></i>
            </button>
            <span class="text-sm font-medium text-heading min-w-[120px] text-center">{{ \Carbon\Carbon::createFromFormat('Y-m', $month)->format('F Y') }}</span>
            <button wire:click="nextMonth" class="w-9 h-9 rounded-lg border border-border flex items-center justify-center text-muted hover:text-heading" aria-label="Next month">
                <i class="ph ph-caret-right"></i>
            </button>
        </div>
    </div>

    @if(! $this->userId)
        <div class="bg-surface border border-border rounded-2xl p-10 text-center text-muted" style="padding: 20px;">
            <i class="ph ph-user-circle text-3xl mb-2 block"></i>
            Pick an employee above to see their work log.
        </div>
    @else
       <div class="bg-surface border border-border rounded-2xl overflow-x-auto">
    <table class="w-full text-sm border-collapse min-w-[760px]">
        <thead>
            <tr class="bg-subtle/50 text-left text-xs text-muted">
                <th class="px-4 py-3 font-medium border-r border-border-subtle whitespace-nowrap">Day</th>
                <th class="px-4 py-3 font-medium border-r border-border-subtle whitespace-nowrap">Date</th>
                <th class="px-4 py-3 font-medium border-r border-border-subtle whitespace-nowrap">Client</th>
                <th class="px-4 py-3 font-medium">Work Done</th>
            </tr>
        </thead>
        <tbody>
            @foreach($this->calendarDays as $day)
                @php
                    $dayKey = $day->format('Y-m-d');
                    $entries = $this->entriesByDate->get($dayKey, collect());
                    $isWeekend = $day->isWeekend();
                    $rowSpan = max($entries->count(), 1);
                @endphp

                @forelse($entries as $entry)
                    <tr wire:key="entry-{{ $entry->id }}"
                        class="border-t border-border-subtle {{ $isWeekend ? 'bg-subtle/30' : '' }}">
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
                    </tr>
                @endforelse
            @endforeach
        </tbody>
    </table>
</div>
    @endif
</div>
