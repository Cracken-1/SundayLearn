<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('admin_activities', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // created, updated, deleted, published, etc.
            $table->string('model_type'); // Lesson, BlogPost, etc.
            $table->unsignedBigInteger('model_id')->nullable();
            $table->string('description');
            $table->json('properties')->nullable(); // Additional data
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();
            
            $table->index(['model_type', 'model_id']);
            $table->index('created_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('admin_activities');
    }
};
