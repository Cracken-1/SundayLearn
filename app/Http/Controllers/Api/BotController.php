<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BotController extends Controller
{
    public function stats()
    {
        return response()->json([
            'total_lessons' => Lesson::count(),
            'published_lessons' => Lesson::published()->count(),
            'total_blogs' => BlogPost::count(),
            'published_blogs' => BlogPost::published()->count(),
        ]);
    }

    public function quickLesson()
    {
        $lesson = Lesson::published()->inRandomOrder()->first();
        return response()->json($lesson);
    }

    public function lessonOfTheDay()
    {
        $lesson = Lesson::published()->orderBy('created_at', 'desc')->first();
        return response()->json($lesson);
    }

    public function ageGroups()
    {
        return response()->json([
            'age_groups' => ['3-5', '6-8', '9-12', 'teen']
        ]);
    }

    public function topics()
    {
        return response()->json([
            'topics' => ['old-testament', 'new-testament', 'parables', 'miracles']
        ]);
    }

    public function webhook(Request $request)
    {
        // Telegram webhook handler
        return response()->json(['status' => 'ok']);
    }

    public function help()
    {
        return response()->json([
            'message' => 'SundayLearn Bot API',
            'endpoints' => [
                '/api/bot/stats',
                '/api/bot/quick-lesson',
                '/api/bot/lesson-of-the-day',
            ]
        ]);
    }

    public function resources()
    {
        return response()->json([
            'resources' => [
                'coloring_pages' => 50,
                'worksheets' => 30,
                'activity_guides' => 20,
            ]
        ]);
    }

    public function downloadResource($type)
    {
        return response()->json([
            'type' => $type,
            'message' => 'Resource download endpoint'
        ]);
    }
}
