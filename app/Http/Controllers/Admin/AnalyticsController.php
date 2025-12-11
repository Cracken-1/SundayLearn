<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        try {
            // Fetch real analytics data with error handling
            $totalPageViews = \App\Models\Analytics::eventType('page_view')->count() ?? 0;
            $totalLessonViews = \App\Models\Analytics::eventType('lesson_view')->count() ?? 0;
            $totalResourceDownloads = \App\Models\Analytics::eventType('resource_download')->count() ?? 0;
            $totalSearches = \App\Models\Analytics::eventType('search')->count() ?? 0;
            
            // Today's stats
            $todayPageViews = \App\Models\Analytics::eventType('page_view')->today()->count();
            $todayLessonViews = \App\Models\Analytics::eventType('lesson_view')->today()->count();
            $todayDownloads = \App\Models\Analytics::eventType('resource_download')->today()->count();
            
            // This week's stats
            $weekPageViews = \App\Models\Analytics::eventType('page_view')->thisWeek()->count();
            $weekLessonViews = \App\Models\Analytics::eventType('lesson_view')->thisWeek()->count();
            $weekDownloads = \App\Models\Analytics::eventType('resource_download')->thisWeek()->count();
            
            // This month's stats
            $monthPageViews = \App\Models\Analytics::eventType('page_view')->thisMonth()->count();
            $monthLessonViews = \App\Models\Analytics::eventType('lesson_view')->thisMonth()->count();
            $monthDownloads = \App\Models\Analytics::eventType('resource_download')->thisMonth()->count();
            
            $analyticsStats = [
                'total_page_views' => $totalPageViews,
                'total_lesson_views' => $totalLessonViews,
                'total_downloads' => $totalResourceDownloads,
                'total_searches' => $totalSearches,
                'today_page_views' => $todayPageViews,
                'today_lesson_views' => $todayLessonViews,
                'today_downloads' => $todayDownloads,
                'week_page_views' => $weekPageViews,
                'week_lesson_views' => $weekLessonViews,
                'week_downloads' => $weekDownloads,
                'month_page_views' => $monthPageViews,
                'month_lesson_views' => $monthLessonViews,
                'month_downloads' => $monthDownloads,
            ];
            
            // Fetch real content statistics with error handling
            $totalLessons = \App\Models\Lesson::count() ?? 0;
            $publishedLessons = method_exists(\App\Models\Lesson::class, 'published') ? 
                \App\Models\Lesson::published()->count() : 
                \App\Models\Lesson::whereNotNull('published_at')->count();
            $lessonsWithVideo = \App\Models\Lesson::whereNotNull('video_url')->count() ?? 0;
            $lessonsWithAudio = \App\Models\Lesson::whereNotNull('audio_url')->count() ?? 0;
            $totalBlogs = \App\Models\BlogPost::count() ?? 0;
            $publishedBlogs = method_exists(\App\Models\BlogPost::class, 'published') ? 
                \App\Models\BlogPost::published()->count() : 
                \App\Models\BlogPost::whereNotNull('published_at')->count();
            $totalEvents = class_exists(\App\Models\Event::class) ? \App\Models\Event::count() : 0;
            $totalTeachingTips = class_exists(\App\Models\TeachingTip::class) ? \App\Models\TeachingTip::count() : 0;
            $totalResources = class_exists(\App\Models\Resource::class) ? \App\Models\Resource::count() : 0;
            
            $contentStats = [
                'total_lessons' => $totalLessons,
                'published_lessons' => $publishedLessons,
                'lessons_with_video' => $lessonsWithVideo,
                'lessons_with_audio' => $lessonsWithAudio,
                'total_blogs' => $totalBlogs,
                'published_blogs' => $publishedBlogs,
                'total_events' => $totalEvents,
                'total_teaching_tips' => $totalTeachingTips,
                'total_resources' => $totalResources,
            ];

            // Age Group Distribution from database
            $ageGroupStats = \App\Models\Lesson::select('age_group', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->whereNotNull('age_group')
                ->groupBy('age_group')
                ->pluck('count', 'age_group')
                ->toArray();

            // Monthly Content Creation (Last 12 months) from database
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthKey = $date->format('M Y');
                $startOfMonth = $date->startOfMonth()->toDateString();
                $endOfMonth = $date->endOfMonth()->toDateString();
                
                $monthlyData[$monthKey] = [
                    'lessons' => \App\Models\Lesson::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                    'blogs' => \App\Models\BlogPost::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count(),
                ];
            }

            // Telegram Import Statistics from database with error handling
            $telegramStats = [
                'total_imports' => class_exists(\App\Models\TelegramRawImport::class) ? \App\Models\TelegramRawImport::count() : 0,
                'pending' => class_exists(\App\Models\TelegramRawImport::class) ? \App\Models\TelegramRawImport::where('processing_status', 'pending')->count() : 0,
                'processing' => class_exists(\App\Models\TelegramRawImport::class) ? \App\Models\TelegramRawImport::where('processing_status', 'processing')->count() : 0,
                'completed' => class_exists(\App\Models\TelegramRawImport::class) ? \App\Models\TelegramRawImport::where('processing_status', 'completed')->count() : 0,
                'failed' => class_exists(\App\Models\TelegramRawImport::class) ? \App\Models\TelegramRawImport::where('processing_status', 'failed')->count() : 0,
            ];

            // Media Type Distribution from database with error handling
            $mediaTypeStats = [
                'photo' => class_exists(\App\Models\TelegramRawImport::class) ? \App\Models\TelegramRawImport::where('media_type', 'photo')->count() : 0,
                'video' => class_exists(\App\Models\TelegramRawImport::class) ? \App\Models\TelegramRawImport::where('media_type', 'video')->count() : 0,
                'document' => class_exists(\App\Models\TelegramRawImport::class) ? \App\Models\TelegramRawImport::where('media_type', 'document')->count() : 0,
            ];

            // Recent Activity from database
            $recentLessons = \App\Models\Lesson::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($lesson) {
                    return (object)[
                        'id' => $lesson->id,
                        'title' => $lesson->title,
                        'type' => 'lesson',
                        'created_at' => $lesson->created_at,
                        'views' => $lesson->views_count ?? 0,
                    ];
                });

            $recentBlogs = \App\Models\BlogPost::orderBy('created_at', 'desc')
                ->limit(5)
                ->get()
                ->map(function($blog) {
                    return (object)[
                        'id' => $blog->id,
                        'title' => $blog->title,
                        'type' => 'blog',
                        'created_at' => $blog->created_at,
                        'views' => $blog->views_count ?? 0,
                    ];
                });

            $recentActivity = $recentLessons->merge($recentBlogs)
                ->sortByDesc('created_at')
                ->take(10);
                
            // Popular pages
            $popularPages = \App\Models\Analytics::getPopularPages(10);
            
            // Popular searches
            $popularSearches = \App\Models\Analytics::getPopularSearches(10);
            
            // Device breakdown
            $deviceStats = \App\Models\Analytics::getDeviceBreakdown();
            
            // Browser breakdown
            $browserStats = \App\Models\Analytics::getBrowserBreakdown();
            
            // Country breakdown
            $countryStats = \App\Models\Analytics::getCountryBreakdown(10);
            
            // Daily analytics for the last 30 days
            $dailyAnalytics = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateKey = $date->format('M j');
                $startOfDay = $date->startOfDay();
                $endOfDay = $date->endOfDay();
                
                $dailyAnalytics[$dateKey] = [
                    'page_views' => \App\Models\Analytics::eventType('page_view')
                        ->whereBetween('created_at', [$startOfDay, $endOfDay])
                        ->count(),
                    'lesson_views' => \App\Models\Analytics::eventType('lesson_view')
                        ->whereBetween('created_at', [$startOfDay, $endOfDay])
                        ->count(),
                    'downloads' => \App\Models\Analytics::eventType('resource_download')
                        ->whereBetween('created_at', [$startOfDay, $endOfDay])
                        ->count(),
                ];
            }

        } catch (\Exception $e) {
            // Fallback to static data if database query fails
            $analyticsStats = [
                'total_page_views' => 0,
                'total_lesson_views' => 0,
                'total_downloads' => 0,
                'total_searches' => 0,
                'today_page_views' => 0,
                'today_lesson_views' => 0,
                'today_downloads' => 0,
                'week_page_views' => 0,
                'week_lesson_views' => 0,
                'week_downloads' => 0,
                'month_page_views' => 0,
                'month_lesson_views' => 0,
                'month_downloads' => 0,
            ];
            
            $contentStats = [
                'total_lessons' => 0,
                'published_lessons' => 0,
                'lessons_with_video' => 0,
                'lessons_with_audio' => 0,
                'total_blogs' => 0,
                'published_blogs' => 0,
                'total_events' => 0,
                'total_teaching_tips' => 0,
                'total_resources' => 0,
            ];

            $ageGroupStats = [];
            
            $monthlyData = [];
            for ($i = 11; $i >= 0; $i--) {
                $date = Carbon::now()->subMonths($i);
                $monthKey = $date->format('M Y');
                $monthlyData[$monthKey] = ['lessons' => 0, 'blogs' => 0];
            }

            $telegramStats = [
                'total_imports' => 0,
                'pending' => 0,
                'processing' => 0,
                'completed' => 0,
                'failed' => 0,
            ];

            $mediaTypeStats = [
                'photo' => 0,
                'video' => 0,
                'document' => 0,
            ];

            $recentActivity = collect([]);
            $popularPages = collect([]);
            $popularSearches = collect([]);
            $deviceStats = collect([]);
            $browserStats = collect([]);
            $countryStats = collect([]);
            
            $dailyAnalytics = [];
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $dateKey = $date->format('M j');
                $dailyAnalytics[$dateKey] = [
                    'page_views' => 0,
                    'lesson_views' => 0,
                    'downloads' => 0,
                ];
            }
        }

        return view('admin.analytics', compact(
            'analyticsStats',
            'contentStats',
            'ageGroupStats',
            'monthlyData',
            'telegramStats',
            'mediaTypeStats',
            'recentActivity',
            'popularPages',
            'popularSearches',
            'deviceStats',
            'browserStats',
            'countryStats',
            'dailyAnalytics'
        ));
    }
}