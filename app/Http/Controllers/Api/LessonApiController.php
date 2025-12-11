<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\TelegramRawImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LessonApiController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $lessons = Lesson::query()
            ->when($request->age_group, fn($q) => $q->where('age_group', 'like', "%{$request->age_group}%"))
            ->when($request->theme, fn($q) => $q->where('theme', 'like', "%{$request->theme}%"))
            ->when($request->search, function($q) use ($request) {
                $q->where(function($query) use ($request) {
                    $query->where('title', 'like', "%{$request->search}%")
                          ->orWhere('scripture', 'like', "%{$request->search}%")
                          ->orWhere('content', 'like', "%{$request->search}%");
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($lessons);
    }

    public function show(Lesson $lesson): JsonResponse
    {
        return response()->json($lesson);
    }

    public function telegramImports(Request $request): JsonResponse
    {
        $imports = TelegramRawImport::query()
            ->when($request->status, fn($q) => $q->where('processing_status', $request->status))
            ->when($request->media_type, fn($q) => $q->where('media_type', $request->media_type))
            ->with('lesson')
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json($imports);
    }

    public function telegramImportShow(TelegramRawImport $import): JsonResponse
    {
        $import->load('lesson');
        return response()->json($import);
    }

    public function stats(): JsonResponse
    {
        return response()->json([
            'total_lessons' => Lesson::count(),
            'telegram_imports' => [
                'total' => TelegramRawImport::count(),
                'pending' => TelegramRawImport::pending()->count(),
                'processing' => TelegramRawImport::processing()->count(),
                'completed' => TelegramRawImport::completed()->count(),
                'failed' => TelegramRawImport::failed()->count(),
            ],
            'recent_activity' => TelegramRawImport::latest()->take(5)->get(),
        ]);
    }
}