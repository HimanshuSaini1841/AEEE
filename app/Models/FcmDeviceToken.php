<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FcmDeviceToken extends Model
{
    protected $table = 'fcm_device_tokens';

    protected $fillable = [
        'student_id',
        'token',
        'device_type',
        'is_active',
        'last_used_at',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'last_used_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class, 'student_id');
    }
}
