<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * High-speed indexed OTP table with SHA-256 hash security & attempt limits.
     */
    public function up(): void
    {
        Schema::create('otp_verifications', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index();
            $table->string('otp_hash', 100);
            $table->dateTime('expires_at')->index();
            $table->unsignedTinyInteger('attempts')->default(0);
            $table->boolean('is_verified')->default(false)->index();
            $table->string('fast2sms_request_id', 100)->nullable();
            $table->timestamps();

            // Composite Index for rapid lookup in < 1ms
            $table->index(['phone', 'is_verified', 'expires_at'], 'idx_phone_valid_otp');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('otp_verifications');
    }
};
