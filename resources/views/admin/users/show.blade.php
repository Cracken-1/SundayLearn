@extends('admin.layout')

@section('title', 'View User - Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">User Details</h1>
        <p class="text-muted">View user information and activity</p>
    </div>
    <div>
        <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning me-2">
            <i class="fas fa-edit me-2"></i>Edit User
        </a>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">User Information</h6>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Full Name</label>
                        <div class="form-control-plaintext">{{ $user->name }}</div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Email Address</label>
                        <div class="form-control-plaintext">{{ $user->email }}</div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Role</label>
                        <div>
                            <span class="badge bg-{{ $user->role === 'super_admin' ? 'danger' : ($user->role === 'admin' ? 'warning' : 'info') }} fs-6">
                                {{ ucfirst(str_replace('_', ' ', $user->role)) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Status</label>
                        <div>
                            <span class="badge bg-{{ $user->is_active ? 'success' : 'secondary' }} fs-6">
                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                            </span>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Last Login</label>
                        <div class="form-control-plaintext">
                            @if($user->last_login_at)
                                @php
                                    $lastLogin = is_string($user->last_login_at) ? \Carbon\Carbon::parse($user->last_login_at) : $user->last_login_at;
                                @endphp
                                {{ $lastLogin->format('M j, Y g:i A') }}
                                <br><small class="text-muted">{{ $lastLogin->diffForHumans() }}</small>
                            @else
                                <span class="text-muted">Never logged in</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Last Login IP</label>
                        <div class="form-control-plaintext">
                            {{ $user->last_login_ip ?? 'N/A' }}
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Created By</label>
                        <div class="form-control-plaintext">
                            @if($user->creator)
                                {{ $user->creator->name }}
                                <small class="text-muted">({{ $user->creator->email }})</small>
                            @else
                                <span class="text-muted">System</span>
                            @endif
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Member Since</label>
                        <div class="form-control-plaintext">
                            @php
                                $createdAt = is_string($user->created_at) ? \Carbon\Carbon::parse($user->created_at) : $user->created_at;
                            @endphp
                            {{ $createdAt->format('M j, Y') }}
                            <br><small class="text-muted">{{ $createdAt->diffForHumans() }}</small>
                        </div>
                    </div>
                </div>
                
                @if($user->password_change_required)
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Password Change Required:</strong> This user must change their password on next login.
                </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">User Avatar</h6>
            </div>
            <div class="card-body text-center">
                <div class="user-avatar mx-auto mb-3" style="width: 80px; height: 80px; background: var(--secondary-color); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: 600; font-size: 2rem;">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <h6>{{ $user->name }}</h6>
                <p class="text-muted">{{ ucfirst(str_replace('_', ' ', $user->role)) }}</p>
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Role Permissions</h6>
            </div>
            <div class="card-body">
                @if($user->role === 'super_admin')
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>All system access
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>User management
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>System settings
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>Integrations
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>Backups
                    </div>
                @elseif($user->role === 'admin')
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>Content management
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>User management
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>Analytics
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-times text-danger me-2"></i>System settings
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-times text-danger me-2"></i>Integrations
                    </div>
                @else
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>Content management
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>Analytics (view only)
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-times text-danger me-2"></i>User management
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-times text-danger me-2"></i>System settings
                    </div>
                    <div class="mb-2">
                        <i class="fas fa-times text-danger me-2"></i>Integrations
                    </div>
                @endif
            </div>
        </div>
        
        <div class="card mt-3">
            <div class="card-header">
                <h6 class="mb-0">Quick Actions</h6>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning btn-sm">
                        <i class="fas fa-edit me-1"></i> Edit User
                    </a>
                    
                    @if($user->is_active)
                        <button class="btn btn-secondary btn-sm" disabled>
                            <i class="fas fa-user-slash me-1"></i> Deactivate
                        </button>
                    @else
                        <button class="btn btn-success btn-sm" disabled>
                            <i class="fas fa-user-check me-1"></i> Activate
                        </button>
                    @endif
                    
                    @if(auth()->guard('admin')->user()->id !== $user->id)
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}" onsubmit="return confirm('Are you sure you want to delete this user? This action cannot be undone.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">
                                <i class="fas fa-trash me-1"></i> Delete User
                            </button>
                        </form>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection