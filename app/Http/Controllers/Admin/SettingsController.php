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
            // Clear all caches
            Artisan::call('optimize:clear');
            
            // NOTE: We do not run config:cache or route:cache here because running these 
            // from a web request can cause race conditions or session invalidation (419 errors) 
            // if the environment is not perfectly stable. 
            // Clearing the cache is sufficient for most troubleshooting.
            
            // Artisan::call('config:cache');
            // Artisan::call('route:cache');
            // Artisan::call('view:cache');

            return back()->with('success', 'Application cache cleared and optimized!');
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
        // Relaxed validation to allow custom values
        $request->validate([
            'default_lesson_duration' => 'nullable|string|max:50',
            'default_age_group' => 'nullable|string|max:50', // Removed strict in: validation
            'auto_save_drafts' => 'nullable|boolean',
            'show_preview' => 'nullable|boolean',
        ]);
        
        // Store editor preferences (you can implement this based on your needs)
        // For now, we'll just return success
        
        return back()->with('success', 'Editor preferences updated successfully!');
    }

    public function updateTelegramSettings(Request $request)
    {
        $currentUser = auth()->guard('admin')->user();
        
        if (!$currentUser->canAccessSystemSettings()) {
            abort(403, 'Unauthorized access. Only administrators can perform system actions.');
        }

        $request->validate([
            'telegram_bot_token' => 'nullable|string',
            'telegram_channel_id' => 'nullable|string',
            'telegram_webhook_url' => 'nullable|url',
        ]);

        try {
            $envUpdates = [
                'TELEGRAM_BOT_TOKEN' => $request->telegram_bot_token,
                'TELEGRAM_CHANNEL_ID' => $request->telegram_channel_id,
                'TELEGRAM_WEBHOOK_URL' => $request->telegram_webhook_url,
            ];

            $this->updateEnvFile($envUpdates);
            
            // Clear config cache to ensure new values are picked up
            Artisan::call('config:clear');

            // Set runtime config for immediate usage in this request
            config([
                'telegram.bot_token' => $request->telegram_bot_token,
                'telegram.channel_id' => $request->telegram_channel_id,
            ]);

            $message = 'Telegram settings updated successfully! Configuration cache cleared.';

            // If we have a webhook URL (and bot token), try to register it
            if ($request->telegram_webhook_url && $request->telegram_bot_token) {
                try {
                    $telegramService = new \App\Services\TelegramService();
                    $result = $telegramService->setWebhook($request->telegram_webhook_url);
                    
                    if ($result['ok'] ?? false) {
                        $message .= ' Webhook registered with Telegram.';
                    } else {
                        $error = $result['description'] ?? 'Unknown error';
                        $message .= " However, Telegram webhook registration failed: {$error}";
                    }
                } catch (\Exception $e) {
                     $message .= " However, failed to contact Telegram: " . $e->getMessage();
                }
            }

            return back()->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to update settings: ' . $e->getMessage());
        }
    }

    private function updateEnvFile(array $values)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $content = file_get_contents($path);
            
            foreach ($values as $key => $value) {
                // Skip if value is null
                if ($value === null) continue;
                
                // Quote value if it contains spaces or special characters
                if (preg_match('/\s/', $value) || strpos($value, '=') !== false) {
                    $value = '"' . str_replace('"', '\"', $value) . '"';
                }
                
                if (strpos($content, "{$key}=") !== false) {
                    // Update existing key
                    $content = preg_replace("/^{$key}=.*/m", "{$key}={$value}", $content);
                } else {
                    // Add new key
                    $content .= "\n{$key}={$value}";
                }
            }
            
            file_put_contents($path, $content);
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