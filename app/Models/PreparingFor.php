<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\DynamicFieldCacheService;

class PreparingFor extends Model
{
    protected $table = 'preparing_fors';

    protected $fillable = [
        'name',
        'short_name',
        'description',
        'display_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'display_order' => 'integer',
        ];
    }

    public function students(): HasMany
    {
        return $this->hasMany(Student::class, 'preparing_for_id');
    }

    protected static function booted(): void
    {
        static::saved(fn () => DynamicFieldCacheService::clearCache());
        static::deleted(fn () => DynamicFieldCacheService::clearCache());
    }
}
