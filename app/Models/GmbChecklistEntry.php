<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GmbChecklistEntry extends Model
{
    protected $fillable = ['gmb_period_id', 'gmb_client_id', 'gmb_subcategory_id', 'is_checked', 'updated_by', 'checked_at'];
    protected $casts = ['is_checked' => 'boolean', 'checked_at' => 'datetime'];
}
