<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockTestAttemptAnswer extends Model
{
    use HasFactory;

    protected $fillable = [
        'attempt_id',
        'question_id',
        'selected_option',
        'is_correct',
        'marks_awarded',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
        'marks_awarded' => 'decimal:2',
    ];

    public function attempt(): BelongsTo
    {
        return $this->belongsTo(MockTestAttempt::class, 'attempt_id');
    }

    public function question(): BelongsTo
    {
        return $this->belongsTo(MockTestQuestion::class, 'question_id');
    }
}
