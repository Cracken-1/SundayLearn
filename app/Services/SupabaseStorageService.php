<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    protected $url;
    protected $key;
    protected $bucket;

    public function __construct()
    {
        $this->url = config('services.supabase.url');
        $this->key = config('services.supabase.service_key');
        $this->bucket = config('services.supabase.bucket', 'lessons-images');
    }

    /**
     * Upload a file to Supabase storage
     */
    public function upload($file, $path, $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->key}",
                'Content-Type' => $file->getMimeType(),
            ])->attach(
                'file',
                file_get_contents($file->getRealPath()),
                $file->getClientOriginalName()
            )->post("{$this->url}/storage/v1/object/{$bucket}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path, $bucket),
                    'data' => $response->json()
                ];
            }

            Log::error('Supabase upload failed', [
                'status' => $response->status(),
                'body' => $response->body()
            ]);

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Upload failed'
            ];
        } catch (\Exception $e) {
            Log::error('Supabase upload exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    /**
     * Delete a file from Supabase storage
     */
    public function delete($path, $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->key}",
            ])->delete("{$this->url}/storage/v1/object/{$bucket}/{$path}");

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('Supabase delete exception', [
                'message' => $e->getMessage()
            ]);
            return false;
        }
    }

    /**
     * Get public URL for a file
     */
    public function getPublicUrl($path, $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        return "{$this->url}/storage/v1/object/public/{$bucket}/{$path}";
    }

    /**
     * List files in a bucket
     */
    public function list($prefix = '', $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        try {
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->key}",
            ])->post("{$this->url}/storage/v1/object/list/{$bucket}", [
                'prefix' => $prefix,
                'limit' => 100,
                'offset' => 0
            ]);

            if ($response->successful()) {
                return $response->json();
            }

            return [];
        } catch (\Exception $e) {
            Log::error('Supabase list exception', [
                'message' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Get storage statistics
     */
    public function getStorageStats()
    {
        try {
            $buckets = ['lessons-images', 'lessons-attachments', 'blog-images', 'telegram-media'];
            $stats = [];

            foreach ($buckets as $bucket) {
                $files = $this->list('', $bucket);
                $totalSize = 0;
                $count = 0;

                foreach ($files as $file) {
                    if (isset($file['metadata']['size'])) {
                        $totalSize += $file['metadata']['size'];
                        $count++;
                    }
                }

                $stats[$bucket] = [
                    'count' => $count,
                    'size' => $totalSize,
                    'size_formatted' => $this->formatBytes($totalSize)
                ];
            }

            return $stats;
        } catch (\Exception $e) {
            Log::error('Supabase stats exception', [
                'message' => $e->getMessage()
            ]);
            return [];
        }
    }

    /**
     * Format bytes to human readable
     */
    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }

    /**
     * Upload from URL (for Telegram files)
     */
    public function uploadFromUrl($url, $path, $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        try {
            $fileContent = file_get_contents($url);
            
            $response = Http::withHeaders([
                'Authorization' => "Bearer {$this->key}",
                'Content-Type' => 'application/octet-stream',
            ])->withBody($fileContent, 'application/octet-stream')
              ->post("{$this->url}/storage/v1/object/{$bucket}/{$path}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path, $bucket)
                ];
            }

            return [
                'success' => false,
                'error' => $response->json()['message'] ?? 'Upload failed'
            ];
        } catch (\Exception $e) {
            Log::error('Supabase upload from URL exception', [
                'message' => $e->getMessage()
            ]);

            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
