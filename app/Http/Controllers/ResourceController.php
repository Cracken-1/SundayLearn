<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use App\Http\Requests\SearchRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ResourceController extends Controller
{
    public function __construct()
    {
        $this->middleware(['throttle:60,1']); // Rate limiting
    }

    public function index(SearchRequest $request)
    {
        try {
            $query = Resource::query();
            
            // Secure filtering with validated input
            if ($request->validated()['type'] ?? null) {
                $query->where('type', $request->validated()['type']);
            }
            
            if ($request->validated()['age_group'] ?? null) {
                $query->where('age_group', $request->validated()['age_group']);
            }
            
            if ($request->validated()['category'] ?? null) {
                $query->where('category', $request->validated()['category']);
            }
            
            // Secure search with parameterized queries
            if ($searchTerm = $request->validated()['search'] ?? null) {
                $query->where(function($q) use ($searchTerm) {
                    $q->where('title', 'like', '%' . $searchTerm . '%')
                      ->orWhere('description', 'like', '%' . $searchTerm . '%');
                });
            }
            
            // Secure sorting
            $sortBy = $request->validated()['sort'] ?? 'newest';
            switch ($sortBy) {
                case 'popular':
                    $query->orderBy('downloads_count', 'desc');
                    break;
                case 'title':
                    $query->orderBy('title', 'asc');
                    break;
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                default:
                    $query->orderBy('created_at', 'desc');
            }
            
            $perPage = min($request->validated()['per_page'] ?? 12, 50); // Limit per page
            $resources = $query->paginate($perPage);
            
            // Cache expensive queries
            $featuredResources = Cache::remember('featured_resources', 3600, function () {
                return Resource::featured()->take(3)->get();
            });
            
            $types = Cache::remember('resource_types', 3600, function () {
                return Resource::select('type')
                    ->selectRaw('COUNT(*) as count')
                    ->groupBy('type')
                    ->get();
            });
            
            $ageGroups = Cache::remember('resource_age_groups', 3600, function () {
                return Resource::whereNotNull('age_group')
                    ->select('age_group')
                    ->selectRaw('COUNT(*) as count')
                    ->groupBy('age_group')
                    ->get();
            });
            
            return view('resources.index', compact('resources', 'featuredResources', 'types', 'ageGroups'));
            
        } catch (\Exception $e) {
            Log::error('Resource index error: ' . $e->getMessage());
            return back()->with('error', 'An error occurred while loading resources.');
        }
    }
    
    public function download($id)
    {
        try {
            // Validate ID is numeric
            if (!is_numeric($id) || $id <= 0) {
                abort(404);
            }
            
            $resource = Resource::findOrFail($id);
            
            // Security check: ensure file URL is safe
            if (!$this->isValidFileUrl($resource->file_url)) {
                Log::warning('Invalid file URL attempted: ' . $resource->file_url);
                abort(403, 'Invalid file access');
            }
            
            // Rate limiting for downloads
            $key = 'download_' . request()->ip() . '_' . $id;
            if (Cache::has($key)) {
                abort(429, 'Too many download attempts');
            }
            Cache::put($key, true, 60); // 1 minute cooldown
            
            // Increment download count safely
            $resource->increment('downloads_count');
            
            // Log download for analytics
            Log::info('Resource downloaded', [
                'resource_id' => $id,
                'ip' => request()->ip(),
                'user_agent' => request()->userAgent()
            ]);
            
            // Redirect to file URL
            return redirect($resource->file_url);
            
        } catch (\Exception $e) {
            Log::error('Resource download error: ' . $e->getMessage());
            abort(404);
        }
    }
    
    /**
     * Validate file URL for security
     */
    private function isValidFileUrl($url): bool
    {
        // Check if URL is from allowed domains/paths
        $allowedPaths = ['/storage/', '/uploads/', '/resources/'];
        $allowedDomains = [config('app.url'), 'https://cdn.sundaylearn.com'];
        
        // Check for local file paths
        foreach ($allowedPaths as $path) {
            if (str_starts_with($url, $path)) {
                return true;
            }
        }
        
        // Check for allowed external domains
        foreach ($allowedDomains as $domain) {
            if (str_starts_with($url, $domain)) {
                return true;
            }
        }
        
        return false;
    }
}
