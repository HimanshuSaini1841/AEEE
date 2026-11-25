<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $table = 'otp_verifications';

    protected $fillable = [
        'phone',
        'otp_hash',
        'expires_at',
        'attempts',
        'is_verified',
        'fast2sms_request_id',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'is_verified' => 'boolean',
            'attempts' => 'integer',
        ];
    }

    public function isValidOtp(string $otp): bool
    {
        if ($this->is_verified || $this->expires_at->isPast()) {
            return false;
        }

        return hash_equals($this->otp_hash, hash('sha256', $otp));
    }
}
