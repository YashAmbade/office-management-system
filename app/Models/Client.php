<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use App\Models\ClientPlanItem;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = ['name', 'company', 'email', 'phone', 'status', 'is_priority', 'start_date', 'end_date', 'department_id', 'created_by', 'notes'];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_priority' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function planItems(): HasMany
    {
        return $this->hasMany(ClientPlanItem::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class);
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(Service::class)->withTimestamps();
    }

    /** Completed task count for a given platform + content type combo (e.g. Facebook + Reel, or Facebook + AI Video). */
    public function completedCountFor(string $platform, int $contentTypeId): int
    {
        return $this->tasks()
            ->whereHas('platforms', fn ($q) => $q->where('platform', $platform))
            ->where('content_type_id', $contentTypeId)
            ->where('status', '!=', 'cancelled')
            ->whereIn('status', ['completed']) // only fully-approved counts toward delivered
            ->count();
    }

    /** Every content type that appears anywhere in this client's plan, for rendering columns/rows dynamically. */
    public function usedContentTypes()
    {
        return $this->planItems->pluck('contentType')->unique('id')->values();
    }

    public function scopeVisibleTo($query, User $user)
    {
        if ($user->hasRole(['Super Admin', 'Manager'])) {
            return $query;
        }

        if ($user->hasRole(['Team Lead', 'Employee'])) {
            return $query;
        }

        return $query->whereRaw('1 = 0');
    }

    public function deliveredCountFor(string $platform, int $contentTypeId): int
    {
        return $this->tasks()
            ->whereHas('platforms', fn ($q) => $q->where('platform', $platform))
            ->where('content_type_id', $contentTypeId)
            ->where('status', '!=', 'cancelled')
            ->count();
    }

    /** Would assigning one more piece of this type/platform exceed the plan? */
    public function wouldExceedQuota(string $platform, int $contentTypeId): bool
    {
        $item = $this->planItems->first(fn ($p) => $p->platform === $platform && $p->content_type_id === $contentTypeId);

        if (! $item) {
            return false;
        }

        return $this->deliveredCountFor($platform, $contentTypeId) >= $item->quantity;
    }

    /** Plan items grouped by platform, each carrying its content type + quantity — for dynamic column/row rendering. */
    public function planItemsByPlatform()
    {
        return $this->planItems->groupBy('platform');
    }
    /** True only if the client has at least one platform, and every platform is marked setup-done. */
    public function isFullySetUp(): bool
    {
        return $this->planItems->isNotEmpty() && $this->planItems->every(fn ($item) => $item->setup_done);
    }
}
