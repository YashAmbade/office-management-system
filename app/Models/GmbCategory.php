<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GmbCategory extends Model
{
    protected $fillable = ['name', 'sort_order', 'color'];

    public function subcategories(): HasMany
    {
        return $this->hasMany(GmbSubcategory::class)->orderBy('sort_order');
    }
}
