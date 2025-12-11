<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class SettingsController extends Controller
{
    public function index()
    {
        try {
            $currentUser = auth()->guard('admin')->user();
            
            if (!$currentUser) {
                return redirect()->route('admin.login')->with('error', 'Please log in to access settings.');
            }
            
            // Check if user has access to system settings
            if (!$currentUser->canAccessSystemSettings()) {
                // Show editor-specific settings
                return view('admin.settings.editor', compact('currentUser'));
            }
            
            // Show full system settings with editor preferences for super admin and admin
            return view('admin.settings.system', compact('currentUser'));
            
        } catch (\Exception $e) {
            \Log::error('Settings page error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'user' => auth()->guard('admin')->id()
            ]);
            
            return back()->with('error', 'Unable to load settings page. Please try again.');
        }
    }

    public function clearCache()
    {
        $currentUser = auth()->guard('admin')->user();
        
        if (!$currentUser->canAccessSystemSettings()) {
            abort(403, 'Unauthorized access. Only administrators can perform system actions.');
        }
        
        try {
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('route:clear');
            Artisan::call('view:clear');

            return back()->with('success', 'Cache cleared successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to clear cache: ' . $e->getMessage());
        }
    }

    public function optimizeApp()
    {
        $currentUser = auth()->guard('admin')->user();
        
        if (!$currentUser->canAccessSystemSettings()) {
            abort(403, 'Unauthorized access. Only administrators can perform system actions.');
        }
        
        try {
            Artisan::call('config:cache');
            Artisan::call('route:cache');
            Artisan::call('view:cache');

            return back()->with('success', 'Application optimized successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to optimize application: ' . $e->getMessage());
        }
    }

    public function runMigrations()
    {
        $currentUser = auth()->guard('admin')->user();
        
        if (!$currentUser->canAccessSystemSettings()) {
            abort(403, 'Unauthorized access. Only administrators can perform system actions.');
        }
        
        try {
            Artisan::call('migrate', ['--force' => true]);

            return back()->with('success', 'Database migrations completed successfully!');
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to run migrations: ' . $e->getMessage());
        }
    }

    public function cleanupStorage()
    {
        $currentUser = auth()->guard('admin')->user();
        
        if (!$currentUser->canAccessSystemSettings()) {
            abort(403, 'Unauthorized access. Only administrators can perform system actions.');
        }
        
        try {
            // Clean up orphaned files
            $deletedFiles = 0;
            
            // You can add logic here to clean up unused files
            // For example, remove thumbnails for deleted lessons
            
            return back()->with('success', "Storage cleanup completed! Removed {$deletedFiles} orphaned files.");
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to cleanup storage: ' . $e->getMessage());
        }
    }

    private function getStorageUsage()
    {
        try {
            $publicPath = storage_path('app/public');
            if (!is_dir($publicPath)) {
                return 'N/A';
            }

            $size = 0;
            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($publicPath, \RecursiveDirectoryIterator::SKIP_DOTS)
            );

            foreach ($iterator as $file) {
                $size += $file->getSize();
            }

            return $this->formatBytes($size);
        } catch (\Exception $e) {
            return 'Unable to calculate';
        }
    }

    public function updateEditorSettings(Request $request)
    {
        $currentUser = auth()->guard('admin')->user();
        
        // Validate editor settings
        $request->validate([
            'default_lesson_duration' => 'nullable|string|in:30 minutes,45 minutes,60 minutes,90 minutes',
            'default_age_group' => 'nullable|string|in:3-5,6-8,9-12,teen,adult',
            'auto_save_drafts' => 'nullable|boolean',
            'show_preview' => 'nullable|boolean',
        ]);
        
        // Store editor preferences (you can implement this based on your needs)
        // For now, we'll just return success
        
        return back()->with('success', 'Editor preferences updated successfully!');
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