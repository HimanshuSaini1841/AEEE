<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * High-speed indexed key-value settings table with RAM micro-caching.
     */
    public function up(): void
    {
        Schema::create('app_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('group', 50)->default('general')->index();
            $table->string('description', 255)->nullable();
            $table->boolean('is_secret')->default(false);
            $table->timestamps();

            $table->index(['group', 'key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('app_settings');
    }
};
