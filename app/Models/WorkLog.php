<?php
// app/Models/WorkLog.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkLog extends Model
{
    protected $fillable = ['user_id', 'work_log_client_id', 'description', 'logged_at'];

    protected $casts = ['logged_at' => 'datetime'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(WorkLogClient::class, 'work_log_client_id');
    }
}
