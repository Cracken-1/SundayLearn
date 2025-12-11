<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Newsletter extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'email',
        'name',
        'status',
        'unsubscribe_token',
        'subscribed_at',
        'unsubscribed_at',
    ];
    
    protected $casts = [
        'subscribed_at' => 'datetime',
        'unsubscribed_at' => 'datetime',
    ];
    
    // Generate unsubscribe token
    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($newsletter) {
            if (!$newsletter->unsubscribe_token) {
                $newsletter->unsubscribe_token = Str::random(64);
            }
            if (!$newsletter->subscribed_at) {
                $newsletter->subscribed_at = now();
            }
        });
    }
    
    // Scope for subscribed
    public function scopeSubscribed($query)
    {
        return $query->where('status', 'subscribed');
    }
    
    // Unsubscribe
    public function unsubscribe()
    {
        $this->update([
            'status' => 'unsubscribed',
            'unsubscribed_at' => now(),
        ]);
    }
    
    // Resubscribe
    public function resubscribe()
    {
        $this->update([
            'status' => 'subscribed',
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
        ]);
    }
}
