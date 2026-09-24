<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany};

class MenuItem extends Model
{
    protected $fillable = [
        'parent_id', 'key', 'label', 'icon', 'route_name',
        'section', 'restricted_department_id', 'sort_order', 'is_active',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id')->where('is_active', true)->orderBy('sort_order');
    }

    public function restrictedDepartment(): BelongsTo
    {
        return $this->belongsTo(Department::class, 'restricted_department_id');
    }
}
