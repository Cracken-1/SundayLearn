<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
// Models will be loaded conditionally to avoid errors if they don't exist
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdminController extends Controller
{
    public function dashboard()
    {
        $currentUser = Auth::guard('admin')->user();
        
        // Try to get real data from database, fallback to static data
        try {
            $totalLessons = \App\Models\Lesson::count();
            $totalBlogs = \App\Models\BlogPost::count();
            $telegramTotal = \App\Models\TelegramRawImport::count();
            
            $stats = [
                // Content stats
                'total_lessons' => $totalLessons,
                'published_lessons' => \App\Models\Lesson::published()->count(),
                'draft_lessons' => \App\Models\Lesson::where('status', 'draft')->count(),
                'featured_lessons' => \App\Models\Lesson::featured()->count(),
                
                'total_blogs' => $totalBlogs,
                'published_blogs' => \App\Models\BlogPost::published()->count(),
                'draft_blogs' => \App\Models\BlogPost::where('status', 'draft')->count(),
                
                // Telegram stats
                'telegram_imports' => [
                    'total' => $telegramTotal,
                    'pending' => \App\Models\TelegramRawImport::where('processing_status', 'pending')->count(),
                    'processing' => \App\Models\TelegramRawImport::where('processing_status', 'processing')->count(),
                    'completed' => \App\Models\TelegramRawImport::where('processing_status', 'completed')->count(),
                    'failed' => \App\Models\TelegramRawImport::where('processing_status', 'failed')->count(),
                ],
                
                // Total content
                'total_content' => $totalLessons + $totalBlogs,
                
                // Engagement stats
                'total_views' => \App\Models\Lesson::sum('views_count') ?: 0,
                'most_viewed_lesson' => \App\Models\Lesson::orderBy('views_count', 'desc')->first(),
                
                // Recent content
                'recent_lessons' => \App\Models\Lesson::orderBy('created_at', 'desc')->limit(5)->get(),
                'recent_blogs' => \App\Models\BlogPost::orderBy('created_at', 'desc')->limit(5)->get(),
                'recent_imports' => \App\Models\TelegramRawImport::orderBy('created_at', 'desc')->limit(5)->get(),
                'recent_activity' => \App\Models\TelegramRawImport::orderBy('created_at', 'desc')->limit(10)->get(),
                
                // Content by category
                'lessons_by_category' => \App\Models\Lesson::select('category', DB::raw('count(*) as count'))
                    ->whereNotNull('category')
                    ->groupBy('category')
                    ->get(),
                
                // Content by difficulty
                'lessons_by_difficulty' => \App\Models\Lesson::select('difficulty', DB::raw('count(*) as count'))
                    ->whereNotNull('difficulty')
                    ->groupBy('difficulty')
                    ->get(),
                
                // Weekly stats
                'lessons_this_week' => \App\Models\Lesson::where('created_at', '>=', now()->startOfWeek())->count(),
                'blogs_this_week' => \App\Models\BlogPost::where('created_at', '>=', now()->startOfWeek())->count(),
                
                // New content stats
                'total_events' => \App\Models\Event::count(),
                'upcoming_events' => \App\Models\Event::upcoming()->count(),
                'featured_events' => \App\Models\Event::featured()->count(),
                
                'total_teaching_tips' => \App\Models\TeachingTip::count(),
                'active_teaching_tips' => \App\Models\TeachingTip::active()->count(),
                
                'total_resources' => \App\Models\Resource::count(),
                'featured_resources' => \App\Models\Resource::where('is_featured', true)->count(),
                'total_downloads' => \App\Models\Resource::sum('downloads_count') ?: 0,
                
                'total_newsletters' => \App\Models\Newsletter::count(),
                'subscribed_newsletters' => \App\Models\Newsletter::subscribed()->count(),
            ];
        } catch (\Exception $e) {
            // Fallback to static data if database query fails
            $stats = [
                'total_lessons' => 0,
                'published_lessons' => 0,
                'draft_lessons' => 0,
                'featured_lessons' => 0,
                'total_blogs' => 0,
                'published_blogs' => 0,
                'draft_blogs' => 0,
                'telegram_imports' => [
                    'total' => 0,
                    'pending' => 0,
                    'processing' => 0,
                    'completed' => 0,
                    'failed' => 0,
                ],
                'total_content' => 0,
                'total_views' => 0,
                'most_viewed_lesson' => null,
                'recent_lessons' => collect([]),
                'recent_blogs' => collect([]),
                'recent_imports' => collect([]),
                'recent_activity' => collect([]),
                'lessons_by_category' => collect([]),
                'lessons_by_difficulty' => collect([]),
                'lessons_this_week' => 0,
                'blogs_this_week' => 0,
                'total_events' => 0,
                'upcoming_events' => 0,
                'featured_events' => 0,
                'total_teaching_tips' => 0,
                'active_teaching_tips' => 0,
                'total_resources' => 0,
                'featured_resources' => 0,
                'total_downloads' => 0,
                'total_newsletters' => 0,
                'subscribed_newsletters' => 0,
            ];
        }

        // System status (only for admin and super_admin)
        $systemStatus = null;
        if ($currentUser->canAccessSystemSettings()) {
            $systemStatus = $this->getSystemStatus();
        }

        return view('admin.dashboard', compact('stats', 'systemStatus', 'currentUser'));
    }

    private function checkTablesExist()
    {
        $tables = ['lessons', 'blog_posts', 'telegram_raw_imports'];
        $exists = [];
        
        foreach ($tables as $table) {
            try {
                DB::table($table)->limit(1)->get();
                $exists[$table] = true;
            } catch (\Exception $e) {
                $exists[$table] = false;
            }
        }
        
        return $exists;
    }

    private function getSystemStatus()
    {
        $status = [
            'database' => $this->checkDatabaseConnection(),
            'telegram_bot' => $this->checkTelegramBot(),
            'telegram_channel' => $this->checkTelegramChannel(),
            'storage' => $this->checkStorageStatus(),
            'cache' => $this->checkCacheStatus(),
            'php_version' => $this->checkPhpVersion(),
        ];

        return $status;
    }

    private function checkDatabaseConnection()
    {
        try {
            DB::connection()->getPdo();
            return [
                'status' => 'online',
                'message' => 'Database connected successfully',
                'details' => 'Connection: ' . config('database.default')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Database connection failed',
                'details' => $e->getMessage()
            ];
        }
    }

    private function checkTelegramBot()
    {
        $botToken = config('telegram.bot_token');
        
        if (!$botToken) {
            return [
                'status' => 'warning',
                'message' => 'Bot token not configured',
                'details' => 'Set TELEGRAM_BOT_TOKEN in .env'
            ];
        }

        // Skip HTTP requests for now to avoid timeout issues
        return [
            'status' => 'warning',
            'message' => 'Bot status check disabled',
            'details' => 'HTTP requests disabled for stability'
        ];
    }

    private function checkTelegramChannel()
    {
        $botToken = config('telegram.bot_token');
        $channelId = config('telegram.channel_id');
        
        if (!$botToken || !$channelId) {
            return [
                'status' => 'warning',
                'message' => 'Channel not configured',
                'details' => 'Set TELEGRAM_CHANNEL_ID in .env'
            ];
        }

        // Skip HTTP requests for now to avoid timeout issues
        return [
            'status' => 'warning',
            'message' => 'Channel status check disabled',
            'details' => 'HTTP requests disabled for stability'
        ];
    }

    private function checkStorageStatus()
    {
        try {
            $disk = Storage::disk();
            $testFile = 'admin-test-' . time() . '.txt';
            
            // Test write
            $disk->put($testFile, 'test');
            
            // Test read
            $content = $disk->get($testFile);
            
            // Cleanup
            $disk->delete($testFile);
            
            return [
                'status' => 'online',
                'message' => 'Storage working',
                'details' => 'Disk: ' . config('filesystems.default')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Storage error',
                'details' => $e->getMessage()
            ];
        }
    }

    private function checkCacheStatus()
    {
        try {
            $key = 'admin-cache-test-' . time();
            cache()->put($key, 'test', 60);
            $value = cache()->get($key);
            cache()->forget($key);
            
            return [
                'status' => 'online',
                'message' => 'Cache working',
                'details' => 'Driver: ' . config('cache.default')
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'message' => 'Cache error',
                'details' => $e->getMessage()
            ];
        }
    }

    private function checkPhpVersion()
    {
        $version = PHP_VERSION;
        $minVersion = '8.1.0';
        
        if (version_compare($version, $minVersion, '>=')) {
            return [
                'status' => 'online',
                'message' => 'PHP version OK',
                'details' => 'Version: ' . $version
            ];
        } else {
            return [
                'status' => 'warning',
                'message' => 'PHP version outdated',
                'details' => "Current: {$version}, Recommended: {$minVersion}+"
            ];
        }
    }
}