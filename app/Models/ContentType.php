<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ContentType extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'is_system', 'created_by'];

    protected $casts = ['is_system' => 'boolean'];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
