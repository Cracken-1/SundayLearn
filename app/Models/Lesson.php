<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'scripture',
        'theme',
        'age_group',
        'duration',
        'thumbnail',
        'image_url',
        'overview',
        'objectives',
        'content',
        'discussion_questions',
        'video_url',
        'audio_url',
        'downloads',
        'attachments',
        'category',
        'difficulty',
        'order',
        'tags',
        'meta_title',
        'meta_description',
        'is_featured',
        'views_count',
        'last_viewed_at',
        'published_at',
        'status'
    ];

    protected $casts = [
        'objectives' => 'array',
        'discussion_questions' => 'array',
        'downloads' => 'array',
        'attachments' => 'array',
        'tags' => 'array',
        'is_featured' => 'boolean',
        'views_count' => 'integer',
        'duration' => 'integer',
        'order' => 'integer',
        'last_viewed_at' => 'datetime',
        'published_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope for filtering by age group
    public function scopeAgeGroup($query, $ageGroup)
    {
        return $query->where('age_group', 'like', "%{$ageGroup}%");
    }

    // Scope for filtering by media type
    public function scopeHasVideo($query)
    {
        return $query->whereNotNull('video_url');
    }

    public function scopeHasAudio($query)
    {
        return $query->whereNotNull('audio_url');
    }

    // Check if lesson has multimedia
    public function hasMultimedia()
    {
        return !empty($this->video_url) || !empty($this->audio_url) || !empty($this->attachments);
    }

    // Get attachments by type
    public function getAttachmentsByType($type)
    {
        if (empty($this->attachments)) {
            return [];
        }
        
        return array_filter($this->attachments, function($attachment) use ($type) {
            return isset($attachment['type']) && $attachment['type'] === $type;
        });
    }

    // Increment view count
    public function incrementViews()
    {
        $this->increment('views_count');
        $this->update(['last_viewed_at' => now()]);
    }

    // Scope for published lessons
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    // Scope for featured lessons
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    // Scope for filtering by category
    public function scopeCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    // Scope for filtering by difficulty
    public function scopeDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }
}
