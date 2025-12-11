<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AdminAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        try {
            // Check if user is authenticated as admin
            if (!Auth::guard('admin')->check()) {
                // Clear any stale session data
                session()->invalidate();
                session()->regenerateToken();
                
                // Log unauthorized access attempt
                Log::warning('Unauthorized admin access attempt', [
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                    'url' => $request->fullUrl(),
                    'timestamp' => now()
                ]);

                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Unauthorized'], 401);
                }

                return redirect()->route('admin.login')->with('error', 'Please log in to access the admin area.');
            }

            // Get the authenticated user
            $user = Auth::guard('admin')->user();
            
            // Check if user still exists and is active
            if (!$user || !$user->is_active) {
                Auth::guard('admin')->logout();
                session()->invalidate();
                session()->regenerateToken();
                
                Log::warning('Admin access denied - user inactive or deleted', [
                    'user_id' => $user ? $user->id : 'unknown',
                    'ip' => $request->ip(),
                    'timestamp' => now()
                ]);
                
                if ($request->expectsJson()) {
                    return response()->json(['error' => 'Account deactivated'], 401);
                }
                
                return redirect()->route('admin.login')->with('error', 'Your account has been deactivated. Please contact an administrator.');
            }
            
        } catch (\Exception $e) {
            // If admin guard is not configured or other error, log and redirect
            Log::error('Admin authentication error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'url' => $request->fullUrl()
            ]);
            
            // Clear session on error
            Auth::guard('admin')->logout();
            session()->invalidate();
            session()->regenerateToken();
            
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Authentication system error'], 500);
            }
            
            return redirect()->route('admin.login')->with('error', 'Authentication error occurred. Please log in again.');
        }

        // Check for session timeout (optional)
        $lastActivity = session('last_activity');
        if ($lastActivity && (time() - $lastActivity > config('session.lifetime') * 60)) {
            Auth::guard('admin')->logout();
            session()->invalidate();
            session()->regenerateToken();
            
            return redirect()->route('admin.login')->with('error', 'Session expired. Please log in again.');
        }

        // Update last activity
        session(['last_activity' => time()]);

        // Log admin access for audit trail
        Log::info('Admin access', [
            'admin_id' => Auth::guard('admin')->id(),
            'admin_role' => $user->role ?? 'unknown',
            'ip' => $request->ip(),
            'url' => $request->fullUrl(),
            'timestamp' => now()
        ]);

        return $next($request);
    }
}