<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DelayRequest extends Model
{
    protected $fillable = [
        'task_id', 'requested_by', 'reason', 'requested_new_due_date',
        'original_due_date', 'status', 'reviewed_by', 'reviewed_at', 'review_comment',
    ];

    protected $casts = [
        'requested_new_due_date' => 'datetime',
        'original_due_date' => 'datetime',
        'reviewed_at' => 'datetime',
    ];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function requester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
