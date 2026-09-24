<?php

namespace App\Enums;

enum TaskPriority: string
{
    case Low = 'low';
    case Medium = 'medium';
    case High = 'high';
    case Critical = 'critical';

    public function label(): string
    {
        return ucfirst($this->value);
    }

    public function badgeClasses(): string
    {
        return match ($this) {
            self::Low => 'bg-subtle text-muted',
            self::Medium => 'bg-info-soft text-info',
            self::High => 'bg-danger-soft text-danger',
            self::Critical => 'bg-danger text-white',
        };
    }
}
