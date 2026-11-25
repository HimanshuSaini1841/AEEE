<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pyqs', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->unsignedSmallInteger('year')->index();
            $table->string('subject')->default('Full Length')->index();
            $table->string('file_path');
            $table->string('file_name');
            $table->string('file_size_formatted')->default('1.0 MB');
            $table->unsignedBigInteger('file_size_bytes')->default(0);
            $table->unsignedSmallInteger('total_pages')->nullable();
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedBigInteger('view_count')->default(0);
            $table->timestamps();

            $table->index(['year', 'is_active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pyqs');
    }
};
