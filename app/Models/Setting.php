<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    protected $fillable = ['key', 'value'];

    private const CACHE_KEY = 'settings:all';

    /** In-memory copy for the lifetime of the request — the `database` cache driver still
     *  costs a query per read, so this is what actually stops repeated lookups in one request. */
    private static ?array $memo = null;

    /**
     * All settings as [key => value], memoized per-request and cached forever across
     * requests, invalidated on write — avoids re-querying the whole table constantly.
     */
    public static function allValues(): array
    {
        return self::$memo ??= Cache::rememberForever(self::CACHE_KEY, fn () => self::query()->pluck('value', 'key')->toArray());
    }

    public static function getValue($key, $default = null)
    {
        return self::allValues()[$key] ?? $default;
    }

    public static function setValue($key, $value)
    {
        $setting = self::updateOrCreate(['key' => $key], ['value' => $value]);
        Cache::forget(self::CACHE_KEY);
        self::$memo = null;

        return $setting;
    }
}
