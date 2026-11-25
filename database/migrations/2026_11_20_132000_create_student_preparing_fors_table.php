<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('student_preparing_fors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained('students')->cascadeOnDelete();
            $table->foreignId('preparing_for_id')->constrained('preparing_fors')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['student_id', 'preparing_for_id'], 'student_prep_unique');
            $table->index('preparing_for_id');
        });

        // Seed existing preparing_for_id records into the pivot table
        DB::statement("
            INSERT IGNORE INTO student_preparing_fors (student_id, preparing_for_id, created_at, updated_at)
            SELECT id, preparing_for_id, NOW(), NOW()
            FROM students
            WHERE preparing_for_id IS NOT NULL
        ");
    }

    public function down(): void
    {
        Schema::dropIfExists('student_preparing_fors');
    }
};
