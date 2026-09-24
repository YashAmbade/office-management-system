<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ClientPlanItem extends Model
{
    protected $fillable = ['client_id', 'platform', 'content_type_id', 'quantity', 'notes', 'setup_done'];

    protected $casts = ['setup_done' => 'boolean'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class);
    }

    public function contentType(): BelongsTo
    {
        return $this->belongsTo(ContentType::class);
    }

    public function label(): string
    {
        return "{$this->platform} — {$this->contentType->name}: {$this->quantity}";
    }

    /** How many tasks already exist for this exact plan item, created in the current calendar month. */
    public function generatedThisMonthCount(): int
    {
        return $this->client->tasks()
            ->whereHas('platforms', fn ($q) => $q->where('platform', $this->platform))
            ->where('content_type_id', $this->content_type_id)
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->where('status', '!=', 'cancelled')
            ->count();
    }

    public function remainingThisMonth(): int
    {
        return max(0, $this->quantity - $this->generatedThisMonthCount());
    }
}
