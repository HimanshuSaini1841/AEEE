<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Manages Firebase Cloud Messaging (FCM) registration tokens.
     */
    public function up(): void
    {
        Schema::create('fcm_device_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->string('token', 500)->unique();
            $table->enum('device_type', ['android', 'ios', 'web'])->default('android')->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamp('last_used_at')->nullable()->index();
            $table->timestamps();

            $table->index(['student_id', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fcm_device_tokens');
    }
};
