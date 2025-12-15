<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TelegramImportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $imports = \App\Models\TelegramRawImport::latest()
                ->paginate(20);
            
            $stats = [
                'total' => \App\Models\TelegramRawImport::count(),
                'pending' => \App\Models\TelegramRawImport::where('processing_status', 'pending')->count(),
                'processing' => \App\Models\TelegramRawImport::where('processing_status', 'processing')->count(),
                'completed' => \App\Models\TelegramRawImport::where('processing_status', 'completed')->count(),
                'failed' => \App\Models\TelegramRawImport::where('processing_status', 'failed')->count(),
            ];
        } catch (\Exception $e) {
            // Fallback to empty data if database issues
            $imports = new LengthAwarePaginator([], 0, 20);
            $stats = [
                'total' => 0,
                'pending' => 0,
                'processing' => 0,
                'completed' => 0,
                'failed' => 0,
            ];
        }

        return view('admin.telegram-imports.index', compact('imports', 'stats'));
    }

    public function show($id)
    {
        // Return sample import data
        $import = (object)[
            'id' => $id,
            'telegram_message_id' => '12345',
            'media_type' => 'photo',
            'caption' => 'Sample telegram import',
            'processing_status' => 'pending',
            'created_at' => now(),
            'lesson' => null
        ];
        
        return view('admin.telegram-imports.show', compact('import'));
    }
}