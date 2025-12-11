<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Analytics extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'event_type',
        'event_category',
        'event_action',
        'event_label',
        'page_url',
        'referrer_url',
        'user_agent',
        'ip_hash',
        'session_hash',
        'user_id',
        'device_type',
        'browser',
        'operating_system',
        'country_code',
        'search_query',
        'metadata',
    ];
    
    protected $casts = [
        'metadata' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    
    // Scope for specific event types
    public function scopeEventType($query, $type)
    {
        return $query->where('event_type', $type);
    }
    
    // Scope for specific categories
    public function scopeCategory($query, $category)
    {
        return $query->where('event_category', $category);
    }
    
    // Scope for date range
    public function scopeDateRange($query, $start, $end)
    {
        return $query->whereBetween('created_at', [$start, $end]);
    }
    
    // Scope for today
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', Carbon::today());
    }
    
    // Scope for this week
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ]);
    }
    
    // Scope for this month
    public function scopeThisMonth($query)
    {
        return $query->whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ]);
    }
    
    // Get popular pages
    public static function getPopularPages($limit = 10)
    {
        return static::select('page_url', \DB::raw('COUNT(*) as views'))
            ->where('event_type', 'page_view')
            ->groupBy('page_url')
            ->orderBy('views', 'desc')
            ->limit($limit)
            ->get();
    }
    
    // Get popular search terms
    public static function getPopularSearches($limit = 10)
    {
        return static::select('search_query', \DB::raw('COUNT(*) as searches'))
            ->where('event_type', 'search')
            ->whereNotNull('search_query')
            ->groupBy('search_query')
            ->orderBy('searches', 'desc')
            ->limit($limit)
            ->get();
    }
    
    // Get device breakdown
    public static function getDeviceBreakdown()
    {
        return static::select('device_type', \DB::raw('COUNT(*) as count'))
            ->whereNotNull('device_type')
            ->groupBy('device_type')
            ->orderBy('count', 'desc')
            ->get();
    }
    
    // Get browser breakdown
    public static function getBrowserBreakdown()
    {
        return static::select('browser', \DB::raw('COUNT(*) as count'))
            ->whereNotNull('browser')
            ->groupBy('browser')
            ->orderBy('count', 'desc')
            ->get();
    }
    
    // Get country breakdown (using country codes for privacy)
    public static function getCountryBreakdown($limit = 10)
    {
        return static::select('country_code', \DB::raw('COUNT(*) as count'))
            ->whereNotNull('country_code')
            ->groupBy('country_code')
            ->orderBy('count', 'desc')
            ->limit($limit)
            ->get();
    }
    
    // Track a new event (privacy-compliant)
    public static function track($eventType, $category = null, $action = null, $label = null, $metadata = [])
    {
        $request = request();
        
        // Hash sensitive data for privacy
        $ipHash = $request->ip() ? hash('sha256', $request->ip() . config('app.key')) : null;
        $sessionHash = $request->session()->getId() ? hash('sha256', $request->session()->getId() . config('app.key')) : null;
        
        return static::create([
            'event_type' => $eventType,
            'event_category' => $category,
            'event_action' => $action,
            'event_label' => $label,
            'page_url' => $request->url(),
            'referrer_url' => $request->header('referer'),
            'user_agent' => static::sanitizeUserAgent($request->header('user-agent')),
            'ip_hash' => $ipHash,
            'session_hash' => $sessionHash,
            'user_id' => auth()->id(), // Only if user is authenticated
            'device_type' => static::detectDeviceType($request->header('user-agent')),
            'browser' => static::detectBrowser($request->header('user-agent')),
            'operating_system' => static::detectOS($request->header('user-agent')),
            'country_code' => static::getCountryCode($request->ip()),
            'metadata' => $metadata,
        ]);
    }
    
    // Sanitize user agent to remove potentially identifying information
    private static function sanitizeUserAgent($userAgent)
    {
        if (!$userAgent) return null;
        
        // Remove version numbers and specific build info for privacy
        $userAgent = preg_replace('/\d+\.\d+\.\d+/', 'x.x.x', $userAgent);
        $userAgent = preg_replace('/Build\/[A-Z0-9]+/', 'Build/XXXXX', $userAgent);
        
        return substr($userAgent, 0, 255); // Limit length
    }
    
    // Get country code from IP (placeholder - would use a privacy-compliant service)
    private static function getCountryCode($ip)
    {
        // In production, use a privacy-compliant geolocation service
        // that doesn't store IP addresses and only returns country codes
        // For now, return null to avoid privacy issues
        return null;
    }
    
    // Detect device type from user agent
    private static function detectDeviceType($userAgent)
    {
        if (preg_match('/Mobile|Android|iPhone|iPad/', $userAgent)) {
            if (preg_match('/iPad/', $userAgent)) {
                return 'tablet';
            }
            return 'mobile';
        }
        return 'desktop';
    }
    
    // Detect browser from user agent
    private static function detectBrowser($userAgent)
    {
        if (preg_match('/Chrome/', $userAgent)) return 'Chrome';
        if (preg_match('/Firefox/', $userAgent)) return 'Firefox';
        if (preg_match('/Safari/', $userAgent)) return 'Safari';
        if (preg_match('/Edge/', $userAgent)) return 'Edge';
        if (preg_match('/Opera/', $userAgent)) return 'Opera';
        return 'Unknown';
    }
    
    // Detect OS from user agent
    private static function detectOS($userAgent)
    {
        if (preg_match('/Windows/', $userAgent)) return 'Windows';
        if (preg_match('/Mac/', $userAgent)) return 'macOS';
        if (preg_match('/Linux/', $userAgent)) return 'Linux';
        if (preg_match('/Android/', $userAgent)) return 'Android';
        if (preg_match('/iOS/', $userAgent)) return 'iOS';
        return 'Unknown';
    }
}