<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'author',
        'image_url',
        'category',
        'tags',
        'meta_title',
        'meta_description',
        'is_featured',
        'views_count',
        'status',
        'published_at'
    ];

    protected $casts = [
        'tags' => 'array',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope for published posts
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    // Scope for draft posts
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    // Scope for featured posts
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope for filtering by category
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope for recent posts
    public function scopeRecent($query, $limit = 5)
    {
        return $query->orderBy('published_at', 'desc')->limit($limit);
    }

    // Increment view count
    public function incrementViews()
    {
        $this->increment('views_count');
    }

    // Get formatted publish date
    public function getFormattedDateAttribute()
    {
        return $this->published_at ? $this->published_at->format('F j, Y') : 'Draft';
    }
}
