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
        Schema::table('mock_tests', function (Blueprint $table) {
            $table->boolean('is_pyq')->default(false)->after('order_num')->index();
            $table->unsignedSmallInteger('pyq_year')->nullable()->after('is_pyq')->index();
            $table->foreignId('pyq_id')->nullable()->after('pyq_year')->constrained('pyqs')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mock_tests', function (Blueprint $table) {
            $table->dropForeign(['pyq_id']);
            $table->dropColumn(['is_pyq', 'pyq_year', 'pyq_id']);
        });
    }
};
