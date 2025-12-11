<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminActivity;
use App\Models\BlogPost;
use App\Models\Lesson;
use App\Models\TelegramRawImport;
use Illuminate\Support\Facades\DB;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $totalLessons = Lesson::count();
        $totalBlogs = BlogPost::count();
        $telegramTotal = TelegramRawImport::count();
        
        $stats = [
            // Content stats
            'total_lessons' => $totalLessons,
            'published_lessons' => Lesson::published()->count(),
            'draft_lessons' => Lesson::where('status', 'draft')->count(),
            'featured_lessons' => Lesson::featured()->count(),
            
            'total_blogs' => $totalBlogs,
            'published_blogs' => BlogPost::published()->count(),
            'draft_blogs' => BlogPost::where('status', 'draft')->count(),
            
            // Telegram stats
            'telegram_imports' => [
                'total' => $telegramTotal,
                'pending' => TelegramRawImport::where('processing_status', 'pending')->count(),
                'processing' => TelegramRawImport::where('processing_status', 'processing')->count(),
                'completed' => TelegramRawImport::where('processing_status', 'completed')->count(),
                'failed' => TelegramRawImport::where('processing_status', 'failed')->count(),
            ],
            
            // Total content
            'total_content' => $totalLessons + $totalBlogs,
            
            // Engagement stats
            'total_views' => Lesson::sum('views_count') ?: 0,
            'most_viewed_lesson' => Lesson::orderBy('views_count', 'desc')->first(),
            
            // Recent content
            'recent_lessons' => Lesson::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_blogs' => BlogPost::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_imports' => TelegramRawImport::orderBy('created_at', 'desc')->limit(5)->get(),
            'recent_activity' => TelegramRawImport::orderBy('created_at', 'desc')->limit(10)->get(),
            
            // Content by category
            'lessons_by_category' => Lesson::select('category', DB::raw('count(*) as count'))
                ->whereNotNull('category')
                ->groupBy('category')
                ->get(),
            
            // Content by difficulty
            'lessons_by_difficulty' => Lesson::select('difficulty', DB::raw('count(*) as count'))
                ->whereNotNull('difficulty')
                ->groupBy('difficulty')
                ->get(),
            
            // Weekly stats
            'lessons_this_week' => Lesson::where('created_at', '>=', now()->startOfWeek())->count(),
            'blogs_this_week' => BlogPost::where('created_at', '>=', now()->startOfWeek())->count(),
            
            // System status
            'systemStatus' => [
                'database' => ['status' => 'online', 'details' => 'MySQL Connected'],
                'storage' => ['status' => 'online', 'details' => 'Local Storage'],
                'cache' => ['status' => 'online', 'details' => 'File Cache'],
            ],
        ];

        return view('admin.dashboard', compact('stats'));
    }
}