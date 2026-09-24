<?php

namespace App\Livewire;

use App\Enums\TaskStatus;
use App\Models\Client;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssignedNotification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Livewire\Attributes\Computed;
use Livewire\Component;

class TaskGenerator extends Component
{
    public Client $client;
    public bool $showModal = false;

    public array $selectedPlanItemIds = [];
    public ?int $assigneeId = null;
    public string $dueDate = '';

    public function mount(Client $client): void
    {
        $this->client = $client;
    }

    public function open(): void
    {
        Gate::authorize('generateTasks', Client::class);

        $this->selectedPlanItemIds = $this->itemsWithRemaining->pluck('id')->toArray();
        $this->assigneeId = null;
        $this->dueDate = now()->endOfMonth()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    #[Computed]
    public function itemsWithRemaining()
    {
        return $this->client->planItems->load('contentType')->filter(fn ($item) => $item->remainingThisMonth() > 0)->values();
    }

    #[Computed]
    public function assignableUsers()
    {
        $user = Auth::user();

        $query = User::where('is_active', true)->whereHas('roles', fn ($q) => $q->where('name', 'Employee'));

        if (! $user->hasRole(['Super Admin', 'Manager', 'Team Lead'])) {
            $query->where('department_id', $user->department_id);
        }

        return $query->orderBy('name')->get();
    }

    public function generate(): void
    {
        Gate::authorize('generateTasks', Client::class);

        $this->validate([
            'assigneeId' => 'required|exists:users,id',
            'dueDate' => 'required|date',
            'selectedPlanItemIds' => 'required|array|min:1',
        ]);

        $actor = Auth::user();
        $assignee = User::findOrFail($this->assigneeId);
        $created = 0;

        DB::transaction(function () use ($actor, $assignee, &$created) {
            foreach ($this->client->planItems as $item) {
                if (! in_array($item->id, $this->selectedPlanItemIds, true)) {
                    continue;
                }

                $remaining = $item->remainingThisMonth();

                for ($i = 1; $i <= $remaining; $i++) {
                    $title = "{$this->client->name} — {$item->platform} {$item->contentType->name} #{$i}";

                    $task = Task::create([
                        'title' => $title,
                        'description' => $item->notes ? "Plan note: {$item->notes}" : null,
                        'created_by' => $actor->id,
                        'department_id' => $actor->department_id,
                        'priority' => 'medium',
                        'status' => TaskStatus::Pending->value,
                        'task_type' => 'normal',
                        'due_date' => $this->dueDate,
                        'client_id' => $this->client->id,
                        'content_type_id' => $item->content_type_id,
                        'is_extra_delivery' => false,
                    ]);

                    $task->platforms()->create(['platform' => $item->platform]);

                    $task->assignments()->create([
                        'assigned_to' => $assignee->id,
                        'assigned_by' => $actor->id,
                        'assigned_at' => now(),
                        'is_current' => true,
                    ]);

                    $task->statusHistory()->create([
                        'old_status' => null,
                        'new_status' => TaskStatus::Pending->value,
                        'changed_by' => $actor->id,
                        'reason' => 'Auto-generated from delivery plan.',
                        'changed_at' => now(),
                    ]);

                    $created++;
                }
            }

            $assignee->notify(new TaskAssignedNotification(
                Task::where('client_id', $this->client->id)->latest()->first(),
                $actor->name
            ));
        });

        $this->showModal = false;
        unset($this->itemsWithRemaining);
        session()->flash('generatedCount', $created);
        $this->dispatch('tasks-generated');
    }

    public function render()
    {
        return view('livewire.task-generator');
    }
}
