<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model
{
    protected $fillable = ['name'];

    public function clients(): BelongsToMany
    {
        return $this->belongsToMany(Client::class)->withTimestamps();
    }
}
