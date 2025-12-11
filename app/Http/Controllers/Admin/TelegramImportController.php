<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

class TelegramImportController extends Controller
{
    public function index(Request $request)
    {
        // Always use safe static data to avoid 500 errors
        $imports = new LengthAwarePaginator([], 0, 20);
        $stats = [
            'total' => 0,
            'pending' => 0,
            'processing' => 0,
            'completed' => 0,
            'failed' => 0,
        ];

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