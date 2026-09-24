<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;

class GmbPeriod extends Model
{
    protected $fillable = ['department_id', 'month', 'year', 'status', 'completed_by', 'completed_at'];
    protected $casts = ['completed_at' => 'datetime'];

    public function entries(): HasMany
    {
        return $this->hasMany(GmbChecklistEntry::class);
    }

    public function label(): string
    {
        return Carbon::create($this->year, $this->month, 1)->format('F Y');
    }
}
