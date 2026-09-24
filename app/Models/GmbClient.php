<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class GmbClient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'department_id', 'created_by', 'is_active',
        'performance_status', 'assigned_to',
    ];

    protected $casts = ['is_active' => 'boolean'];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public static function performanceLevels(): array
{
    return [
        'gone' => ['label' => 'Client Gone', 'bg' => '#7f1d1d', 'text' => '#ffffff'],
        'low' => ['label' => 'Low Performing', 'bg' => '#fecaca', 'text' => '#7f1d1d'],
        'medium' => ['label' => 'Medium Performing', 'bg' => '#fef9c3', 'text' => '#854d0e'],
        'high' => ['label' => 'High Performing', 'bg' => '#73e099', 'text' => '#14532d'],
        'very_high' => ['label' => 'Very Good Performing', 'bg' => '#166534', 'text' => '#ffffff'],
    ];
}

public function performanceColors(): array
{
    return self::performanceLevels()[$this->performance_status] ?? ['bg' => '#e5e7eb', 'text' => '#6b7280'];
}

    public function performanceBadgeClasses(): string
    {
        return self::performanceLevels()[$this->performance_status]['classes'] ?? 'bg-subtle text-faint';
    }

    public function performanceLabel(): string
    {
        return self::performanceLevels()[$this->performance_status]['label'] ?? 'Unknown';
    }
}
