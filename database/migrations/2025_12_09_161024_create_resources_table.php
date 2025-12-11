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
        Schema::create('resources', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['worksheet', 'coloring_page', 'activity_guide', 'craft', 'game', 'other'])->default('other');
            $table->string('file_url', 500);
            $table->string('thumbnail', 500)->nullable();
            $table->string('category', 100)->nullable();
            $table->string('age_group', 50)->nullable();
            $table->integer('file_size')->nullable();
            $table->string('file_type', 50)->nullable();
            $table->integer('downloads_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('resources');
    }
};
