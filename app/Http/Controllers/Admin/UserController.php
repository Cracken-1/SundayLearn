<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $currentUser = Auth::guard('admin')->user();
        
        // Check if user is authenticated
        if (!$currentUser) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access this area.');
        }
        
        // Only super_admin and admin can manage users
        if (!$currentUser->canManageUsers()) {
            abort(403, "Unauthorized access. Current role: {$currentUser->role}. Required: super_admin or admin.");
        }

        $users = AdminUser::with('creator')->orderBy('created_at', 'desc')->get();
        
        return view('admin.users.index', compact('users', 'currentUser'));
    }

    public function create()
    {
        $currentUser = Auth::guard('admin')->user();
        
        if (!$currentUser) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access this area.');
        }
        
        if (!$currentUser->canManageUsers()) {
            abort(403, "Unauthorized access. Current role: {$currentUser->role}. Required: super_admin or admin.");
        }

        $roles = AdminUser::getAvailableRoles();
        
        // Remove viewer role as requested
        unset($roles['viewer']);
        
        return view('admin.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $currentUser = Auth::guard('admin')->user();
        
        if (!$currentUser) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access this area.');
        }
        
        if (!$currentUser->canManageUsers()) {
            abort(403, "Unauthorized access. Current role: {$currentUser->role}. Required: super_admin or admin.");
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:admin_users',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['super_admin', 'admin', 'editor'])],
        ]);

        AdminUser::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'is_active' => true,
            'created_by' => $currentUser->id,
            'password_change_required' => $request->boolean('password_change_required'),
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function show(AdminUser $user)
    {
        $currentUser = Auth::guard('admin')->user();
        
        if (!$currentUser) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access this area.');
        }
        
        if (!$currentUser->canManageUsers()) {
            abort(403, "Unauthorized access. Current role: {$currentUser->role}. Required: super_admin or admin.");
        }

        return view('admin.users.show', compact('user'));
    }

    public function edit(AdminUser $user)
    {
        $currentUser = Auth::guard('admin')->user();
        
        if (!$currentUser) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access this area.');
        }
        
        if (!$currentUser->canManageUsers()) {
            abort(403, "Unauthorized access. Current role: {$currentUser->role}. Required: super_admin or admin.");
        }

        $roles = AdminUser::getAvailableRoles();
        unset($roles['viewer']);
        
        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, AdminUser $user)
    {
        $currentUser = Auth::guard('admin')->user();
        
        if (!$currentUser) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access this area.');
        }
        
        if (!$currentUser->canManageUsers()) {
            abort(403, "Unauthorized access. Current role: {$currentUser->role}. Required: super_admin or admin.");
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admin_users')->ignore($user->id)],
            'role' => ['required', Rule::in(['super_admin', 'admin', 'editor'])],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
            'is_active' => $request->boolean('is_active'),
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(AdminUser $user)
    {
        $currentUser = Auth::guard('admin')->user();
        
        if (!$currentUser) {
            return redirect()->route('admin.login')->with('error', 'Please log in to access this area.');
        }
        
        if (!$currentUser->canManageUsers()) {
            abort(403, "Unauthorized access. Current role: {$currentUser->role}. Required: super_admin or admin.");
        }

        // Prevent deleting yourself
        if ($user->id === $currentUser->id) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    /**
     * Show account settings for current user
     */
    public function accountSettings()
    {
        $user = Auth::guard('admin')->user();
        return view('admin.users.account-settings', compact('user'));
    }

    /**
     * Update account settings
     */
    public function updateAccountSettings(Request $request)
    {
        $user = Auth::guard('admin')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admin_users')->ignore($user->id)],
            'current_password' => 'required_with:password',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // Verify current password if changing password
        if ($request->filled('password')) {
            if (!Hash::check($request->current_password, $user->password)) {
                return back()->withErrors(['current_password' => 'Current password is incorrect.']);
            }

            // For editors, require admin approval for password changes
            if ($user->isEditor()) {
                $user->update([
                    'password_change_required' => true,
                ]);
                
                return back()->with('warning', 'Password change request submitted. An administrator will approve it.');
            }

            $user->update([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            return back()->with('success', 'Account settings updated successfully.');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);

        return back()->with('success', 'Account settings updated successfully.');
    }
}