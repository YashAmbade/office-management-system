@php
    $visibleItems = \App\Models\MenuItem::whereNull('parent_id')
        ->where('is_active', true)
        ->with('children')
        ->orderBy('sort_order')
        ->get()
        ->filter(fn($item) => \App\Models\MenuPermission::isVisibleFor(auth()->user(), $item));

    $sections = $visibleItems->groupBy('section');
@endphp

@foreach ($sections as $section => $sectionItems)
    <div class="space-y-1">
        @if ($section)
            <p class="nav-section nav-text">{{ $section }}</p>
        @endif

        @foreach ($sectionItems as $item)
            @php
                $visibleChildren = $item->children->filter(
                    fn($c) => \App\Models\MenuPermission::isVisibleFor(auth()->user(), $c),
                );
            @endphp

            @if ($item->children->isEmpty())
                {{-- Leaf item: direct link --}}
                <a class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all"
                    href="{{ $item->route_name ? route($item->route_name) : '#' }}"
                    @if (request()->routeIs($item->route_name)) data-active="true" @endif>
                    <i class="ph {{ $item->icon }} text-2xl flex-shrink-0"></i>
                    <span class="nav-text font-medium whitespace-nowrap">{{ $item->label }}</span>
                </a>
            @elseif ($visibleChildren->isNotEmpty())
                {{-- Parent with visible children: render each child link directly (flat, matching your current style) --}}
                @foreach ($visibleChildren as $child)
                    <a class="nav-item flex items-center gap-3 px-3 py-2.5 rounded-xl transition-all"
                        href="{{ route($child->route_name) }}"
                        @if (request()->routeIs($child->route_name)) data-active="true" @endif>
                        <i class="ph {{ $child->icon }} text-2xl flex-shrink-0"></i>
                        <span class="nav-text font-medium whitespace-nowrap">{{ $child->label }}</span>
                    </a>
                @endforeach
            @endif
        @endforeach
    </div>
@endforeach
