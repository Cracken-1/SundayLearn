<?php

namespace App\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        // Get all published lessons from database
        $lessons = Lesson::published()
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function($lesson) {
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
     * Priority: video thumbnail > audio thumbnail > image attachment > thumbnail field > default
     */
    private function getBestThumbnail($lesson)
    {
        // Check for video/audio URLs first
        if (!empty($lesson->video_url)) {
            return $this->getVideoThumbnail($lesson->video_url);
        }
        
        if (!empty($lesson->audio_url)) {
            return 'audio-placeholder.jpg'; // Could be a custom audio icon
        }
        
        // Check attachments for images
        if (!empty($lesson->attachments)) {
            foreach ($lesson->attachments as $attachment) {
                $type = strtolower($attachment['type'] ?? '');
                if (in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    return $attachment['url'] ?? 'default.jpg';
                }
            }
            
            // Check for video attachments
            foreach ($lesson->attachments as $attachment) {
                $type = strtolower($attachment['type'] ?? '');
                if (in_array($type, ['mp4', 'avi', 'mov', 'wmv'])) {
                    return 'video-placeholder.jpg';
                }
            }
        }
        
        // Fall back to thumbnail field or default
        return $lesson->thumbnail ?? 'default.jpg';
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
        
        foreach ($attachments as $attachment) {
            $type = strtolower($attachment['type'] ?? '');
            if (in_array($type, $types)) {
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
}
