<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use ZipArchive;

class BackupController extends Controller
{
    public function index()
    {
        $backups = $this->getBackupList();
        $stats = [
            'total_backups' => count($backups),
            'total_size' => $this->getTotalBackupSize($backups),
            'last_backup' => $backups ? $backups[0]['created_at'] : null,
        ];

        // Check if ZipArchive is available
        $zipAvailable = class_exists('ZipArchive');

        return view('admin.backups.index', compact('backups', 'stats', 'zipAvailable'));
    }

    public function create()
    {
        try {
            // Check if ZipArchive is available
            if (!class_exists('ZipArchive')) {
                return back()->with('error', 'ZipArchive extension is not installed. Please enable php_zip extension in your php.ini file. For XAMPP: Uncomment "extension=zip" in php.ini and restart Apache.');
            }

            $backupName = 'backup_' . date('Y-m-d_H-i-s') . '.zip';
            $backupPath = storage_path('app/backups/' . $backupName);
            
            // Ensure backup directory exists
            if (!is_dir(dirname($backupPath))) {
                mkdir(dirname($backupPath), 0755, true);
            }

            $zip = new \ZipArchive();
            if ($zip->open($backupPath, \ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('Cannot create backup file');
            }

            // Add database export
            $this->addDatabaseToBackup($zip);
            
            // Add important files
            $this->addFilesToBackup($zip);
            
            $zip->close();

            return back()->with('success', "Backup created successfully: {$backupName}");
        } catch (\Exception $e) {
            return back()->with('error', 'Backup failed: ' . $e->getMessage());
        }
    }

    public function download($filename)
    {
        try {
            // Sanitize filename to prevent directory traversal
            $filename = basename($filename);
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (!file_exists($backupPath)) {
                return back()->with('error', 'Backup file not found');
            }

            // Return download response with explicit headers
            return response()->download($backupPath, $filename, [
                'Content-Type' => 'application/zip',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ]);
        } catch (\Exception $e) {
            return back()->with('error', 'Download failed: ' . $e->getMessage());
        }
    }

    public function destroy($filename)
    {
        try {
            $backupPath = storage_path('app/backups/' . $filename);
            
            if (file_exists($backupPath)) {
                unlink($backupPath);
                return back()->with('success', 'Backup deleted successfully');
            } else {
                return back()->with('error', 'Backup file not found');
            }
        } catch (\Exception $e) {
            return back()->with('error', 'Failed to delete backup: ' . $e->getMessage());
        }
    }

    private function getBackupList()
    {
        $backupDir = storage_path('app/backups');
        
        if (!is_dir($backupDir)) {
            return [];
        }

        $files = glob($backupDir . '/*.zip');
        $backups = [];

        foreach ($files as $file) {
            $backups[] = [
                'name' => basename($file),
                'size' => $this->formatBytes(filesize($file)),
                'created_at' => date('Y-m-d H:i:s', filemtime($file)),
                'path' => $file,
            ];
        }

        // Sort by creation time (newest first)
        usort($backups, function($a, $b) {
            return strtotime($b['created_at']) - strtotime($a['created_at']);
        });

        return $backups;
    }

    private function getTotalBackupSize($backups)
    {
        $totalSize = 0;
        foreach ($backups as $backup) {
            if (file_exists($backup['path'])) {
                $totalSize += filesize($backup['path']);
            }
        }
        return $this->formatBytes($totalSize);
    }

    private function addDatabaseToBackup($zip)
    {
        try {
            // Export database structure and data
            $tables = DB::select('SHOW TABLES');
            $sql = "-- SundayLearn Database Backup\n";
            $sql .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";

            foreach ($tables as $table) {
                $tableName = array_values((array) $table)[0];
                
                // Get table structure
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`");
                $sql .= "-- Table: {$tableName}\n";
                $sql .= "DROP TABLE IF EXISTS `{$tableName}`;\n";
                $sql .= $createTable[0]->{'Create Table'} . ";\n\n";
                
                // Get table data
                $rows = DB::select("SELECT * FROM `{$tableName}`");
                if (!empty($rows)) {
                    $sql .= "-- Data for table: {$tableName}\n";
                    foreach ($rows as $row) {
                        $values = array_map(function($value) {
                            return is_null($value) ? 'NULL' : "'" . addslashes($value) . "'";
                        }, (array) $row);
                        $sql .= "INSERT INTO `{$tableName}` VALUES (" . implode(', ', $values) . ");\n";
                    }
                    $sql .= "\n";
                }
            }

            $zip->addFromString('database.sql', $sql);
        } catch (\Exception $e) {
            // If database backup fails, add error info
            $zip->addFromString('database_error.txt', 'Database backup failed: ' . $e->getMessage());
        }
    }

    private function addFilesToBackup($zip)
    {
        // Add .env file
        if (file_exists(base_path('.env'))) {
            $zip->addFile(base_path('.env'), '.env');
        }

        // Add storage files
        $this->addDirectoryToZip($zip, storage_path('app/public'), 'storage/');
        
        // Add important config files
        $configFiles = [
            'composer.json',
            'composer.lock',
        ];

        foreach ($configFiles as $file) {
            $filePath = base_path($file);
            if (file_exists($filePath)) {
                $zip->addFile($filePath, $file);
            }
        }
    }

    private function addDirectoryToZip($zip, $dir, $zipPath = '')
    {
        if (!is_dir($dir)) {
            return;
        }

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($dir, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::SELF_FIRST
        );

        foreach ($iterator as $file) {
            if ($file->isFile()) {
                $relativePath = $zipPath . substr($file->getPathname(), strlen($dir) + 1);
                $zip->addFile($file->getPathname(), $relativePath);
            }
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