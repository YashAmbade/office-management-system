<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\GmbCategory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GmbSubcategory extends Model
{
    protected $fillable = ['gmb_category_id', 'name', 'sort_order'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(GmbCategory::class, 'gmb_category_id');
    }
}
