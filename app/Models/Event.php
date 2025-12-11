<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Event extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'description',
        'event_date',
        'event_type',
        'color',
        'icon',
        'is_featured',
        'display_order',
    ];
    
    protected $casts = [
        'event_date' => 'date',
        'is_featured' => 'boolean',
        'display_order' => 'integer',
    ];
    
    // Get days until event
    public function getDaysUntilAttribute()
    {
        return Carbon::now()->diffInDays($this->event_date, false);
    }
    
    // Check if event is upcoming
    public function getIsUpcomingAttribute()
    {
        return $this->event_date >= Carbon::today();
    }
    
    // Scope for upcoming events
    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', Carbon::today())
                    ->orderBy('event_date', 'asc');
    }
    
    // Scope for featured events
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
