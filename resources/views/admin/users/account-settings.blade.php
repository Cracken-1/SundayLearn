@extends('admin.layout')

@section('title', 'Account Settings - Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Account Settings</h1>
        <p class="text-muted">Manage your account information and preferences</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Profile Information</h6>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.account-settings.update') }}">
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
                    
                    <hr>
                    
                    <h6 class="mb-3">Change Password</h6>
                    @if($user->isEditor())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            As an editor, password changes require administrator approval.
                        </div>
                    @endif
                    
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control @error('current_password') is-invalid @enderror" 
                                   id="current_password" name="current_password">
                            @error('current_password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password">
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-4 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation">
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Account Information</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <div>
                        <span class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }} fs-6">
                            {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                        </span>
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Account Status</label>
                    <div>
                        <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} fs-6">
                            {{ $user->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </div>
                </div>
                
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
                <h6 class="mb-0">Security Settings</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <label class="form-label">Two-Factor Authentication</label>
                    <div>
                        <span class="badge bg-secondary">Not Enabled</span>
                    </div>
                    <small class="text-muted">Coming soon</small>
                </div>
                
                <div class="mb-3">
                    <label class="form-label">Login Sessions</label>
                    <div>
                        <button class="btn btn-sm btn-outline-danger" disabled>
                            <i class="fas fa-sign-out-alt me-1"></i>Revoke All Sessions
                        </button>
                    </div>
                    <small class="text-muted">Coming soon</small>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection