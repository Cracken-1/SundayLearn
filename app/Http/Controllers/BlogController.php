<?php

namespace App\Http\Controllers;

use App\Models\BlogPost;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index()
    {
        // Get published blog posts from database
        $posts = BlogPost::where('status', 'published')
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->orderBy('published_at', 'desc')
            ->paginate(12);
        
        // Get categories with counts from database
        $categories = BlogPost::where('status', 'published')
            ->whereNotNull('category')
            ->selectRaw('category, COUNT(*) as count')
            ->groupBy('category')
            ->get()
            ->map(function($cat) {
                return [
                    'name' => $cat->category,
                    'count' => $cat->count,
                    'icon' => $this->getCategoryIcon($cat->category)
                ];
            })
            ->toArray();
        
        // Add sample categories if database is empty
        if (empty($categories)) {
            $categories = [
                ['name' => 'Teaching Tips', 'count' => 0, 'icon' => 'lightbulb'],
                ['name' => 'Classroom Management', 'count' => 0, 'icon' => 'users-cog'],
                ['name' => 'Lesson Planning', 'count' => 0, 'icon' => 'calendar-alt'],
                ['name' => 'Activities & Crafts', 'count' => 0, 'icon' => 'palette'],
            ];
        }
        
        return view('blog.index', compact('posts', 'categories'));
    }

    public function show($id)
    {
        $post = BlogPost::where('status', 'published')
            ->where('id', $id)
            ->firstOrFail();
        
        // Increment view count
        $post->increment('views_count');
        
        // Get related posts
        $relatedPosts = BlogPost::where('status', 'published')
            ->where('id', '!=', $id)
            ->where(function($query) use ($post) {
                if ($post->category) {
                    $query->where('category', $post->category);
                }
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();

        return view('blog.show', compact('post', 'relatedPosts'));
    }
    
    private function getCategoryIcon($category)
    {
        $icons = [
            'Teaching Tips' => 'lightbulb',
            'Classroom Management' => 'users-cog',
            'Lesson Planning' => 'calendar-alt',
            'Activities & Crafts' => 'palette',
            'Bible Study' => 'bible',
            'Prayer' => 'praying-hands',
            'Worship' => 'music',
            'Parenting' => 'heart',
        ];
        
        return $icons[$category] ?? 'bookmark';
    }
}