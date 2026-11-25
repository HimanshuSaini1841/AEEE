<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Services\DynamicFieldCacheService;

class StudentClass extends Model
{
    protected $table = 'student_classes';

    protected $fillable = [
        'name',
        'slug',
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
        return $this->hasMany(Student::class, 'class_id');
    }

    protected static function booted(): void
    {
        // Flush cached profile options when modified in Admin Panel
        static::saved(fn () => DynamicFieldCacheService::clearCache());
        static::deleted(fn () => DynamicFieldCacheService::clearCache());
    }
}
