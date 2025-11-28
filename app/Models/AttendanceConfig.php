<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AttendanceConfig extends Model
{
    use HasFactory;

    protected $fillable = [
        'key',
        'value',
        'type',
        'description',
        'is_public',
    ];

    protected $casts = [
        'is_public' => 'boolean',
    ];

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeByKey($query, $key)
    {
        return $query->where('key', $key);
    }

    // Helper Methods
    public static function getValue(string $key, $default = null)
    {
        return Cache::remember("config.{$key}", 3600, function () use ($key, $default) {
            $config = self::where('key', $key)->first();

            if (! $config) {
                return $default;
            }

            return self::castValue($config->value, $config->type);
        });
    }

    public static function setValue(string $key, $value, string $type = 'string'): bool
    {
        $config = self::updateOrCreate(
            ['key' => $key],
            [
                'value' => is_array($value) ? json_encode($value) : $value,
                'type' => $type,
            ]
        );

        Cache::forget("config.{$key}");

        return $config->exists;
    }

    protected static function castValue($value, $type)
    {
        return match ($type) {
            'integer' => (int) $value,
            'boolean' => (bool) $value,
            'json' => json_decode($value, true),
            'array' => json_decode($value, true),
            'time' => $value,
            default => $value,
        };
    }

    public function getValueAttribute($value)
    {
        return self::castValue($value, $this->type);
    }
}
