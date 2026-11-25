<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MockTestQuestion extends Model
{
    use HasFactory;

    protected $fillable = [
        'mock_test_id',
        'subject',
        'topic',
        'question_text',
        'question_image',
        'option_a',
        'option_b',
        'option_c',
        'option_d',
        'correct_option',
        'explanation',
        'positive_marks',
        'negative_marks',
        'order_num',
    ];

    protected $casts = [
        'positive_marks' => 'decimal:2',
        'negative_marks' => 'decimal:2',
        'order_num' => 'integer',
    ];

    public function mockTest(): BelongsTo
    {
        return $this->belongsTo(MockTest::class);
    }

    protected static function booted()
    {
        static::saved(function ($question) {
            $question->mockTest?->clearCache();
        });

        static::deleted(function ($question) {
            $question->mockTest?->clearCache();
        });
    }
}
