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

        .dashb h1, .dashb .num {
            font-family: 'Sora', sans-serif;
            letter-spacing: -0.02em;
        }

        .dashb .num {
            font-variant-numeric: tabular-nums;
            letter-spacing: -0.03em;
        }

        .card {
            background: #FFFFFF;
            border: 1px solid #E7E4F2;
            border-radius: 18px;
            box-shadow: 0 1px 2px rgba(24, 20, 50, 0.04), 0 10px 30px -22px rgba(76, 66, 163, 0.35);
        }

        .panel-head {
            background: linear-gradient(180deg, #F7F5FD, #FFFFFF);
            border-bottom: 1px solid #EEEBF7;
        }

        .stat {
            border-radius: 18px;
            border: 1px solid;
            position: relative;
            overflow: hidden;
        }

        .stat-violet { background: linear-gradient(155deg, #F3F0FE, #FBFAFF); border-color: #E3DDFB; }
        .stat-rose   { background: linear-gradient(155deg, #FDEFF2, #FFFBFC); border-color: #F8D9E1; }
        .stat-amber  { background: linear-gradient(155deg, #FDF4E1, #FFFDF8); border-color: #F5E4BE; }
        .stat-blue   { background: linear-gradient(155deg, #EDF2FE, #FBFCFF); border-color: #D9E2FB; }
        .stat-green  { background: linear-gradient(155deg, #E9F7EF, #FAFEFB); border-color: #CBEBD8; }

        .stat .glow {
            position: absolute;
            right: -24px;
            top: -24px;
            width: 96px;
            height: 96px;
            border-radius: 9999px;
            opacity: .55;
        }

        .rowh { transition: background-color .14s ease; }
        .rowh:hover { background: #F7F5FD; }

        .bar-track {
            background: #F1EFF8;
            border-radius: 9999px;
            overflow: hidden;
            height: 6px;
        }
        .bar-fill {
            height: 100%;
            border-radius: 9999px;
            background: linear-gradient(90deg, #8477F6, #5647D9);
        }
    </style>
@endpush

<div class="dashb min-h-full p-3 md:p-6 space-y-6 pb-24! lg:pb-6!">

    {{-- ── Header ───────────────────────────────────────────── --}}
    <div class="flex flex-wrap items-end justify-between gap-4">
        <div>
            <h1 class="text-[26px] md:text-[30px] font-extrabold leading-none" style="color:#141127;">
                Team Analytics
            </h1>
            <p class="text-sm mt-2" style="color:#7C7896;">
                {{ now()->format('l, F j, Y') }} &nbsp;·&nbsp; Team Lead & Employee performance, last 30 days.
            </p>
        </div>
        @can('create', \App\Models\Task::class)
            <a href="{{ route('tasks.index') }}"
                class="h-10 px-4 rounded-xl text-white text-sm font-semibold inline-flex items-center gap-2 transition-transform hover:-translate-y-0.5"
                style="background:linear-gradient(135deg,#8477F6,#5647D9); box-shadow:0 10px 24px -8px rgba(86,71,217,0.55);">
                <i class="ph-bold ph-list text-sm"></i> All Tasks
            </a>
        @endcan
    </div>
    
    {{-- ── Per-person table ────────────────────────────────────── --}}
    <div class="card overflow-hidden mt-4">
        <div class="panel-head flex items-center justify-between px-5 py-4">
            <h3 class="text-[14px] font-bold flex items-center gap-2" style="color:#141127;">
                <span class="w-7 h-7 rounded-lg flex items-center justify-center"
                    style="background:#EAE4FD; color:#6D5DF6;"><i class="ph-fill ph-users-three text-sm"></i></span>
                Team Leads & Employees
            </h3>
            <span class="text-xs" style="color:#9591AD;">{{ $this->teamStats->count() }} people</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse min-w-[720px]">
                <thead>
                    <tr class="text-left text-xs" style="color:#9591AD;">
                        <th class="px-5 py-3 font-medium">Name</th>
                        <th class="px-4 py-3 font-medium">Role</th>
                        <th class="px-4 py-3 font-medium">Department</th>
                        <th class="px-4 py-3 font-medium text-center">Assigned (30d)</th>
                        <th class="px-4 py-3 font-medium text-center">Completed (30d)</th>
                        <th class="px-4 py-3 font-medium text-center">Active</th>
                        <th class="px-4 py-3 font-medium text-center">Overdue</th>
                        <th class="px-4 py-3 font-medium">Completion</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($this->teamStats as $row)
                        <tr class="rowh border-t" style="border-color:#F1EFF8;">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-2.5">
                                    <span class="w-8 h-8 rounded-full flex items-center justify-center text-[11px] font-semibold flex-shrink-0"
                                        style="background:#EAE4FD; color:#5647D9;">
                                        {{ strtoupper(substr($row->user->name, 0, 2)) }}
                                    </span>
                                    <span class="font-semibold" style="color:#1E1B3A;">{{ $row->user->name }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3" style="color:#7C7896;">{{ $row->role }}</td>
                            <td class="px-4 py-3" style="color:#7C7896;">{{ $row->user->department?->name ?? '-' }}</td>
                            <td class="px-4 py-3 num text-center font-semibold" style="color:#1E1B3A;">
                                {{ $row->assigned_30d }}</td>
                            <td class="px-4 py-3 num text-center font-semibold" style="color:#1FA97A;">
                                {{ $row->completed_30d }}</td>
                            <td class="px-4 py-3 num text-center font-semibold" style="color:#5647D9;">
                                {{ $row->active_now }}</td>
                            <td class="px-4 py-3 num text-center font-semibold"
                                style="color: {{ $row->overdue_now > 0 ? '#F0506E' : '#9591AD' }};">
                                {{ $row->overdue_now }}</td>
                            <td class="px-4 py-3" style="min-width:120px;">
                                @if ($row->completion_rate === null)
                                    <span class="text-xs" style="color:#B6B3CC;">No data</span>
                                @else
                                    <div class="flex items-center gap-2">
                                        <div class="bar-track flex-1">
                                            <div class="bar-fill" style="width: {{ $row->completion_rate }}%;"></div>
                                        </div>
                                        <span class="num text-xs font-semibold" style="color:#4A4668;">
                                            {{ $row->completion_rate }}%</span>
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-5 py-10 text-center text-sm" style="color:#9591AD;">
                                No Team Leads or Employees found.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── Org-wide summary cards ──────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mt-5">
        <div class="stat stat-violet p-5">
            <span class="glow" style="background:radial-gradient(circle,rgba(132,119,246,0.35),transparent 70%);"></span>
            <div class="relative">
                <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                    style="background:#EAE4FD; color:#5647D9;"><i class="ph-fill ph-arrow-square-out text-xl"></i></span>
            </div>
            <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                {{ $this->orgStats['assigned_30d'] }}</p>
            <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Tasks Assigned (30d)</p>
        </div>

        <div class="stat stat-green p-5">
            <span class="glow" style="background:radial-gradient(circle,rgba(31,169,122,0.28),transparent 70%);"></span>
            <div class="relative">
                <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                    style="background:#D5F0E0; color:#1FA97A;"><i class="ph-fill ph-check-circle text-xl"></i></span>
            </div>
            <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                {{ $this->orgStats['completed_30d'] }}</p>
            <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Completed (30d)</p>
        </div>

        <div class="stat stat-rose p-5">
            <span class="glow" style="background:radial-gradient(circle,rgba(240,80,110,0.3),transparent 70%);"></span>
            <div class="relative">
                <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                    style="background:#FCDEE5; color:#F0506E;"><i class="ph-fill ph-warning-circle text-xl"></i></span>
            </div>
            <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                {{ $this->orgStats['overdue_now'] }}</p>
            <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Overdue Right Now</p>
        </div>

        <div class="stat stat-amber p-5">
            <span class="glow" style="background:radial-gradient(circle,rgba(245,166,35,0.3),transparent 70%);"></span>
            <div class="relative">
                <span class="w-10 h-10 rounded-2xl flex items-center justify-center"
                    style="background:#FBEBC6; color:#C9861F;"><i class="ph-fill ph-hourglass text-xl"></i></span>
            </div>
            <p class="num text-[32px] font-extrabold mt-3 leading-none" style="color:#141127;">
                {{ $this->orgStats['pending_review'] }}</p>
            <p class="text-[12px] font-semibold mt-1.5" style="color:#7C7896;">Pending Review</p>
        </div>
    </div>

   

</div>