<?php

namespace App\Models;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Client;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Task extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'created_by',
        'department_id',
        'client_id',
        'content_type_id',
        'is_extra_delivery',
        'project_id',
        'priority',
        'status',
        'task_type',
        'parent_interrupted_task_id',
        'due_date',
        'started_at',
        'completed_at',
        'estimated_hours',
        'is_archived',
        'deleted_by',
        'deletion_reason',
        'priority_auto_escalated',
    ];

    protected $casts = [
        'priority' => TaskPriority::class,
        'status' => TaskStatus::class,
        'due_date' => 'datetime',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'is_archived' => 'boolean',
        'is_extra_delivery' => 'boolean',
        'priority_auto_escalated' => 'boolean',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TaskAssignment::class);
    }

    public function platforms(): HasMany
    {
        return $this->hasMany(TaskPlatform::class);
    }

    public function deletedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'deleted_by');
    }

    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    public function currentAssignment(): HasOne
    {
        return $this->hasOne(TaskAssignment::class)->where('is_current', true);
    }

    public function activeAssignment(): HasOne
    {
        return $this->hasOne(TaskAssignment::class)->where('is_current', true);
    }

    /** Convenience: the current assignee, or null if unassigned. */
    public function currentAssignee(): ?User
    {
        return $this->currentAssignment?->assignee;
    }

    public function statusHistory(): HasMany
    {
        return $this->hasMany(TaskStatusHistory::class)->orderByDesc('changed_at');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(TaskComment::class)->orderByDesc('created_at');
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(TaskAttachment::class);
    }

    public function delayRequests(): HasMany
    {
        return $this->hasMany(DelayRequest::class)->orderByDesc('created_at');
    }

    public function pendingDelayRequest(): ?DelayRequest
    {
        return $this->delayRequests()->where('status', 'pending')->first();
    }

    /** Add-on records where THIS task was the one interrupted. */
    public function interruptions(): HasMany
    {
        return $this->hasMany(AddOnTask::class, 'interrupted_task_id');
    }

    public function activeInterruption(): ?AddOnTask
    {
        return $this->interruptions()->whereNull('resumed_at')->latest('interrupted_at')->first();
    }

    public function parentInterruptedTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'parent_interrupted_task_id');
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole(['Super Admin', 'Manager'])) {
            return $query;
        }

        if ($user->hasRole('Team Lead')) {
            return $query->where('department_id', $user->department_id);
        }

        // Employee: only tasks currently assigned to them
        return $query->whereHas('currentAssignment', fn($q) => $q->where('assigned_to', $user->id));
    }

    public function isOverdue(): bool
    {
        return $this->due_date->isPast()
            && ! in_array($this->status, [TaskStatus::Completed, TaskStatus::Cancelled, TaskStatus::PendingReview], true);
    }

    /** Employees see a submitted task as "Completed" from their perspective — reviewers still see the real "Pending Approval" state. */
    public function displayStatusLabel($viewer): string
    {
        if ($this->status === \App\Enums\TaskStatus::PendingReview && $viewer->hasRole('Employee')) {
            return 'Completed';
        }

        return $this->status->label();
    }

    public function displayStatusBadgeClasses($viewer): string
    {
        if ($this->status === \App\Enums\TaskStatus::PendingReview && $viewer->hasRole('Employee')) {
            return \App\Enums\TaskStatus::Completed->badgeClasses();
        }

        return $this->status->badgeClasses();
    }
}
