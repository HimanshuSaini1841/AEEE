<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * High performance mock tests schema engineered to handle thousands
     * of concurrent student test submissions with zero deadlocks or table scans.
     */
    public function up(): void
    {
        // 1. Mock Tests Table
        Schema::create('mock_tests', function (Blueprint $table) {
            $table->id();
            $table->string('title', 191);
            $table->string('slug', 191)->unique();
            $table->text('description')->nullable();
            $table->unsignedInteger('duration_minutes')->default(150); // 150 mins for AEEE (2.5 hrs)
            $table->unsignedInteger('total_marks')->default(300);      // 300 marks for AEEE
            $table->unsignedInteger('total_questions')->default(100);  // 100 questions for AEEE
            $table->decimal('positive_marks_default', 4, 2)->default(3.00); // +3 for AEEE
            $table->decimal('negative_marks_default', 4, 2)->default(1.00); // -1 for AEEE
            $table->longText('instructions')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->integer('order_num')->default(0)->index();
            $table->timestamps();

            $table->index(['is_active', 'order_num'], 'idx_mock_tests_active_order');
        });

        // 2. Mock Test Questions Table (LaTeX supported in text & options)
        Schema::create('mock_test_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_test_id')->constrained('mock_tests')->cascadeOnDelete();
            $table->string('subject', 50)->default('Mathematics')->index(); // Mathematics, Physics, Chemistry, English
            $table->mediumText('question_text');
            $table->string('question_image', 500)->nullable();
            $table->mediumText('option_a');
            $table->mediumText('option_b');
            $table->mediumText('option_c');
            $table->mediumText('option_d');
            $table->string('correct_option', 5); // 'A', 'B', 'C', 'D'
            $table->mediumText('explanation')->nullable();
            $table->decimal('positive_marks', 4, 2)->default(3.00);
            $table->decimal('negative_marks', 4, 2)->default(1.00);
            $table->integer('order_num')->default(1);
            $table->timestamps();

            $table->index(['mock_test_id', 'subject', 'order_num'], 'idx_questions_test_subj_order');
            $table->index(['mock_test_id', 'id'], 'idx_questions_test_id');
        });

        // 3. Mock Test Student Attempts Table
        Schema::create('mock_test_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mock_test_id')->constrained('mock_tests')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->decimal('score', 6, 2)->default(0.00);
            $table->decimal('total_marks', 6, 2)->default(0.00);
            $table->unsignedInteger('correct_count')->default(0);
            $table->unsignedInteger('wrong_count')->default(0);
            $table->unsignedInteger('unattempted_count')->default(0);
            $table->decimal('accuracy_percentage', 5, 2)->default(0.00);
            $table->unsignedInteger('time_taken_seconds')->default(0);
            $table->json('subject_breakdown')->nullable(); // JSON breakdown per subject
            $table->enum('status', ['in_progress', 'completed', 'abandoned'])->default('completed')->index();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();

            // High-speed index for leaderboard, rank computation & student records
            $table->index(['mock_test_id', 'student_id'], 'idx_attempts_test_student');
            $table->index(['student_id', 'created_at'], 'idx_attempts_student_created');
            $table->index(['mock_test_id', 'score'], 'idx_attempts_test_score');
        });

        // 4. Mock Test Attempt Answers Table
        Schema::create('mock_test_attempt_answers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attempt_id')->constrained('mock_test_attempts')->cascadeOnDelete();
            $table->foreignId('question_id')->constrained('mock_test_questions')->cascadeOnDelete();
            $table->string('selected_option', 5)->nullable(); // 'A', 'B', 'C', 'D' or null
            $table->boolean('is_correct')->default(false);
            $table->decimal('marks_awarded', 4, 2)->default(0.00);
            $table->timestamps();

            $table->index(['attempt_id', 'question_id'], 'idx_attempt_answers_attempt_q');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mock_test_attempt_answers');
        Schema::dropIfExists('mock_test_attempts');
        Schema::dropIfExists('mock_test_questions');
        Schema::dropIfExists('mock_tests');
    }
};
