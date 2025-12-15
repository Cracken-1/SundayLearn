<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class MigrateAttachmentsToResources extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:migrate-attachments-to-resources';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate JSON attachments from lessons table to resources table';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting migration of lesson attachments...');

        $lessons = \App\Models\Lesson::whereNotNull('attachments')
            ->where('attachments', '!=', '[]')
            ->get();

        $count = 0;
        $skipped = 0;

        foreach ($lessons as $lesson) {
            $attachments = $lesson->attachments ?? [];
            
            if (empty($attachments)) {
                continue;
            }

            $this->info("Processing lesson: {$lesson->title} (ID: {$lesson->id})");

            foreach ($attachments as $index => $attachment) {
                // Skip media files if desired, or include them ensuring mapping is correct
                $type = strtolower($attachment['type'] ?? 'other');
                
                // Check if already exists to prevent duplicates
                $exists = \App\Models\Resource::where('lesson_id', $lesson->id)
                    ->where('title', $attachment['name'])
                    ->first();
                
                if ($exists) {
                    $skipped++;
                    continue;
                }
                
                // Map file types
                $resourceType = $this->mapFileTypeToResourceType($type);
                
                try {
                    \App\Models\Resource::create([
                        'title' => $attachment['name'],
                        'description' => "Material from lesson: {$lesson->title}",
                        'type' => $resourceType,
                        'file_url' => $this->guessFileUrl($lesson, $attachment, $index),
                        'file_type' => $type,
                        'file_size' => $attachment['size'] ?? 0,
                        'lesson_id' => $lesson->id,
                        'source' => 'lesson',
                        'category' => 'Lesson Materials',
                        'age_group' => $lesson->age_group,
                        'created_at' => $lesson->created_at,
                        'updated_at' => $lesson->updated_at,
                    ]);
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to migrate attachment '{$attachment['name']}' in lesson {$lesson->id}: " . $e->getMessage());
                }
            }
        }

        $this->info("Migration completed. {$count} resources created. {$skipped} skipped.");
    }
    
    private function mapFileTypeToResourceType($fileType)
    {
        $mapping = [
            'pdf' => 'worksheet',
            'doc' => 'worksheet',
            'docx' => 'worksheet',
            'ppt' => 'activity_guide',
            'pptx' => 'activity_guide',
            'jpg' => 'coloring_page',
            'jpeg' => 'coloring_page',
            'png' => 'coloring_page',
            'zip' => 'craft',
            'rar' => 'craft',
        ];
        
        return $mapping[$fileType] ?? 'other';
    }

    private function guessFileUrl($lesson, $attachment, $index = null)
    {
        if (isset($attachment['path'])) {
            return '/storage/' . $attachment['path'];
        }
        
        // Use index if available, otherwise rely on name (which might fail if controller expects index)
        if ($index !== null) {
             return route('lessons.download-attachment', ['lesson' => $lesson->id, 'attachment' => $index]);
        }

        return route('lessons.download-attachment', ['lesson' => $lesson->id, 'attachment' => $attachment['name']]);
    }
}
