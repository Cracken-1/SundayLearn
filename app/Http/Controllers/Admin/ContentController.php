<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContentController extends Controller
{
    public function index()
    {
        try {
            // Fetch actual data from database
            $totalLessons = \App\Models\Lesson::count();
            $publishedLessons = \App\Models\Lesson::published()->count();
            $draftLessons = \App\Models\Lesson::where('status', 'draft')->count();
            
            $totalBlogs = \App\Models\BlogPost::count();
            $publishedBlogs = \App\Models\BlogPost::published()->count();
            $draftBlogs = \App\Models\BlogPost::where('status', 'draft')->count();
            
            $telegramImports = \App\Models\TelegramRawImport::count();
            $pendingImports = \App\Models\TelegramRawImport::where('processing_status', 'pending')->count();
            
            $stats = [
                'total_lessons' => $totalLessons,
                'published_lessons' => $publishedLessons,
                'draft_lessons' => $draftLessons,
                'total_blogs' => $totalBlogs,
                'published_blogs' => $publishedBlogs,
                'draft_blogs' => $draftBlogs,
                'telegram_imports' => $telegramImports,
                'pending_imports' => $pendingImports,
            ];

            // Fetch recent content from database
            $recentLessons = \App\Models\Lesson::orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $recentBlogs = \App\Models\BlogPost::orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            $recentImports = \App\Models\TelegramRawImport::orderBy('created_at', 'desc')
                ->limit(10)
                ->get();

            // Get age group distribution
            $ageGroupStats = \App\Models\Lesson::select('age_group', \Illuminate\Support\Facades\DB::raw('count(*) as count'))
                ->whereNotNull('age_group')
                ->groupBy('age_group')
                ->pluck('count', 'age_group')
                ->toArray();

            // Get storage stats
            $storageStats = $this->getStorageStats();

        } catch (\Exception $e) {
            // Fallback to static data if database query fails
            $stats = [
                'total_lessons' => 0,
                'published_lessons' => 0,
                'draft_lessons' => 0,
                'total_blogs' => 0,
                'published_blogs' => 0,
                'draft_blogs' => 0,
                'telegram_imports' => 0,
                'pending_imports' => 0,
            ];

            $recentLessons = collect([]);
            $recentBlogs = collect([]);
            $recentImports = collect([]);
            $ageGroupStats = [];
            $storageStats = ['total' => 'N/A', 'images' => 'N/A', 'documents' => 'N/A'];
        }

        return view('admin.content.index', compact(
            'stats', 'recentLessons', 'recentBlogs', 'recentImports', 
            'ageGroupStats', 'storageStats'
        ));
    }

    public function bulkActions(Request $request)
    {
        $request->validate([
            'action' => 'required|in:publish,unpublish,delete',
            'type' => 'required|in:lessons,blogs',
            'ids' => 'required|array',
            'ids.*' => 'integer',
        ]);

        try {
            $model = $request->type === 'lessons' ? \App\Models\Lesson::class : \App\Models\BlogPost::class;
            $items = $model::whereIn('id', $request->ids);

            switch ($request->action) {
                case 'publish':
                    $items->update(['status' => 'published', 'published_at' => now()]);
                    $message = ucfirst($request->type) . ' published successfully!';
                    break;
                    
                case 'unpublish':
                    $items->update(['status' => 'draft', 'published_at' => null]);
                    $message = ucfirst($request->type) . ' unpublished successfully!';
                    break;
                    
                case 'delete':
                    $items->delete();
                    $message = ucfirst($request->type) . ' deleted successfully!';
                    break;
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Bulk action failed: ' . $e->getMessage());
        }
    }

    public function cleanup()
    {
        try {
            $deletedFiles = 0;
            $deletedRecords = 0;

            // Clean up old failed telegram imports (using actual column names)
            try {
                // Clean up failed imports older than 30 days that haven't created any content
                $orphanedImports = \App\Models\TelegramRawImport::where('processing_status', 'failed')
                    ->where('created_at', '<', now()->subDays(30))
                    ->whereNull('created_lesson_id')
                    ->whereNull('created_blog_id');
                
                $count = $orphanedImports->count();
                if ($count > 0) {
                    $orphanedImports->delete();
                    $deletedRecords += $count;
                }
            } catch (\Exception $e) {
                // If telegram imports cleanup fails, continue with other cleanup
            }

            // Clean up old draft lessons (older than 90 days)
            try {
                $oldDrafts = \App\Models\Lesson::where('status', 'draft')
                    ->where('created_at', '<', now()->subDays(90))
                    ->whereNull('published_at');
                
                $count = $oldDrafts->count();
                if ($count > 0) {
                    $oldDrafts->delete();
                    $deletedRecords += $count;
                }
            } catch (\Exception $e) {
                // Continue if this fails
            }

            // Clean up old draft blogs (older than 90 days)
            try {
                $oldBlogDrafts = \App\Models\BlogPost::where('status', 'draft')
                    ->where('created_at', '<', now()->subDays(90))
                    ->whereNull('published_at');
                
                $count = $oldBlogDrafts->count();
                if ($count > 0) {
                    $oldBlogDrafts->delete();
                    $deletedRecords += $count;
                }
            } catch (\Exception $e) {
                // Continue if this fails
            }

            // Clean up temporary files
            $tempPath = storage_path('app/temp');
            if (is_dir($tempPath)) {
                $files = glob($tempPath . '/*');
                foreach ($files as $file) {
                    if (is_file($file) && filemtime($file) < strtotime('-7 days')) {
                        unlink($file);
                        $deletedFiles++;
                    }
                }
            }

            $message = "Cleanup completed! ";
            if ($deletedRecords > 0) {
                $message .= "Removed {$deletedRecords} old record(s). ";
            }
            if ($deletedFiles > 0) {
                $message .= "Removed {$deletedFiles} temporary file(s).";
            }
            if ($deletedRecords === 0 && $deletedFiles === 0) {
                $message .= "No items needed cleanup.";
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Cleanup failed: ' . $e->getMessage());
        }
    }

    private function getStorageStats()
    {
        try {
            $publicPath = storage_path('app/public');
            if (!is_dir($publicPath)) {
                return ['total' => 'N/A', 'images' => 'N/A', 'documents' => 'N/A'];
            }

            $totalSize = 0;
            $imageSize = 0;
            $documentSize = 0;

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($publicPath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                $size = $file->getSize();
                $totalSize += $size;

                $extension = strtolower($file->getExtension());
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) {
                    $imageSize += $size;
                } elseif (in_array($extension, ['pdf', 'doc', 'docx', 'txt'])) {
                    $documentSize += $size;
                }
            }

            return [
                'total' => $this->formatBytes($totalSize),
                'images' => $this->formatBytes($imageSize),
                'documents' => $this->formatBytes($documentSize),
            ];
        } catch (\Exception $e) {
            return ['total' => 'Error', 'images' => 'Error', 'documents' => 'Error'];
        }
    }

    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $size > 1024 && $i < count($units) - 1; $i++) {
            $size /= 1024;
        }
        
        return round($size, $precision) . ' ' . $units[$i];
    }
}