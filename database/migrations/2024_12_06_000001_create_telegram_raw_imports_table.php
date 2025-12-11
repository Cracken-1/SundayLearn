<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('telegram_raw_imports', function (Blueprint $table) {
            $table->id();
            $table->string('telegram_message_id')->unique();
            $table->string('telegram_file_id')->nullable();
            $table->string('media_type')->nullable(); // photo, video, audio, document
            $table->text('caption')->nullable();
            $table->json('telegram_data'); // Full Telegram update data
            $table->string('file_url')->nullable(); // Downloaded file URL
            $table->string('file_path')->nullable(); // Local file path
            $table->string('processing_status')->default('pending'); // pending, processing, completed, failed
            $table->text('processing_notes')->nullable();
            $table->foreignId('lesson_id')->nullable()->constrained()->onDelete('set null');
            $table->timestamps();
            
            $table->index(['processing_status', 'created_at']);
            $table->index('telegram_message_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('telegram_raw_imports');
    }
};