<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\DynamicFieldCacheService;

class State extends Model
{
    protected $table = 'states';

    protected $fillable = [
        'name',
        'code',
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
        return $this->hasMany(Student::class, 'state_id');
    }

    protected static function booted(): void
    {
        static::saved(fn () => DynamicFieldCacheService::clearCache());
        static::deleted(fn () => DynamicFieldCacheService::clearCache());
    }
}
