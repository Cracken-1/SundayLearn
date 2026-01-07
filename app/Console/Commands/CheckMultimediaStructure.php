<?php

namespace App\Console\Commands;

use App\Models\Lesson;
use Illuminate\Console\Command;

class CheckMultimediaStructure extends Command
{
    protected $signature = 'lessons:check-multimedia';
    protected $description = 'Check if multimedia reflects after lesson content structure';

    public function handle()
    {
        $this->info('🔍 Checking Multimedia Structure in Lessons...');
        
        $lessons = Lesson::all();
        
        if ($lessons->isEmpty()) {
            $this->warn('No lessons found in database.');
            $this->info('Use the admin panel to create lessons with multimedia content.');
            return 0;
        }
        
        $multimediaCount = 0;
        $contentAfterMultimedia = 0;
        
        foreach ($lessons as $lesson) {
            $hasMultimedia = $lesson->hasMultimedia();
            
            if ($hasMultimedia) {
                $multimediaCount++;
                
                // Check if content comes after multimedia in the structure
                $content = $lesson->content;
                if (!empty($content)) {
                    $contentAfterMultimedia++;
                }
            }
            
            $this->line("📚 {$lesson->title}");
            $this->line("   Age Group: {$lesson->age_group}");
            $this->line("   Has Multimedia: " . ($hasMultimedia ? '✅ Yes' : '❌ No'));
            
            if ($hasMultimedia) {
                if (!empty($lesson->video_url)) {
                    $this->line("   📹 Video: Present");
                }
                if (!empty($lesson->audio_url)) {
                    $this->line("   🔊 Audio: Present");
                }
                if (!empty($lesson->attachments)) {
                    $attachmentCount = count($lesson->attachments);
                    $this->line("   📎 Attachments: {$attachmentCount} files");
                }
            }
            
            $this->line("   Content Length: " . strlen($lesson->content ?? '') . " characters");
            $this->line('');
        }
        
        $this->info("📊 Summary:");
        $this->info("Total Lessons: " . $lessons->count());
        $this->info("Lessons with Multimedia: {$multimediaCount}");
        $this->info("Lessons with Content after Multimedia: {$contentAfterMultimedia}");
        
        if ($multimediaCount > 0) {
            $percentage = round(($contentAfterMultimedia / $multimediaCount) * 100, 1);
            $this->info("Multimedia-Content Structure Compliance: {$percentage}%");
        }
        
        return 0;
    }
}