<?php

namespace App\Livewire;

use App\Models\WorkLog as WorkLogModel;
use App\Models\WorkLogClient;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Computed;
use Livewire\Component;

class WorkLog extends Component
{
    public ?int $clientId = null;
    public string $description = '';
    public string $loggedAtDate = '';
    public ?int $editingId = null;

    public string $rangeStart = '';
    public string $rangeEnd = '';

    // Inline "add new client" support
    public string $newClientName = '';

    #[Computed]
    public function myClients()
    {
        return WorkLogClient::where('is_active', true)
            ->where('department_id', Auth::user()->department_id)
            ->orderBy('name')
            ->get();
    }

    #[Computed]
    public function myEntriesByDate()
    {
        $start = Carbon::parse($this->rangeStart)->startOfDay();
        $end = Carbon::parse($this->rangeEnd)->endOfDay();

        return WorkLogModel::where('user_id', Auth::id())
            ->with('client')
            ->whereBetween('logged_at', [$start, $end])
            ->orderBy('logged_at')
            ->get()
            ->groupBy(fn ($log) => $log->logged_at->format('Y-m-d'));
    }

    #[Computed]
    public function myRangeDays(): array
    {
        $start = Carbon::parse($this->rangeStart)->startOfDay();
        $end = Carbon::parse($this->rangeEnd)->startOfDay();

        if ($start->gt($end)) {
            return [];
        }

        $days = [];
        for ($date = $end->copy(); $date->gte($start); $date->subDay()) {
            $days[] = $date->copy();
        }

        return $days;
    }

    public function mount(): void
    {
        if (! auth()->user()->canAccessWorkLog()) {
            abort(403);
        }

        $this->loggedAtDate = now()->format('Y-m-d');
        $this->rangeEnd = now()->format('Y-m-d');
        $this->rangeStart = now()->subDays(6)->format('Y-m-d');
    }

    public function edit(int $logId): void
    {
        $log = WorkLogModel::where('user_id', Auth::id())->findOrFail($logId);
        $this->editingId = $log->id;
        $this->clientId = $log->work_log_client_id;
        $this->description = $log->description;
        $this->loggedAtDate = $log->logged_at->format('Y-m-d');
    }

    public function cancelEdit(): void
    {
        $this->reset(['editingId', 'clientId', 'description']);
        $this->loggedAtDate = now()->format('Y-m-d');
    }

    /**
     * Called from the client combobox when the typed name doesn't match
     * an existing client. Creates it inline, scoped to the employee's
     * own department, and selects it immediately.
     */
    public function quickAddClient(): void
    {
        $name = trim($this->newClientName);

        $this->resetErrorBag('newClientName');

        if ($name === '') {
            return;
        }

        $departmentId = Auth::user()->department_id;

        $existing = WorkLogClient::withTrashed()
            ->where('department_id', $departmentId)
            ->whereRaw('LOWER(name) = ?', [strtolower($name)])
            ->first();

        if ($existing) {
            $message = $existing->trashed()
                ? "\"{$name}\" already exists but was removed — ask your manager to restore it."
                : "A client named \"{$name}\" already exists — please select it from the list instead.";

            $this->addError('newClientName', $message);
            return;
        }

        $client = WorkLogClient::create([
            'name' => $name,
            'department_id' => $departmentId,
            'is_active' => true,
            'created_by' => Auth::id(),
        ]);

        $this->clientId = $client->id;
        $this->newClientName = '';
        unset($this->myClients);
        $this->dispatch('work-log-client-saved', id: $client->id, name: $client->name);
    }

    public function submit(): void
{
    $this->validate([
        'clientId' => 'required|exists:work_log_clients,id',
        'description' => 'required|string|max:5000',
        'loggedAtDate' => 'required|date|before_or_equal:today',
    ]);

    $cleanDescription = $this->sanitizeDescriptionHtml($this->description);

    if ($this->editingId) {
        $log = WorkLogModel::where('user_id', Auth::id())->findOrFail($this->editingId);
        $log->update([
            'work_log_client_id' => $this->clientId,
            'description' => $cleanDescription,
            'logged_at' => $this->loggedAtDate,
        ]);
        $this->dispatch('work-log-updated');
    } else {
        WorkLogModel::create([
            'user_id' => Auth::id(),
            'work_log_client_id' => $this->clientId,
            'description' => $cleanDescription,
            'logged_at' => $this->loggedAtDate,
        ]);
        $this->dispatch('work-log-saved');
    }

    $this->reset(['editingId', 'clientId', 'description']);
    $this->loggedAtDate = now()->format('Y-m-d');
    unset($this->myEntriesByDate);
}

/**
 * Whitelist-based HTML sanitizer for rich-text descriptions.
 * Strips everything except basic formatting tags, and only allows
 * color/background-color declarations inside style attributes.
 */
private function sanitizeDescriptionHtml(string $html): string
{
    $allowedTags = '<p><br><strong><b><em><i><u><s><span><ul><ol><li>';
    $clean = strip_tags($html, $allowedTags);

    // Strip any inline event handlers or javascript: URIs, just in case.
    $clean = preg_replace('/\son\w+\s*=\s*"[^"]*"/i', '', $clean);
    $clean = preg_replace('/\son\w+\s*=\s*\'[^\']*\'/i', '', $clean);
    $clean = preg_replace('/javascript:/i', '', $clean);

    // Only keep color/background-color inside style="..." — drop everything else.
    $clean = preg_replace_callback('/style\s*=\s*"([^"]*)"/i', function ($m) {
        preg_match_all('/(color|background-color)\s*:\s*[^;"]+;?/i', $m[1], $matches);
        $safe = trim(implode(' ', $matches[0]));
        return $safe ? 'style="' . e($safe) . '"' : '';
    }, $clean);

    return $clean;
}

    public function setLast7Days(): void
    {
        $this->rangeEnd = now()->format('Y-m-d');
        $this->rangeStart = now()->subDays(6)->format('Y-m-d');
    }

    public function render()
    {
        return view('livewire.work-log');
    }
}