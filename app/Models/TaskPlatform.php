<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TaskPlatform extends Model
{
    public $timestamps = false;

    protected $fillable = ['task_id', 'platform'];

    protected $casts = ['created_at' => 'datetime'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }
}
