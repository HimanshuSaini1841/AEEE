<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('mock_test_questions', function (Blueprint $table) {
            $table->string('topic', 100)->nullable()->after('subject')->index();
        });

        Schema::table('mock_test_attempts', function (Blueprint $table) {
            $table->json('topic_breakdown')->nullable()->after('subject_breakdown');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mock_test_questions', function (Blueprint $table) {
            $table->dropColumn('topic');
        });

        Schema::table('mock_test_attempts', function (Blueprint $table) {
            $table->dropColumn('topic_breakdown');
        });
    }
};
