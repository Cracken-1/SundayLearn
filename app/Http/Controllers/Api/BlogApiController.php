<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    public function index()
    {
        try {
            $blogs = BlogPost::where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->paginate(10);

            return response()->json([
                'success' => true,
                'data' => $blogs->items(),
                'total' => $blogs->total(),
                'current_page' => $blogs->currentPage(),
                'last_page' => $blogs->lastPage()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch blogs',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $blog = BlogPost::where('status', 'published')
                ->where('id', $id)
                ->firstOrFail();

            // Increment view count
            $blog->increment('views_count');

            return response()->json([
                'success' => true,
                'data' => $blog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found',
                'error' => $e->getMessage()
            ], 404);
        }
    }

    public function latest()
    {
        try {
            $blogs = BlogPost::where('status', 'published')
                ->whereNotNull('published_at')
                ->where('published_at', '<=', now())
                ->orderBy('published_at', 'desc')
                ->take(5)
                ->get();

            return response()->json([
                'success' => true,
                'data' => $blogs,
                'total' => $blogs->count()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch latest blogs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}