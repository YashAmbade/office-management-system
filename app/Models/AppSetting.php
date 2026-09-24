<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AppSetting extends Model
{
    protected $primaryKey = 'setting_key';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['setting_key', 'setting_value'];

    public static function get(string $key, $default = null)
    {
        return static::find($key)?->setting_value ?? $default;
    }

    public static function set(string $key, $value): void
    {
        static::updateOrCreate(['setting_key' => $key], ['setting_value' => $value]);
    }
}
