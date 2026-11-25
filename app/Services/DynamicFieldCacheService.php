<?php

namespace App\Services;

use App\Models\StudentClass;
use App\Models\State;
use App\Models\PreparingFor;
use Illuminate\Support\Facades\Cache;

class DynamicFieldCacheService
{
    public const CACHE_KEY = 'aeee_active_profile_options';
    public const CACHE_TTL_HOURS = 24;

    /**
     * Get all active options for mobile app profile completion.
     * Cached in RAM (Redis / DB cache) for sub-millisecond retrieval under heavy load.
     */
    public static function getActiveProfileOptions(): array
    {
        return Cache::remember(self::CACHE_KEY, now()->addHours(self::CACHE_TTL_HOURS), function () {
            return [
                'classes' => StudentClass::query()
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->select(['id', 'name', 'slug'])
                    ->get()
                    ->toArray(),

                'states' => State::query()
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->orderBy('name')
                    ->select(['id', 'name', 'code'])
                    ->get()
                    ->toArray(),

                'preparing_for' => PreparingFor::query()
                    ->where('is_active', true)
                    ->orderBy('display_order')
                    ->select(['id', 'name', 'short_name', 'description'])
                    ->get()
                    ->toArray(),
            ];
        });
    }

    /**
     * Clear cache instantly when an Admin modifies Classes, States, or Target Exams.
     */
    public static function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
    }
}
