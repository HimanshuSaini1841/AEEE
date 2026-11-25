<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MockTestAttempt extends Model
{
    use HasFactory;

    protected $fillable = [
        'mock_test_id',
        'student_id',
        'score',
        'total_marks',
        'correct_count',
        'wrong_count',
        'unattempted_count',
        'accuracy_percentage',
        'time_taken_seconds',
        'subject_breakdown',
        'topic_breakdown',
        'status',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'total_marks' => 'decimal:2',
        'correct_count' => 'integer',
        'wrong_count' => 'integer',
        'unattempted_count' => 'integer',
        'accuracy_percentage' => 'decimal:2',
        'time_taken_seconds' => 'integer',
        'subject_breakdown' => 'array',
        'topic_breakdown' => 'array',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function mockTest(): BelongsTo
    {
        return $this->belongsTo(MockTest::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(MockTestAttemptAnswer::class, 'attempt_id');
    }

    /**
     * Compute rank instantly using indexed B-Tree score lookup.
     */
    public function getRankAttribute(): int
    {
        return static::where('mock_test_id', $this->mock_test_id)
            ->where('score', '>', $this->score)
            ->count() + 1;
    }

    public function getTotalAttemptsAttribute(): int
    {
        return static::where('mock_test_id', $this->mock_test_id)->count();
    }
}
