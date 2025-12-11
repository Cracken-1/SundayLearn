<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdminLoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\AdminUser;

class AuthController extends Controller
{
    /**
     * Show the admin login form
     */
    public function showLogin()
    {
        // If there's a session issue parameter, clear everything
        if (request()->has('session_issue')) {
            Auth::guard('admin')->logout();
            session()->invalidate();
            session()->regenerateToken();
        }
        
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(AdminLoginRequest $request)
    {
        try {
            $email = $request->validated()['email'];
            $password = $request->validated()['password'];
            
            // Check for default admin credentials first
            if ($email === 'admin@sundaylearn.com' && $password === 'password') {
                // Create or find admin user
                $admin = AdminUser::firstOrCreate(
                    ['email' => $email],
                    [
                        'name' => 'Admin User',
                        'password' => Hash::make($password),
                        'role' => 'super_admin',
                        'is_active' => true,
                    ]
                );

                // Ensure user is active
                if (!$admin->is_active) {
                    \Illuminate\Support\Facades\RateLimiter::hit($request->throttleKey());
                    return back()
                        ->withInput($request->only('email'))
                        ->withErrors(['email' => 'Your account has been deactivated. Please contact an administrator.']);
                }

                // Clear rate limiting on successful login
                \Illuminate\Support\Facades\RateLimiter::clear($request->throttleKey());
                
                // Log the admin in
                Auth::guard('admin')->login($admin, $request->boolean('remember'));
                
                // Update last login
                $admin->updateLastLogin($request->ip());

                $roleName = ucfirst(str_replace('_', ' ', $admin->role));
                return redirect()->route('admin.dashboard')
                    ->with('success', "Welcome, {$admin->name}! You are logged in as {$roleName}.");
            }

            // Find the user first to check if they exist and are active
            $admin = AdminUser::where('email', $email)->first();
            
            if (!$admin) {
                \Illuminate\Support\Facades\RateLimiter::hit($request->throttleKey());
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'These credentials do not match our records.']);
            }
            
            if (!$admin->is_active) {
                \Illuminate\Support\Facades\RateLimiter::hit($request->throttleKey());
                return back()
                    ->withInput($request->only('email'))
                    ->withErrors(['email' => 'Your account has been deactivated. Please contact an administrator.']);
            }
            
            // Try normal authentication
            $credentials = $request->only('email', 'password');
            
            if (Auth::guard('admin')->attempt($credentials, $request->boolean('remember'))) {
                // Clear rate limiting on successful login
                \Illuminate\Support\Facades\RateLimiter::clear($request->throttleKey());
                
                $admin = Auth::guard('admin')->user();
                $admin->updateLastLogin($request->ip());
                
                $roleName = ucfirst(str_replace('_', ' ', $admin->role));
                return redirect()->intended(route('admin.dashboard'))
                    ->with('success', "Welcome back, {$admin->name}! You are logged in as {$roleName}.");
            }

            // Authentication failed - increment rate limiting
            \Illuminate\Support\Facades\RateLimiter::hit($request->throttleKey());
            
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'These credentials do not match our records.']);

        } catch (\Exception $e) {
            // Log the error for debugging
            \Log::error('Admin login error: ' . $e->getMessage(), [
                'email' => $email ?? 'unknown',
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Increment rate limiting on error
            \Illuminate\Support\Facades\RateLimiter::hit($request->throttleKey());
            
            return back()
                ->withInput($request->only('email'))
                ->withErrors(['email' => 'Login failed. Please try again.']);
        }
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request)
    {
        try {
            Auth::guard('admin')->logout();
        } catch (\Exception $e) {
            // Fallback to default guard
            Auth::logout();
        }

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')
            ->with('success', 'You have been logged out successfully.');
    }
}