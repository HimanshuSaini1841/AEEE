<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * High performance students schema with B-Tree composite indexes
     * designed to handle millions of student rows without table scans.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            
            // Google OAuth fields
            $table->string('google_id', 191)->unique();
            $table->string('email', 191)->unique();
            $table->string('name', 150);
            $table->string('avatar_url', 500)->nullable();

            // Mobile number & verification
            $table->string('phone', 20)->nullable()->index();
            $table->boolean('is_phone_verified')->default(false)->index();
            $table->timestamp('phone_verified_at')->nullable()->index();

            // Dynamic profile choices (Foreign keys)
            $table->foreignId('class_id')->nullable()->constrained('student_classes')->nullOnDelete();
            $table->foreignId('state_id')->nullable()->constrained('states')->nullOnDelete();
            $table->foreignId('preparing_for_id')->nullable()->constrained('preparing_fors')->nullOnDelete();

            // Profile status flags
            $table->boolean('is_profile_completed')->default(false)->index();
            $table->enum('status', ['active', 'blocked'])->default('active')->index();
            $table->timestamp('last_active_at')->nullable()->index();

            $table->timestamps();

            // Optimized Composite Indexes for millions of records
            $table->index(['is_profile_completed', 'created_at'], 'idx_profile_created');
            $table->index(['class_id', 'state_id', 'preparing_for_id'], 'idx_audience_targeting');
            $table->index(['status', 'is_phone_verified', 'created_at'], 'idx_status_verified');
            $table->index(['phone', 'is_phone_verified'], 'idx_phone_lookup');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
