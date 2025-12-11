@extends('admin.layout')

@section('title', 'Create User - Admin Dashboard')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Create New User</h1>
        <p class="text-muted">Add a new admin user to the system</p>
    </div>
    <div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Users
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.users.store') }}">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Full Name</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                   id="name" name="name" value="{{ old('name') }}" required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" 
                                   id="email" name="email" value="{{ old('email') }}" required>
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" 
                                   id="password" name="password" required>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" 
                                   id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="role" class="form-label">Role</label>
                        <select class="form-select @error('role') is-invalid @enderror" id="role" name="role" required>
                            <option value="">Select a role</option>
                            @foreach($roles as $value => $label)
                                <option value="{{ $value }}" {{ old('role') === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div class="form-text">
                            <strong>Super Administrator:</strong> Full system access<br>
                            <strong>Administrator:</strong> Full access except system settings<br>
                            <strong>Editor:</strong> Content management only
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="password_change_required" 
                                   name="password_change_required" value="1" {{ old('password_change_required') ? 'checked' : '' }}>
                            <label class="form-check-label" for="password_change_required">
                                Require password change on first login
                            </label>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end gap-2">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Create User
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
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