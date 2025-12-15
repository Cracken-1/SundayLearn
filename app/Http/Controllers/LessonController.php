<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        // Get all published lessons from database
        // Get all published lessons from database (paginated)
        $lessons = Lesson::published()
            ->orderBy('created_at', 'desc')
            ->paginate(12)
            ->through(function($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'scripture' => $lesson->scripture ?? 'N/A',
                    'theme' => $lesson->theme ?? 'Biblical Teaching',
                    'age_group' => $lesson->age_group ?? 'All Ages',
                    'duration' => $lesson->duration ? $lesson->duration . ' minutes' : '30 minutes',
                    'thumbnail' => $this->getBestThumbnail($lesson),
                    'video_url' => $lesson->video_url,
                    'audio_url' => $lesson->audio_url,
                    'attachments' => $lesson->attachments ?? [],
                    'has_video' => !empty($lesson->video_url) || $this->hasAttachmentType($lesson->attachments, 'video'),
                    'has_audio' => !empty($lesson->audio_url) || $this->hasAttachmentType($lesson->attachments, 'audio'),
                    'has_documents' => $this->hasAttachmentType($lesson->attachments, ['pdf', 'doc', 'docx', 'ppt', 'pptx']),
                    'is_from_db' => true, // Ensure this flag is preserved
                ];
            });
        
        // Get trending lessons (most viewed)
        $trendingLessons = \Cache::remember('trending_lessons', 900, function() {
            return Lesson::published()
                ->orderBy('views_count', 'desc')
                ->take(3)
                ->get()
                ->map(function($lesson) {
                    return [
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'views_count' => $lesson->views_count,
                        'age_group' => $lesson->age_group ?? 'All Ages',
                    ];
                });
        });
        
        // Get upcoming events
        $upcomingEvents = \App\Models\Event::upcoming()->take(2)->get();
        
        // Get random teaching tip
        $teachingTip = \App\Models\TeachingTip::getRandomTip();
        
        return view('lessons.index', compact('lessons', 'trendingLessons', 'upcomingEvents', 'teachingTip'));
    }
    
    /**
     * Get the best thumbnail for a lesson
     * Priority: 1. Image attachments, 2. Video attachments, 3. Video URL thumbnail, 4. Set thumbnail, 5. Default
     */
    private function getBestThumbnail($lesson)
    {
        // First priority: Look for image attachments
        if (!empty($lesson->attachments)) {
            foreach ($lesson->attachments as $attachment) {
                $type = strtolower($attachment['type'] ?? '');
                if (in_array($type, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    return $attachment['url'] ?? 'default.jpg';
                }
            }
            
            // Second priority: Use video file itself as thumbnail (browser will show first frame)
            foreach ($lesson->attachments as $attachment) {
                $type = strtolower($attachment['type'] ?? '');
                if (in_array($type, ['mp4', 'webm'])) {
                    // Return the video URL - browsers can generate thumbnails from MP4/WebM
                    return $attachment['url'] ?? 'video-attachment-placeholder.jpg';
                }
            }
            
            // Third priority: Other video formats (use placeholder)
            foreach ($lesson->attachments as $attachment) {
                $type = strtolower($attachment['type'] ?? '');
                if (in_array($type, ['avi', 'mov', 'wmv'])) {
                    return 'video-attachment-placeholder.jpg';
                }
            }
        }
        
        // Fourth priority: Video URL thumbnail (YouTube/Vimeo)
        if (!empty($lesson->video_url)) {
            return $this->getVideoThumbnail($lesson->video_url);
        }
        
        // Fifth priority: Set thumbnail field
        if (!empty($lesson->thumbnail)) {
            return $lesson->thumbnail;
        }
        
        // Sixth priority: Audio placeholder if has audio
        if (!empty($lesson->audio_url) || $this->hasAttachmentType($lesson->attachments, ['mp3', 'wav', 'ogg', 'm4a'])) {
            return 'audio-placeholder.jpg';
        }
        
        // Final fallback
        return 'default.jpg';
    }
    
    /**
     * Extract thumbnail from video URL (YouTube, Vimeo, etc.)
     */
    private function getVideoThumbnail($url)
    {
        // YouTube
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
        }
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
        }
        
        // Vimeo
        if (preg_match('/vimeo\.com\/(\d+)/', $url, $matches)) {
            // Vimeo requires API call for thumbnail, return placeholder for now
            return 'video-placeholder.jpg';
        }
        
        return 'video-placeholder.jpg';
    }
    
    /**
     * Check if lesson has attachments of specific type(s)
     */
    private function hasAttachmentType($attachments, $types)
    {
        if (empty($attachments)) {
            return false;
        }
        
        $types = is_array($types) ? $types : [$types];
        
        // Handle special type groups
        $expandedTypes = [];
        foreach ($types as $type) {
            switch ($type) {
                case 'video':
                    $expandedTypes = array_merge($expandedTypes, ['mp4', 'avi', 'mov', 'wmv', 'webm']);
                    break;
                case 'audio':
                    $expandedTypes = array_merge($expandedTypes, ['mp3', 'wav', 'ogg', 'm4a']);
                    break;
                case 'image':
                    $expandedTypes = array_merge($expandedTypes, ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    break;
                default:
                    $expandedTypes[] = $type;
            }
        }
        
        foreach ($attachments as $attachment) {
            $type = strtolower($attachment['type'] ?? '');
            if (in_array($type, $expandedTypes)) {
                return true;
            }
        }
        
        return false;
    }

    public function show($id)
    {
        // Get lesson from database
        $lessonModel = Lesson::findOrFail($id);
        
        // Increment view count
        $lessonModel->incrementViews();
        
        // Format lesson data
        $lesson = [
            'id' => $lessonModel->id,
            'title' => $lessonModel->title,
            'scripture' => $lessonModel->scripture ?? 'N/A',
            'theme' => $lessonModel->theme ?? 'Biblical Teaching',
            'age_group' => $lessonModel->age_group ?? 'All Ages',
            'duration' => $lessonModel->duration ? $lessonModel->duration . ' minutes' : '30 minutes',
            'thumbnail' => $lessonModel->thumbnail ?? 'default.jpg',
            'overview' => $lessonModel->overview ?? '',
            'objectives' => $lessonModel->objectives ?? [],
            'content' => $lessonModel->content,
            'discussion_questions' => $lessonModel->discussion_questions ?? [],
            'video_url' => $lessonModel->video_url,
            'audio_url' => $lessonModel->audio_url,
            'downloads' => $lessonModel->downloads ?? [],
            'attachments' => $lessonModel->attachments ?? [],
        ];

        // Get related lessons (same age group or category)
        $relatedLessons = Lesson::published()
            ->where('id', '!=', $id)
            ->where(function($query) use ($lessonModel) {
                $query->where('age_group', $lessonModel->age_group)
                      ->orWhere('category', $lessonModel->category);
            })
            ->take(3)
            ->get()
            ->map(function($l) {
                return [
                    'id' => $l->id,
                    'title' => $l->title,
                    'scripture' => $l->scripture ?? 'N/A',
                    'theme' => $l->theme ?? 'Biblical Teaching',
                    'age_group' => $l->age_group ?? 'All Ages',
                    'duration' => $l->duration ? $l->duration . ' minutes' : '30 minutes',
                    'thumbnail' => $this->getBestThumbnail($l),
                ];
            })
            ->toArray();

        return view('lessons.show', compact('lesson', 'relatedLessons'));
    }
    
    public function downloadAttachment($lessonId, $attachmentIndex)
    {
        try {
            // Find the lesson
            $lesson = Lesson::findOrFail($lessonId);
            
            // Get attachments
            $attachments = $lesson->attachments ?? [];
            
            // Validate attachment index
            if (!isset($attachments[$attachmentIndex])) {
                abort(404, 'Attachment not found');
            }
            
            $attachment = $attachments[$attachmentIndex];
            
            // Validate file URL
            if (empty($attachment['url'])) {
                abort(404, 'File not found');
            }
            
            // Security check: ensure file URL is safe
            if (!$this->isValidFileUrl($attachment['url'])) {
                \Log::warning('Invalid file URL attempted: ' . $attachment['url']);
                abort(403, 'Invalid file access');
            }
            
            // Rate limiting for downloads
            $key = 'download_lesson_' . request()->ip() . '_' . $lessonId . '_' . $attachmentIndex;
            if (\Cache::has($key)) {
                abort(429, 'Too many download attempts');
            }
            \Cache::put($key, true, 60); // 1 minute cooldown
            
            // Log download for analytics
            \Log::info('Lesson attachment downloaded', [
                'lesson_id' => $lessonId,
                'attachment_index' => $attachmentIndex,
                'filename' => $attachment['name'] ?? 'unknown',
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Get file path from URL
            $filePath = str_replace(asset('storage/'), '', $attachment['url']);
            $fullPath = storage_path('app/public/' . $filePath);
            
            // Check if file exists
            if (file_exists($fullPath)) {
                // Return file download response
                return response()->download($fullPath, $attachment['name'] ?? 'download');
            } else {
                // Fallback to redirect if file doesn't exist locally
                return redirect($attachment['url']);
            }
            
        } catch (\Exception $e) {
            \Log::error('Lesson attachment download error: ' . $e->getMessage());
            abort(404, 'File not found');
        }
    }
    
    /**
     * Validate file URL for security
     */
    private function isValidFileUrl($url): bool
    {
        // Check if URL is from allowed domains/paths
        $allowedPaths = ['/storage/', '/uploads/', '/lessons/'];
        $allowedDomains = [config('app.url'), 'https://cdn.sundaylearn.com'];
        
        // Check for local file paths
        foreach ($allowedPaths as $path) {
            if (str_contains($url, $path)) {
                return true;
            }
        }
        
        // Check for allowed external domains
        foreach ($allowedDomains as $domain) {
            if (str_starts_with($url, $domain)) {
                return true;
            }
        }
        
        return false;
    }
}
