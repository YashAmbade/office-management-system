<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Department extends Model
{
    use SoftDeletes;
    protected $fillable = ['name', 'code', 'parent_department_id', 'is_active'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'parent_department_id');
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
