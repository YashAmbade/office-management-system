<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AddOnTask extends Model
{
    protected $fillable = [
        'interrupted_task_id', 'add_on_task_id', 'employee_id',
        'interrupted_at', 'reason', 'resumed_at', 'created_by',
    ];

    protected $casts = [
        'interrupted_at' => 'datetime',
        'resumed_at' => 'datetime',
    ];

    public function interruptedTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'interrupted_task_id');
    }

    public function addOnTask(): BelongsTo
    {
        return $this->belongsTo(Task::class, 'add_on_task_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'employee_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
