<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Cache;

class MockTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'duration_minutes',
        'start_time',
        'end_time',
        'total_marks',
        'total_questions',
        'positive_marks_default',
        'negative_marks_default',
        'instructions',
        'is_active',
        'order_num',
        'is_pyq',
        'pyq_year',
        'pyq_id',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'total_marks' => 'integer',
        'total_questions' => 'integer',
        'positive_marks_default' => 'decimal:2',
        'negative_marks_default' => 'decimal:2',
        'is_active' => 'boolean',
        'order_num' => 'integer',
        'is_pyq' => 'boolean',
        'pyq_year' => 'integer',
        'pyq_id' => 'integer',
    ];

    public function questions(): HasMany
    {
        return $this->hasMany(MockTestQuestion::class)->orderBy('order_num', 'asc');
    }

    public function attempts(): HasMany
    {
        return $this->hasMany(MockTestAttempt::class);
    }

    public function pyq(): BelongsTo
    {
        return $this->belongsTo(Pyq::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePyq($query, ?int $year = null)
    {
        $q = $query->where('is_pyq', true);
        if ($year) {
            $q->where('pyq_year', $year);
        }
        return $q;
    }

    public function clearCache(): void
    {
        Cache::forget('mock_tests_active_list');
        Cache::forget("mock_test_payload_{$this->id}");
        Cache::forget("mock_test_answer_key_{$this->id}");
    }

    protected static function booted()
    {
        static::saved(function ($mockTest) {
            $mockTest->clearCache();
        });

        static::deleted(function ($mockTest) {
            $mockTest->clearCache();
        });
    }
}
