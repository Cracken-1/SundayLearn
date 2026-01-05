<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FileUploadController extends Controller
{
    protected $storageService;

    public function __construct(SupabaseStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => 'required|file|max:102400', // 100MB max
            'fileType' => 'required|string|in:video,audio,document',
        ]);

        $file = $request->file('file');
        $fileType = $request->input('fileType');

        $bucketMap = [
            'video' => 'lessons-attachments',
            'audio' => 'lessons-attachments',
            'document' => 'lessons-attachments',
        ];

        $bucket = $bucketMap[$fileType];

        // Define a path within the bucket, e.g., videos/filename.mp4
        $path = $fileType . 's/' . uniqid() . '-' . $file->getClientOriginalName();

        // Upload the file
        $result = $this->storageService->upload($file, $path, $bucket);

        if ($result['success']) {
            return response()->json([
                'success' => true,
                'url' => $result['url'],
                'path' => $result['path'],
                'filename' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => $result['error'] ?? 'Upload failed.',
        ], 500);
    }
}
