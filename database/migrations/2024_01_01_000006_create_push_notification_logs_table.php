<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Logs push notifications triggered from Admin Panel to students.
     */
    public function up(): void
    {
        Schema::create('push_notification_logs', function (Blueprint $table) {
            $table->id();
            $table->string('title', 150);
            $table->text('body');
            $table->string('image_url', 500)->nullable();
            $table->json('target_criteria')->nullable(); // e.g. {"class_id": 2, "state_id": null}
            $table->unsignedBigInteger('total_targets')->default(0);
            $table->unsignedBigInteger('success_count')->default(0);
            $table->unsignedBigInteger('failure_count')->default(0);
            $table->foreignId('admin_id')->nullable()->constrained('admin_users')->nullOnDelete();
            $table->string('status', 30)->default('pending')->index(); // pending, processing, completed, failed
            $table->timestamps();

            $table->index(['created_at', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('push_notification_logs');
    }
};
