@extends('admin.layout')

@section('title', 'Edit User - Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit User</h1>
        <p class="text-muted">Update user information and permissions</p>
    </div>
    <div>
        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-info me-2">
            <i class="fas fa-eye me-2"></i>View User
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.update', $user) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name', $user->name) }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email', $user->email) }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="role" class="form-label">Role</label>
                            <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                                @foreach($roles as $value => $label)
                                    <option value="{{ $value }}" {{ old('role', $user->role) === $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                            @error('role')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Account Status</label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Active Account
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h6 class="mb-3">Change Password (Optional)</h6>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">Leave blank to keep current password</div>
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.show', $user) }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Current User Info</h6>
            </div>
            <div class="card-body text-center">
                <div class="user-avatar mx-auto mb-3" style="width: 60px; height: 60px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 1.5rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h6>{{ $user->name }}</h6>
                <p class="text-muted mb-2">{{ $user->email }}</p>
                <span class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }}">
                    {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                </span>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Account Details</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Last Login</label>
                    <div>
                        @if($user->last_login_at)
                            @php
                                $lastLogin = is_string($user->last_login_at) ? \Carbon\Carbon::parse($user->last_login_at) : $user->last_login_at;
                            @endphp
                            {{ $lastLogin->format('M j, Y g:i A') }}
                        @else
                            <span class="text-muted">Never</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Created By</label>
                    <div>
                        @if($user->creator)
                            {{ $user->creator->name }}
                        @else
                            <span class="text-muted">System</span>
                        @endif
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Member Since</label>
                    <div>
                        @php
                            $createdAt = is_string($user->created_at) ? \Carbon\Carbon::parse($user->created_at) : $user->created_at;
                        @endphp
                        {{ $createdAt->format('M j, Y') }}
                    </div>
                </div>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Role Permissions</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6 class="text-danger">Super Administrator</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>All system access</li>
                        <li><i class="fas fa-check text-success me-2"></i>User management</li>
                        <li><i class="fas fa-check text-success me-2"></i>System settings</li>
                        <li><i class="fas fa-check text-success me-2"></i>Integrations</li>
                        <li><i class="fas fa-check text-success me-2"></i>Backups</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-warning">Administrator</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>Content management</li>
                        <li><i class="fas fa-check text-success me-2"></i>User management</li>
                        <li><i class="fas fa-check text-success me-2"></i>Analytics</li>
                        <li><i class="fas fa-times text-danger me-2"></i>System settings</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Integrations</li>
                    </ul>
                </div>
                
                <div class="mb-3">
                    <h6 class="text-info">Editor</h6>
                    <ul class="list-unstyled small">
                        <li><i class="fas fa-check text-success me-2"></i>Content management</li>
                        <li><i class="fas fa-check text-success me-2"></i>Analytics (view only)</li>
                        <li><i class="fas fa-times text-danger me-2"></i>User management</li>
                        <li><i class="fas fa-times text-danger me-2"></i>System settings</li>
                        <li><i class="fas fa-times text-danger me-2"></i>Integrations</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection