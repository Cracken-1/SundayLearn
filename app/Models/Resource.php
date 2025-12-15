<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'type',
        'file_url',
        'thumbnail',
        'category',
        'age_group',
        'file_size',
        'file_type',
        'downloads_count',
        'is_featured',
        'lesson_id',
        'source',
    ];

    /**
     * Get the lesson that owns the resource.
     */
    public function lesson()
    {
        return $this->belongsTo(Lesson::class);
    }
    
    protected $casts = [
        'file_size' => 'integer',
        'downloads_count' => 'integer',
        'is_featured' => 'boolean',
    ];
    
    // Increment download count
    public function incrementDownloads()
    {
        $this->increment('downloads_count');
    }
    
    // Scope for featured resources
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
    
    // Scope by type
    public function scopeOfType($query, $type)
    {
        return $query->where('type', $type);
    }
    
    // Scope by age group
    public function scopeForAgeGroup($query, $ageGroup)
    {
        return $query->where('age_group', $ageGroup);
    }
    
    // Get file size in human readable format
    public function getFileSizeFormattedAttribute()
    {
        if (!$this->file_size) {
            return 'Unknown';
        }
        
        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;
        
        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }
        
        return round($size, 2) . ' ' . $units[$unit];
    }
    
    // Get downloads count (alias for downloads_count)
    public function getDownloadsAttribute()
    {
        return $this->downloads_count ?? 0;
    }
    
    // Get appropriate icon for resource type
    public function getTypeIcon()
    {
        $icons = [
            'pdf' => 'file-pdf',
            'worksheet' => 'file-alt',
            'coloring' => 'palette',
            'activity' => 'clipboard-list',
            'craft' => 'cut',
            'game' => 'gamepad',
            'video' => 'video',
            'audio' => 'volume-up',
            'image' => 'image',
            'presentation' => 'presentation',
            'template' => 'file-contract',
        ];
        
        return $icons[$this->type] ?? 'file';
    }
}
