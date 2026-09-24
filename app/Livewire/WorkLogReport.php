<?php

namespace App\Livewire;

use App\Models\User;
use App\Models\WorkLog;
use Illuminate\Support\Carbon;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WorkLogReport extends Component
{
    public string $month; // Y-m

    public ?int $userId = null;

    public function mount(): void
    {
        if (! auth()->user()->hasRole(['Super Admin', 'Manager'])) {
            abort(403);
        }

        $this->month = now()->format('Y-m');
    }

    /** Employees the manager/admin can pick from. */
    #[Computed]
    public function employees()
    {
        return User::query()
            ->whereHas('workLogs')
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function entriesByDate()
    {
        if (! $this->userId) {
            return collect();
        }

        $start = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
        $end = $start->copy()->endOfMonth();

        return WorkLog::with(['user', 'client'])
            ->where('user_id', $this->userId)
            ->whereBetween('logged_at', [$start->startOfDay(), $end->endOfDay()])
            ->orderBy('logged_at')
            ->get()
            ->groupBy(fn ($log) => $log->logged_at->format('Y-m-d'));
    }

    /** Every calendar day of the selected month, so empty days still render a row. */
    #[Computed]
public function calendarDays(): array
{
    $start = Carbon::createFromFormat('Y-m', $this->month)->startOfMonth();
    $end = $start->copy()->endOfMonth();
    $days = [];

    for ($date = $end->copy(); $date->gte($start); $date->subDay()) {
        $days[] = $date->copy();
    }

    return $days;
}

    public function selectEmployee(int $userId): void
    {
        $this->userId = $userId;
    }

    public function previousMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->subMonth()->format('Y-m');
    }

    public function nextMonth(): void
    {
        $this->month = Carbon::createFromFormat('Y-m', $this->month)->addMonth()->format('Y-m');
    }

    public function render()
    {
        return view('livewire.work-log-report');
    }
}
