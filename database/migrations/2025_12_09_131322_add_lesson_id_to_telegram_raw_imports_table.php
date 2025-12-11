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
        Schema::table('telegram_raw_imports', function (Blueprint $table) {
            // Add lesson_id column if it doesn't exist (table already has created_lesson_id)
            // This migration is kept for consistency but may not be needed
            if (!Schema::hasColumn('telegram_raw_imports', 'lesson_id')) {
                $table->foreignId('lesson_id')->nullable()->after('error_message')->constrained()->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('telegram_raw_imports', function (Blueprint $table) {
            // Drop the lesson_id column if it exists
            if (Schema::hasColumn('telegram_raw_imports', 'lesson_id')) {
                $table->dropForeign(['lesson_id']);
                $table->dropColumn('lesson_id');
            }
        });
    }
};
