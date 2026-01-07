<?php

namespace App\Services;

use Supabase\CreateClient;
use Illuminate\Support\Facades\Log;

class SupabaseStorageService
{
    protected $client;
    protected $bucket;

    public function __construct()
    {
        // Extract reference ID from URL (e.g., https://abc123.supabase.co -> abc123)
        $url = \config('supabase.url');
        $referenceId = parse_url($url, PHP_URL_HOST);
        $referenceId = explode('.', $referenceId)[0];
        
        $this->client = new CreateClient(
            \config('supabase.service_key'),
            $referenceId
        );
        $this->bucket = \config('supabase.buckets.lessons_images', 'lessons-images');
    }

    /**
     * Upload a file to Supabase storage
     */
    public function upload($file, $path, $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        try {
            $fileContent = file_get_contents($file->getRealPath());
            
            $response = $this->client->storage
                ->from($bucket)
                ->upload($path, $fileContent, [
                    'contentType' => $file->getMimeType()
                ]);

            if ($response) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path, $bucket),
                    'data' => $response
                ];
            }

            return [
                'success' => false,
                'error' => 'Upload failed'
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
            $response = $this->client->storage
                ->from($bucket)
                ->remove([$path]);

            return !empty($response);
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
        
        try {
            return $this->client->storage
                ->from($bucket)
                ->getPublicUrl($path);
        } catch (\Exception $e) {
            Log::error('Supabase getPublicUrl exception', [
                'message' => $e->getMessage()
            ]);
            return null;
        }
    }

    /**
     * List files in a bucket
     */
    public function list($prefix = '', $bucket = null)
    {
        $bucket = $bucket ?? $this->bucket;
        
        try {
            $response = $this->client->storage
                ->from($bucket)
                ->list($prefix, [
                    'limit' => 100,
                    'offset' => 0
                ]);

            return $response ?? [];
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
            
            $response = $this->client->storage
                ->from($bucket)
                ->upload($path, $fileContent, [
                    'contentType' => 'application/octet-stream'
                ]);

            if ($response) {
                return [
                    'success' => true,
                    'path' => $path,
                    'url' => $this->getPublicUrl($path, $bucket)
                ];
            }

            return [
                'success' => false,
                'error' => 'Upload failed'
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

    /**
     * Get the Supabase client instance
     */
    public function getClient()
    {
        return $this->client;
    }
}
