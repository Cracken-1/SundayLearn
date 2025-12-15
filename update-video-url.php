<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Updating lesson video URL...\n";

$lesson = App\Models\Lesson::find(1);
if ($lesson) {
    echo "Current video_url: " . ($lesson->video_url ?: 'NULL') . "\n";
    
    // Clear the invalid YouTube URL
    $lesson->video_url = null;
    $lesson->save();
    
    echo "✅ Cleared invalid YouTube URL\n";
    echo "✅ Local MP4 attachment will now be used\n";
    echo "\nLesson details:\n";
    echo "- Title: {$lesson->title}\n";
    echo "- Attachments: " . (is_array($lesson->attachments) ? count($lesson->attachments) : 0) . "\n";
    
    if (is_array($lesson->attachments)) {
        foreach ($lesson->attachments as $index => $attachment) {
            $type = strtolower($attachment['type'] ?? '');
            if (in_array($type, ['mp4', 'avi', 'mov', 'wmv', 'webm'])) {
                echo "\n📹 Video attachment found:\n";
                echo "   Name: " . ($attachment['name'] ?? 'N/A') . "\n";
                echo "   Type: " . strtoupper($type) . "\n";
                echo "   URL: " . ($attachment['url'] ?? 'N/A') . "\n";
            }
        }
    }
} else {
    echo "❌ Lesson not found\n";
}

echo "\n✅ Done! Refresh the lesson page to see the video player.\n";
