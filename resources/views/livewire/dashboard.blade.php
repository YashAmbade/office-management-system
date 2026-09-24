@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700&family=Sora:wght@600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/regular/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/fill/style.css">
    <link rel="stylesheet" href="https://unpkg.com/@phosphor-icons/web@2.1.1/src/bold/style.css">
    <style>
        .dashb {
            font-family: 'Manrope', ui-sans-serif, system-ui, -apple-system, sans-serif;
            -webkit-font-smoothing: antialiased;
        }

        .dashb h1,
        .dashb .num {
            font-family: 'Sora', sans-serif;
            letter-spacing: -0.02em;
        }

        .dashb .num {
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.03em;
        }

        /* Base card: soft off-white, stronger border for definition */
        .card {
            background: #FFFFFF;
            border: 1px solid #E7E4F2;
            border-radius: 18px;
            box-shadow: 0 1px 2px rgba(24, 20, 50, 0.04), 0 10px 30px -22px rgba(76, 66, 163, 0.35);
        }

        /* Tinted panel header band */
        .panel-head {
            background: linear-gradient(180deg, #F7F5FD, #FFFFFF);
            border-bottom: 1px solid #EEEBF7;
        }

        /* Tinted stat cards */
        .stat {
            border-radius: 18px;
            border: 1px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-violet {
            background: linear-gradient(155deg, #F3F0FE, #FBFAFF);
            border-color: #E3DDFB;
        }

        .stat-rose {
            background: linear-gradient(155deg, #FDEFF2, #FFFBFC);
            border-color: #F8D9E1;
        }

        .stat-amber {
            background: linear-gradient(155deg, #FDF4E1, #FFFDF8);
            border-color: #F5E4BE;
        }

        .stat-blue {
            background: linear-gradient(155deg, #EDF2FE, #FBFCFF);
            border-color: #D9E2FB;
        }

        .stat-green {
            background: linear-gradient(155deg, #E9F7EF, #FAFEFB);
            border-color: #CBEBD8;
        }

        .stat .glow {
            position: absolute;
            right: -24px;
            top: -24px;
            width: 96px;
            height: 96px;
            border-radius: 9999px;
            opacity: .55;
        }

        .card-hover {
            transition: transform .16s ease, box-shadow .16s ease;
        }

        .card-hover:hover {
            transform: translateY(-3px);
            box-shadow: 0 16px 34px -18px rgba(76, 66, 163, 0.4);
        }

        .rowh {
            transition: background-color .14s ease;
        }

        .rowh:hover {
            background: #F7F5FD;
        }

        .chip {
            background: #FBFAFE;
            border: 1px solid #EEEBF7;
            transition: background-color .14s ease, border-color .14s ease;
        }

        .chip:hover {
            background: #F3F0FE;
            border-color: #DDD5F7;
        }
    </style>
@endpush

<div class="dashb min-h-full p-3 md:p-6 space-y-6 pb-24! lg:pb-6!">

    {{-- ── Header ───────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-[26px] md:text-[30px] font-extrabold leading-none" style="color:#141127;">
                {{ now()->hour < 12 ? 'Good morning' : (now()->hour < 17 ? 'Good afternoon' : 'Good evening') }},
                {{ explode(' ', auth()->user()->name)[0] }}
            </h1>
            <p class="text-sm mt-2" style="color:#7C7896;">{{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Here's what's
                happening today.</p>
        </div>
        @can('create', \App\Models\Task::class)
            <a href="{{ route('tasks.index') }}"
                class="h-10 px-4 rounded-xl text-white text-sm font-semibold inline-flex items-center gap-2 transition-transform hover:-translate-y-0.5"
                style="background:linear-gradient(135deg,#8477F6,#5647D9); box-shadow:0 10px 24px -8px rgba(86,71,217,0.55);">
                <i class="ph-bold ph-list text-sm"></i> All Tasks
            </a>
        @endcan
    </div>

    {{-- ── Stat cards - tinted ───────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-5">
        @if ($this->isReviewer && $this->expiringClientsCount > 0)
            <a href="{{ route('clients.index') }}" class="stat stat-violet card-hover p-5 block">
                <span class="glow"
                    style="background:radial-gradient(circle,rgba(132,119,246,0.35),transparent 70%);"></span>
                <div class="relative flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                        style="background:#EAE4FD; color:#d9474e;"><i
                            class="ph-fill ph-bell-ringing text-xl"></i></span>
                </div>
                <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                    {{ $this->expiringClientsCount }}</p>
                <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Engagements Ending Soon</p>
            </a>
        @endif

        {{-- My Active Tasks --}}
        <a href="{{ route('tasks.index') }}?filter=mine" class="stat stat-violet card-hover p-5 block">
            <span class="glow"
                style="background:radial-gradient(circle,rgba(132,119,246,0.35),transparent 70%);"></span>
            <div class="relative flex items-center justify-between">
                <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                    style="background:#EAE4FD; color:#5647D9;"><i class="ph-fill ph-list-checks text-xl"></i></span>
            </div>
            <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">{{ $this->myTasksCount }}
            </p>
            <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">My Active Tasks</p>
        </a>

        {{-- Overdue --}}
        <a href="{{ route('tasks.index') }}" class="stat stat-rose card-hover p-5 block">
            <span class="glow"
                style="background:radial-gradient(circle,rgba(240,80,110,0.3),transparent 70%);"></span>
            <div class="relative flex items-center justify-between">
                <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                    style="background:#FCDEE5; color:#F0506E;"><i class="ph-fill ph-warning-circle text-xl"></i></span>
            </div>
            <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">{{ $this->overdueCount }}
            </p>
            <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Overdue</p>
        </a>

        {{-- Pending approval OR Completed --}}
        @if ($this->isReviewer)
            <a href="{{ route('tasks.index') }}" class="stat stat-amber card-hover p-5 block">
                <span class="glow"
                    style="background:radial-gradient(circle,rgba(245,166,35,0.3),transparent 70%);"></span>
                <div class="relative flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                        style="background:#FBEBC6; color:#C9861F;"><i class="ph-fill ph-hourglass text-xl"></i></span>
                </div>
                <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                    {{ $this->pendingApprovalCount }}</p>
                <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Pending Approval</p>
            </a>
        @else
            <div class="stat stat-green p-5">
                <span class="glow"
                    style="background:radial-gradient(circle,rgba(31,169,122,0.28),transparent 70%);"></span>
                <div class="relative flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                        style="background:#D5F0E0; color:#1FA97A;"><i
                            class="ph-fill ph-check-circle text-xl"></i></span>
                </div>
                <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                    {{ $this->completedThisWeekCount }}</p>
                <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Completed This Week</p>
            </div>
        @endif

        {{-- Active clients OR Completed --}}
        @can('viewAny', \App\Models\Client::class)
            <a href="{{ route('clients.index') }}" class="stat stat-blue card-hover p-5 block">
                <span class="glow"
                    style="background:radial-gradient(circle,rgba(75,110,245,0.28),transparent 70%);"></span>
                <div class="relative flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                        style="background:#DEE7FD; color:#4B6EF5;"><i class="ph-fill ph-briefcase text-xl"></i></span>
                </div>
                <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                    {{ $this->activeClientsCount }}</p>
                <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Active Clients</p>
            </a>
        @else
            <div class="stat stat-green p-5">
                <span class="glow"
                    style="background:radial-gradient(circle,rgba(31,169,122,0.28),transparent 70%);"></span>
                <div class="relative flex items-center justify-between">
                    <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                        style="background:#D5F0E0; color:#1FA97A;"><i class="ph-fill ph-check-circle text-xl"></i></span>
                </div>
                <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                    {{ $this->completedThisWeekCount }}</p>
                <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Completed This Week</p>
            </div>
        @endcan
    </div>

    {{-- ── Quick actions ─────────────────────────────────────── --}}
    <div class="card overflow-hidden mt-5">
        <div class="panel-head flex items-center justify-between px-5 py-4">
            <h3 class="text-[14px] font-bold flex items-center gap-2" style="color:#141127;">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:#EAE4FD; color:#6D5DF6;"><i class="ph-fill ph-lightning text-sm"></i></span>
                Quick Actions
            </h3>
        </div>
        <div class="p-5 grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-2.5">

            <a href="{{ route('tasks.index') }}" class="chip flex items-center gap-2.5 p-3 rounded-xl">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                    style="background:#EAE4FD; color:#6D5DF6;"><i class="ph ph-kanban"></i></span>
                <span class="text-[13px] font-semibold truncate" style="color:#332F52;">Task Board</span>
            </a>

            @can('viewAny', \App\Models\Task::class)
                @if (auth()->user()->hasRole(['Super Admin', 'Manager', 'Team Lead']))
                    <a href="{{ route('tasks.all-log') }}" class="chip flex items-center gap-2.5 p-3 rounded-xl">
                        <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                            style="background:#FCDEE5; color:#F0506E;"><i
                                class="ph ph-clock-counter-clockwise"></i></span>
                        <span class="text-[13px] font-semibold truncate" style="color:#332F52;">All Tasks Log</span>
                    </a>
                @endif
            @endcan

            @can('viewAny', \App\Models\Client::class)
                <a href="{{ route('clients.index') }}" class="chip flex items-center gap-2.5 p-3 rounded-xl">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background:#DEE7FD; color:#4B6EF5;"><i class="ph ph-briefcase"></i></span>
                    <span class="text-[13px] font-semibold truncate" style="color:#332F52;">Clients</span>
                </a>
            @endcan

            @if (auth()->user()->canSeeModule('employees'))
                <a href="{{ route('users.index') }}" class="chip flex items-center gap-2.5 p-3 rounded-xl">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background:#FBEBC6; color:#C9861F;"><i class="ph ph-users-three"></i></span>
                    <span class="text-[13px] font-semibold truncate" style="color:#332F52;">Employees</span>
                </a>
            @endcan

            @if (auth()->user()->canSeeModule('departments'))
                <a href="{{ route('departments.index') }}" class="chip flex items-center gap-2.5 p-3 rounded-xl">
                    <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                        style="background:#D5F0E0; color:#1FA97A;"><i class="ph ph-buildings"></i></span>
                    <span class="text-[13px] font-semibold truncate" style="color:#332F52;">Departments</span>
                </a>
            @endrole

            <a href="{{ route('settings.index') }}" class="chip flex items-center gap-2.5 p-3 rounded-xl">
                <span class="w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0"
                    style="background:#ECEAF7; color:#8B87A6;"><i class="ph ph-gear-six"></i></span>
                <span class="text-[13px] font-semibold truncate" style="color:#332F52;">Settings</span>
            </a>
</div>
</div>

{{-- ── Two columns ──────────────────────────────────────── --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mt-4">

{{-- Upcoming tasks --}}
<div class="card overflow-hidden">
    <div class="panel-head flex items-center justify-between px-5 py-4">
        <h3 class="text-[14px] font-bold flex items-center gap-2" style="color:#141127;">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center"
                style="background:#EAE4FD; color:#6D5DF6;"><i
                    class="ph-fill ph-calendar-blank text-sm"></i></span>
            Upcoming Tasks
        </h3>
        <a href="{{ route('tasks.index') }}?filter=mine" class="text-xs font-semibold hover:underline"
            style="color:#5647D9;">View all</a>
    </div>
    <div class="px-2 py-1.5">
        @forelse($this->myUpcomingTasks as $task)
            <a href="{{ route('tasks.show', $task) }}"
                class="rowh flex items-center justify-between gap-3 px-3 py-3 rounded-xl">
                <div class="flex items-center gap-3 min-w-0">
                    <span class="w-1.5 h-8 rounded-full flex-shrink-0"
                        style="background: {{ $task->isOverdue() ? '#F0506E' : '#6D5DF6' }};"></span>
                    <div class="min-w-0">
                        <p class="text-sm font-semibold truncate" style="color:#1E1B3A;">{{ $task->title }}
                        </p>
                        <p class="text-[11px] mt-0.5" style="color:#9591AD;">
                            {{ $task->client?->name ?? 'No client' }}</p>
                    </div>
                </div>
                <span class="num text-xs flex-shrink-0 font-semibold px-2.5 py-1 rounded-lg"
                    style="{{ $task->isOverdue() ? 'background:#FCDEE5;color:#F0506E;' : 'background:#F1EDFC;color:#6D5DF6;' }}">
                    {{ $task->due_date->format('M j') }}
                </span>
            </a>
        @empty
            <p class="text-sm text-center py-10" style="color:#9591AD;">Nothing upcoming - you're all caught
                up.</p>
        @endforelse
    </div>
</div>

@if ($this->isReviewer)
    {{-- Pending approval --}}
    <div class="card overflow-hidden">
        <div class="panel-head px-5 py-4">
            <h3 class="text-[14px] font-bold flex items-center gap-2" style="color:#141127;">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:#FBEBC6; color:#C9861F;"><i
                        class="ph-fill ph-hourglass text-sm"></i></span>
                Waiting on Your Approval
            </h3>
        </div>
        <div class="px-2 py-1.5">
            @forelse($this->pendingApprovalTasks as $task)
                <a href="{{ route('tasks.show', $task) }}"
                    class="rowh flex items-center justify-between gap-3 px-3 py-3 rounded-xl">
                    <div class="min-w-0">
                        <p class="text-sm font-semibold truncate" style="color:#1E1B3A;">{{ $task->title }}
                        </p>
                        <p class="text-[11px] mt-0.5" style="color:#9591AD;">
                            {{ $task->currentAssignee()?->name ?? '-' }}</p>
                    </div>
                    <span
                        class="inline-flex items-center px-2.5 py-1 rounded-md text-[10px] font-bold flex-shrink-0"
                        style="background:#FBEBC6; color:#C9861F;">Review</span>
                </a>
            @empty
                <p class="text-sm text-center py-10" style="color:#9591AD;">Nothing waiting for approval right
                    now.</p>
            @endforelse
        </div>
    </div>
@else
    {{-- Recent activity --}}
    <div class="card overflow-hidden">
        <div class="panel-head px-5 py-4">
            <h3 class="text-[14px] font-bold flex items-center gap-2" style="color:#141127;">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:#DEE7FD; color:#4B6EF5;"><i
                        class="ph-fill ph-clock-clockwise text-sm"></i></span>
                Recent Activity
            </h3>
        </div>
        <div class="px-5 py-2">
            @forelse($this->recentActivity as $entry)
                <div class="flex items-start gap-3 py-2.5 border-b last:border-0"
                    style="border-color:#F1EFF8;">
                    <span class="w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0"
                        style="background:#6D5DF6;"></span>
                    <p class="text-[13px] leading-relaxed" style="color:#4A4668;">
                        <span class="font-semibold"
                            style="color:#1E1B3A;">{{ $entry->changedBy?->name ?? 'System' }}</span>
                        moved <a href="{{ route('tasks.show', $entry->task) }}"
                            class="font-semibold hover:underline"
                            style="color:#5647D9;">{{ $entry->task->title }}</a>
                        to <span
                            class="font-medium">{{ ucfirst(str_replace('_', ' ', $entry->new_status)) }}</span>
                        <span style="color:#B6B3CC;"> · {{ $entry->changed_at->diffForHumans() }}</span>
                    </p>
                </div>
            @empty
                <p class="text-sm text-center py-10" style="color:#9591AD;">No recent activity.</p>
            @endforelse
        </div>
    </div>
@endif
</div>

{{-- ── Full-width activity for reviewers ────────────────── --}}
@if ($this->isReviewer)
<div class="card overflow-hidden mt-4">
    <div class="panel-head px-5 py-4">
        <h3 class="text-[14px] font-bold flex items-center gap-2" style="color:#141127;">
            <span class="w-7 h-7 rounded-lg flex items-center justify-center"
                style="background:#DEE7FD; color:#4B6EF5;"><i
                    class="ph-fill ph-clock-clockwise text-sm"></i></span>
            Recent Activity
        </h3>
    </div>
    <div class="px-5 py-2 grid grid-cols-1 sm:grid-cols-2 gap-x-8">
        @foreach ($this->recentActivity as $entry)
            <div class="flex items-start gap-3 py-2.5 border-b last:border-0" style="border-color:#F1EFF8;">
                <span class="w-1.5 h-1.5 rounded-full mt-1.5 flex-shrink-0"
                    style="background:#6D5DF6;"></span>
                <p class="text-[13px] leading-relaxed" style="color:#4A4668;">
                    <span class="font-semibold"
                        style="color:#1E1B3A;">{{ $entry->changedBy?->name ?? 'System' }}</span>
                    moved <a href="{{ route('tasks.show', $entry->task) }}"
                        class="font-semibold hover:underline"
                        style="color:#5647D9;">{{ $entry->task->title }}</a>
                    to <span
                        class="font-medium">{{ ucfirst(str_replace('_', ' ', $entry->new_status)) }}</span>
                    <span style="color:#B6B3CC;"> · {{ $entry->changed_at->diffForHumans() }}</span>
                </p>
            </div>
        @endforeach
    </div>
</div>
@endif
</div>
/ /   t e s t   l i n e  
 