<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Student extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'students';

    protected $fillable = [
        'google_id',
        'email',
        'password',
        'name',
        'avatar_url',
        'phone',
        'is_phone_verified',
        'phone_verified_at',
        'class_id',
        'state_id',
        'city',
        'preparing_for_id',
        'is_profile_completed',
        'status',
        'last_active_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $with = [
        'preparingFors',
    ];

    protected $appends = [
        'preparing_for_ids',
    ];

    public function getPreparingForIdsAttribute(): array
    {
        $ids = $this->preparingFors->pluck('id')->values()->toArray();
        if (!empty($ids)) {
            return $ids;
        }
        return $this->preparing_for_id ? [(int) $this->preparing_for_id] : [];
    }

    protected function casts(): array
    {
        return [
            'is_phone_verified' => 'boolean',
            'is_profile_completed' => 'boolean',
            'phone_verified_at' => 'datetime',
            'last_active_at' => 'datetime',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function studentClass(): BelongsTo
    {
        return $this->belongsTo(StudentClass::class, 'class_id');
    }

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class, 'state_id');
    }

    public function preparingFor(): BelongsTo
    {
        return $this->belongsTo(PreparingFor::class, 'preparing_for_id');
    }

    public function preparingFors(): BelongsToMany
    {
        return $this->belongsToMany(PreparingFor::class, 'student_preparing_fors', 'student_id', 'preparing_for_id');
    }

    public function deviceTokens(): HasMany
    {
        return $this->hasMany(FcmDeviceToken::class, 'student_id');
    }

    public function mockTestAttempts(): HasMany
    {
        return $this->hasMany(MockTestAttempt::class, 'student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | High-Speed Query Scopes (Optimized for Millions of Records)
    |--------------------------------------------------------------------------
    */

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', 'active');
    }

    public function scopeCompletedProfile(Builder $query): Builder
    {
        return $query->where('is_profile_completed', true);
    }

    public function scopeIncompleteProfile(Builder $query): Builder
    {
        return $query->where('is_profile_completed', false);
    }

    public function scopeVerified(Builder $query): Builder
    {
        return $query->where('is_phone_verified', true);
    }

    /**
     * Fast prefix index search using indexed columns (avoids slow full table scan)
     */
    public function scopeFastSearch(Builder $query, ?string $term): Builder
    {
        if (empty($term)) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('phone', 'like', $term . '%')
              ->orWhere('email', 'like', $term . '%')
              ->orWhere('name', 'like', $term . '%');
        });
    }

    /**
     * Filter students by demographic criteria (used for target push notifications)
     */
    public function scopeAudienceTarget(Builder $query, array $criteria): Builder
    {
        if (!empty($criteria['class_id'])) {
            $query->where('class_id', $criteria['class_id']);
        }
        if (!empty($criteria['state_id'])) {
            $query->where('state_id', $criteria['state_id']);
        }
        if (!empty($criteria['preparing_for_id'])) {
            $query->where('preparing_for_id', $criteria['preparing_for_id']);
        }
        return $query;
    }
}
