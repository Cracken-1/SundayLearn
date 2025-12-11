<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Lesson;
use App\Models\BlogPost;

class HomeController extends Controller
{
    public function index()
    {
        // Get real lessons from database only
        $featuredLessons = Lesson::published()
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($lesson) {
                return [
                    'id' => $lesson->id,
                    'title' => $lesson->title,
                    'scripture' => $lesson->scripture ?? 'N/A',
                    'age_group' => $lesson->age_group ?? 'All Ages',
                    'duration' => $lesson->duration ? $lesson->duration . ' minutes' : '30 minutes',
                    'thumbnail' => $this->getBestThumbnail($lesson),
                    'has_video' => !empty($lesson->video_url) || $this->hasAttachmentType($lesson->attachments, ['mp4', 'avi', 'mov', 'wmv', 'webm']),
                    'has_audio' => !empty($lesson->audio_url) || $this->hasAttachmentType($lesson->attachments, ['mp3', 'wav', 'ogg', 'm4a']),
                ];
            })
            ->toArray();

        // Get real blog posts from database only
        $recentPosts = BlogPost::where('status', 'published')
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get()
            ->map(function($post) {
                return [
                    'id' => $post->id,
                    'title' => $post->title,
                    'excerpt' => $post->excerpt ?? substr(strip_tags($post->content), 0, 150) . '...',
                    'published_at' => $post->published_at->format('Y-m-d'),
                ];
            })
            ->toArray();

        // Get actual counts from database
        $totalLessons = Lesson::count();
        $totalBlogs = BlogPost::count();
        $publishedLessons = Lesson::published()->count();
        
        $stats = [
            ['icon' => 'book-open', 'number' => $publishedLessons, 'label' => 'Published Lessons'],
            ['icon' => 'users', 'number' => '1000+', 'label' => 'Teachers Served'],
            ['icon' => 'download', 'number' => '5000+', 'label' => 'Resources Downloaded'],
            ['icon' => 'heart', 'number' => '100%', 'label' => 'Free to Use'],
        ];

        // Get featured event for "This Week's Focus"
        $featuredEvent = \App\Models\Event::featured()
            ->where('event_date', '>=', now()->subDays(7))
            ->where('event_date', '<=', now()->addDays(30))
            ->orderBy('event_date', 'asc')
            ->first();

        return view('home', compact('featuredLessons', 'recentPosts', 'stats', 'totalLessons', 'totalBlogs', 'featuredEvent'));
    }
    
    /**
     * Get the best thumbnail for a lesson
     */
    private function getBestThumbnail($lesson)
    {
        if (!empty($lesson->video_url)) {
            return $this->getVideoThumbnail($lesson->video_url);
        }
        
        if (!empty($lesson->audio_url)) {
            return 'audio-placeholder.jpg';
        }
        
        if (!empty($lesson->attachments)) {
            foreach ($lesson->attachments as $attachment) {
                $type = strtolower($attachment['type'] ?? '');
                if (in_array($type, ['jpg', 'jpeg', 'png', 'gif'])) {
                    return $attachment['url'] ?? 'default.jpg';
                }
            }
        }
        
        return $lesson->thumbnail ?? 'default.jpg';
    }
    
    /**
     * Extract thumbnail from video URL
     */
    private function getVideoThumbnail($url)
    {
        if (preg_match('/youtube\.com\/watch\?v=([^&]+)/', $url, $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
        }
        if (preg_match('/youtu\.be\/([^?]+)/', $url, $matches)) {
            return "https://img.youtube.com/vi/{$matches[1]}/hqdefault.jpg";
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
}