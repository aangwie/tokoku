<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];

    /**
     * In-memory cache to avoid redundant DB queries within a single request.
     */
    protected static array $cache = [];

    /**
     * Retrieve a setting value by key.
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed
    {
        // Return from static cache if already fetched
        if (array_key_exists($key, static::$cache)) {
            return static::$cache[$key];
        }

        $setting = static::where('key', $key)->first();

        if ($setting) {
            static::$cache[$key] = $setting->value;
            return $setting->value;
        }

        return $default;
    }

    /**
     * Set or update a setting value by key.
     *
     * @param string $key
     * @param mixed $value
     * @return \App\Models\Setting
     */
    public static function set(string $key, mixed $value): self
    {
        $setting = static::updateOrCreate(
            ['key' => $key],
            ['value' => $value]
        );

        // Update static cache
        static::$cache[$key] = $value;

        return $setting;
    }

    /**
     * Clear the in-memory cache (useful for testing).
     */
    public static function clearCache(): void
    {
        static::$cache = [];
    }
}
