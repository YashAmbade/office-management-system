<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskAssignment extends Model
{
    protected $fillable = ['task_id', 'assigned_to', 'assigned_by', 'assigned_at', 'is_current', 'unassigned_at'];

    protected $casts = [
        'assigned_at' => 'datetime',
        'unassigned_at' => 'datetime',
        'is_current' => 'boolean',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function assigner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_by');
    }
}
