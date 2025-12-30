<?php

namespace App\Jobs;

use App\Models\Lesson;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ProcessLessonAttachment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $lesson;
    protected $filePath;
    protected $originalName;
    protected $category;

    /**
     * Create a new job instance.
     *
     * @param Lesson $lesson
     * @param string $filePath
     * @param string $originalName
     * @param string $category
     */
    public function __construct(Lesson $lesson, string $filePath, string $originalName, string $category)
    {
        $this->lesson = $lesson;
        $this->filePath = $filePath;
        $this->originalName = $originalName;
        $this->category = $category;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file = new \Illuminate\Http\File($this->filePath);

        $filename = time() . '_' . uniqid() . '_' . $this->originalName;
        $path = Storage::disk('public')->putFileAs("lessons/{$this->category}", $file, $filename);

        $attachmentData = [
            'name' => $this->originalName,
            'filename' => $filename,
            'path' => $path,
            'url' => asset('storage/' . $path),
            'type' => $file->extension(),
            'mime_type' => $file->getMimeType(),
            'size' => $file->getSize(),
            'category' => $this->category,
            'uploaded_at' => now()->toDateTimeString()
        ];

        $attachments = $this->lesson->attachments ?? [];
        $attachments[] = $attachmentData;
        $this->lesson->update(['attachments' => $attachments]);

        // Delete the temporary file
        unlink($this->filePath);
    }
}
