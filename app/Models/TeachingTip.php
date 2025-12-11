<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeachingTip extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title',
        'content',
        'icon',
        'category',
        'is_active',
        'display_order',
    ];
    
    protected $casts = [
        'is_active' => 'boolean',
        'display_order' => 'integer',
    ];
    
    // Scope for active tips
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
    
    // Get a random active tip
    public static function getRandomTip()
    {
        return static::active()->inRandomOrder()->first();
    }
}
