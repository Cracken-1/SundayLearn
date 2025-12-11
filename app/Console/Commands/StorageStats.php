<?php

namespace App\Console\Commands;

use App\Services\SupabaseStorageService;
use Illuminate\Console\Command;

class StorageStats extends Command
{
    protected $signature = 'storage:stats';
    protected $description = 'Display Supabase storage statistics';

    public function handle(SupabaseStorageService $storage)
    {
        $this->info('Fetching storage statistics...');
        $this->newLine();

        $stats = $storage->getStorageStats();

        if (empty($stats)) {
            $this->error('Failed to fetch storage statistics');
            return 1;
        }

        $totalSize = 0;
        $totalCount = 0;

        $this->table(
            ['Bucket', 'Files', 'Size'],
            collect($stats)->map(function ($stat, $bucket) use (&$totalSize, &$totalCount) {
                $totalSize += $stat['size'];
                $totalCount += $stat['count'];
                return [
                    $bucket,
                    number_format($stat['count']),
                    $stat['size_formatted']
                ];
            })
        );

        $this->newLine();
        $this->info("Total Files: " . number_format($totalCount));
        $this->info("Total Size: " . $this->formatBytes($totalSize));
        
        // Calculate percentage of free tier
        $freeLimit = 1024 * 1024 * 1024; // 1GB
        $percentage = ($totalSize / $freeLimit) * 100;
        
        $this->newLine();
        if ($percentage > 90) {
            $this->error("Storage Usage: {$percentage}% (Warning: Near limit!)");
        } elseif ($percentage > 75) {
            $this->warn("Storage Usage: {$percentage}%");
        } else {
            $this->info("Storage Usage: " . number_format($percentage, 2) . "%");
        }
        
        $remaining = $freeLimit - $totalSize;
        $this->info("Remaining: " . $this->formatBytes($remaining) . " of 1GB");

        return 0;
    }

    protected function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, $precision) . ' ' . $units[$i];
    }
}
