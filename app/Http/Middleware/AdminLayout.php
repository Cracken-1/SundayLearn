<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminLayout
{
    public function handle(Request $request, Closure $next)
    {
        // Set a flag to indicate we're in admin area
        view()->share('isAdminArea', true);
        
        return $next($request);
    }
}