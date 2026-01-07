<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Newsletter extends Model
{
    protected $fillable = [
        'email',
        'subscribed_at',
        'status'
    ];

    protected $casts = [
        'subscribed_at' => 'datetime'
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeUnsubscribed($query)
    {
        return $query->where('status', 'unsubscribed');
    }
}