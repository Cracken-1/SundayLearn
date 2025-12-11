<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BlogApiController extends Controller
{
    public function index()
    {
        try {
            // Return sample blog data for now
            $blogs = [
                [
                    'id' => 1,
                    'title' => 'Teaching Tips for Sunday School',
                    'excerpt' => 'Effective strategies for engaging young learners in Bible study.',
                    'author' => 'SundayLearn Team',
                    'published_at' => '2024-01-15',
                ],
                [
                    'id' => 2,
                    'title' => 'Creative Lesson Planning',
                    'excerpt' => 'How to make Bible lessons interactive and memorable.',
                    'author' => 'SundayLearn Team',
                    'published_at' => '2024-01-10',
                ],
                [
                    'id' => 3,
                    'title' => 'Managing Classroom Behavior',
                    'excerpt' => 'Positive discipline techniques for Sunday school teachers.',
                    'author' => 'SundayLearn Team',
                    'published_at' => '2024-01-05',
                ],
            ];

            return response()->json([
                'success' => true,
                'data' => $blogs,
                'total' => count($blogs)
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
            // Return sample blog data for now
            $blog = [
                'id' => (int)$id,
                'title' => 'Sample Blog Post #' . $id,
                'content' => 'This is sample content for blog post ' . $id . '. In a real application, this would come from the database.',
                'excerpt' => 'Sample excerpt for blog post ' . $id,
                'author' => 'SundayLearn Team',
                'published_at' => '2024-01-15',
                'tags' => ['teaching', 'sunday-school', 'tips']
            ];

            return response()->json([
                'success' => true,
                'data' => $blog
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch blog',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function latest()
    {
        try {
            // Return latest blog posts
            $blogs = [
                [
                    'id' => 1,
                    'title' => 'Teaching Tips for Sunday School',
                    'excerpt' => 'Effective strategies for engaging young learners in Bible study.',
                    'author' => 'SundayLearn Team',
                    'published_at' => '2024-01-15',
                ]
            ];

            return response()->json([
                'success' => true,
                'data' => $blogs,
                'total' => count($blogs)
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