#!/usr/bin/env php
<?php
/**
 * Video Player Diagnostic Script
 * Run this to check video player configuration
 */

echo "=== SundayLearn Video Player Diagnostic ===\n\n";

// Check if we're in the right directory
if (!file_exists('artisan')) {
    echo "❌ Error: Please run this script from the project root directory\n";
    exit(1);
}

echo "✅ Running from project root\n\n";

// 1. Check storage symlink
echo "1. Checking storage symlink...\n";
$symlinkPath = __DIR__ . '/public/storage';
if (is_link($symlinkPath)) {
    echo "   ✅ Symlink exists: public/storage -> " . readlink($symlinkPath) . "\n";
} elseif (file_exists($symlinkPath)) {
    echo "   ⚠️  Path exists but is not a symlink (may be a directory)\n";
} else {
    echo "   ❌ Symlink does not exist\n";
    echo "   💡 Run: php artisan storage:link\n";
}
echo "\n";

// 2. Check storage directories
echo "2. Checking storage directories...\n";
$storagePublic = __DIR__ . '/storage/app/public';
if (is_dir($storagePublic)) {
    echo "   ✅ storage/app/public exists\n";
    
    // Check for lessons directory
    $lessonsDir = $storagePublic . '/lessons';
    if (is_dir($lessonsDir)) {
        $files = glob($lessonsDir . '/*');
        echo "   ✅ storage/app/public/lessons exists (" . count($files) . " files)\n";
        
        // List video files
        $videoExtensions = ['mp4', 'avi', 'mov', 'wmv', 'webm'];
        $videoFiles = [];
        foreach ($files as $file) {
            $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
            if (in_array($ext, $videoExtensions)) {
                $videoFiles[] = basename($file);
            }
        }
        
        if (count($videoFiles) > 0) {
            echo "   📹 Video files found:\n";
            foreach ($videoFiles as $video) {
                echo "      - $video\n";
            }
        } else {
            echo "   ⚠️  No video files found in lessons directory\n";
        }
    } else {
        echo "   ⚠️  storage/app/public/lessons does not exist\n";
        echo "   💡 Create it or upload videos through admin panel\n";
    }
} else {
    echo "   ❌ storage/app/public does not exist\n";
}
echo "\n";

// 3. Check database connection and lessons
echo "3. Checking database...\n";
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $lessonCount = \App\Models\Lesson::count();
    echo "   ✅ Database connected\n";
    echo "   📚 Total lessons: $lessonCount\n";
    
    if ($lessonCount > 0) {
        // Check for lessons with videos
        $withVideoUrl = \App\Models\Lesson::whereNotNull('video_url')->count();
        $withAttachments = \App\Models\Lesson::whereNotNull('attachments')->count();
        
        echo "   📹 Lessons with video_url: $withVideoUrl\n";
        echo "   📎 Lessons with attachments: $withAttachments\n";
        
        // Show first lesson details
        $firstLesson = \App\Models\Lesson::first();
        if ($firstLesson) {
            echo "\n   First lesson details:\n";
            echo "   - ID: {$firstLesson->id}\n";
            echo "   - Title: {$firstLesson->title}\n";
            echo "   - Video URL: " . ($firstLesson->video_url ?: 'None') . "\n";
            echo "   - Attachments: " . (is_array($firstLesson->attachments) ? count($firstLesson->attachments) : 0) . "\n";
            
            if (is_array($firstLesson->attachments) && count($firstLesson->attachments) > 0) {
                echo "   - Attachment details:\n";
                foreach ($firstLesson->attachments as $index => $attachment) {
                    echo "     [" . ($index + 1) . "] " . ($attachment['name'] ?? 'N/A') . 
                         " (" . ($attachment['type'] ?? 'N/A') . ")\n";
                    echo "         URL: " . ($attachment['url'] ?? 'N/A') . "\n";
                }
            }
        }
    }
} catch (\Exception $e) {
    echo "   ❌ Database error: " . $e->getMessage() . "\n";
}
echo "\n";

// 4. Check PHP configuration
echo "4. Checking PHP configuration...\n";
$uploadMaxFilesize = ini_get('upload_max_filesize');
$postMaxSize = ini_get('post_max_size');
$maxExecutionTime = ini_get('max_execution_time');
$memoryLimit = ini_get('memory_limit');

echo "   - upload_max_filesize: $uploadMaxFilesize\n";
echo "   - post_max_size: $postMaxSize\n";
echo "   - max_execution_time: $maxExecutionTime seconds\n";
echo "   - memory_limit: $memoryLimit\n";

// Convert to bytes for comparison
function convertToBytes($value) {
    $value = trim($value);
    $last = strtolower($value[strlen($value)-1]);
    $value = (int) $value;
    switch($last) {
        case 'g': $value *= 1024;
        case 'm': $value *= 1024;
        case 'k': $value *= 1024;
    }
    return $value;
}

$uploadBytes = convertToBytes($uploadMaxFilesize);
$postBytes = convertToBytes($postMaxSize);

if ($uploadBytes < 10485760) { // 10MB
    echo "   ⚠️  upload_max_filesize is low (recommended: 100M)\n";
}
if ($postBytes < 10485760) {
    echo "   ⚠️  post_max_size is low (recommended: 100M)\n";
}

echo "\n";

// 5. Check .env configuration
echo "5. Checking .env configuration...\n";
$envPath = __DIR__ . '/.env';
if (file_exists($envPath)) {
    echo "   ✅ .env file exists\n";
    $appUrl = env('APP_URL', 'Not set');
    $appDebug = env('APP_DEBUG', false) ? 'true' : 'false';
    echo "   - APP_URL: $appUrl\n";
    echo "   - APP_DEBUG: $appDebug\n";
} else {
    echo "   ❌ .env file not found\n";
    echo "   💡 Copy .env.example to .env\n";
}
echo "\n";

// Summary
echo "=== Summary ===\n";
echo "✅ Storage symlink: " . (is_link($symlinkPath) ? 'OK' : 'MISSING') . "\n";
echo "✅ Storage directory: " . (is_dir($storagePublic) ? 'OK' : 'MISSING') . "\n";
echo "✅ Database: " . ($lessonCount >= 0 ? 'OK' : 'ERROR') . "\n";
echo "✅ PHP upload config: " . ($uploadBytes >= 10485760 ? 'OK' : 'LOW') . "\n";

echo "\n=== Next Steps ===\n";
if (!is_link($symlinkPath)) {
    echo "1. Run: php artisan storage:link\n";
}
if ($lessonCount == 0) {
    echo "2. Create a lesson through admin panel at /admin/lessons\n";
} else {
    echo "2. Visit lesson at: " . env('APP_URL', 'http://localhost:8000') . "/lessons/1\n";
    echo "3. Open browser console (F12) and check for video errors\n";
}
echo "4. Check the video_player_fix.md guide for detailed troubleshooting\n";

echo "\n";
