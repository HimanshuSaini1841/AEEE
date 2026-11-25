<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Dynamic Classes (Class 11, Class 12, Dropper, etc.)
        Schema::create('student_classes', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('slug', 100)->unique();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });

        // 2. Dynamic States
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('code', 10)->nullable()->index();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['is_active', 'display_order', 'name']);
        });

        // 3. Dynamic Preparing For (AEEE, JEE Main, NEET, etc.)
        Schema::create('preparing_fors', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120);
            $table->string('short_name', 50)->nullable();
            $table->string('description', 255)->nullable();
            $table->unsignedInteger('display_order')->default(0)->index();
            $table->boolean('is_active')->default(true)->index();
            $table->timestamps();

            $table->index(['is_active', 'display_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('preparing_fors');
        Schema::dropIfExists('states');
        Schema::dropIfExists('student_classes');
    }
};
