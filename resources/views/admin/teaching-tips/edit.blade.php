@extends('admin.layout')

@section('title', 'Edit Teaching Tip - Admin')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Teaching Tip</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.teaching-tips.update', $teachingTip) }}">
                    @csrf
                    @method('PUT')
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $teachingTip->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="5" required>{{ old('content', $teachingTip->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Teaching Methods" {{ old('category', $teachingTip->category) == 'Teaching Methods' ? 'selected' : '' }}>Teaching Methods</option>
                            <option value="Engagement" {{ old('category', $teachingTip->category) == 'Engagement' ? 'selected' : '' }}>Engagement</option>
                            <option value="Storytelling" {{ old('category', $teachingTip->category) == 'Storytelling' ? 'selected' : '' }}>Storytelling</option>
                            <option value="Classroom Management" {{ old('category', $teachingTip->category) == 'Classroom Management' ? 'selected' : '' }}>Classroom Management</option>
                            <option value="Activities" {{ old('category', $teachingTip->category) == 'Activities' ? 'selected' : '' }}>Activities</option>
                            <option value="Prayer" {{ old('category', $teachingTip->category) == 'Prayer' ? 'selected' : '' }}>Prayer</option>
                            <option value="General" {{ old('category', $teachingTip->category) == 'General' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Icon -->
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon *</label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" name="icon" value="{{ old('icon', $teachingTip->icon) }}" required>
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Font Awesome icon name (without 'fa-' prefix)</small>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark me-1">lightbulb</span>
                            <span class="badge bg-light text-dark me-1">book-reader</span>
                            <span class="badge bg-light text-dark me-1">users</span>
                            <span class="badge bg-light text-dark me-1">chalkboard-teacher</span>
                        </div>
                    </div>
                    
                    <!-- Display Order -->
                    <div class="mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control @error('display_order') is-invalid @enderror" 
                               id="display_order" name="display_order" value="{{ old('display_order', $teachingTip->display_order) }}" min="0">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Active Status -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', $teachingTip->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active</strong> - Display this tip on the website
                            </label>
                        </div>
                    </div>
                    
                    <!-- Metadata -->
                    <div class="alert alert-light">
                        <small class="text-muted">
                            <strong>Created:</strong> {{ $teachingTip->created_at->format('M d, Y g:i A') }}<br>
                            <strong>Last Updated:</strong> {{ $teachingTip->updated_at->format('M d, Y g:i A') }}
                        </small>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Teaching Tip
                        </button>
                        <a href="{{ route('admin.teaching-tips.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Tip Information</h6>
            </div>
            <div class="card-body">
                <h6>Current Status</h6>
                <p class="mb-2">
                    @if($teachingTip->is_active)
                        <span class="badge bg-success">Active</span>
                        <small class="text-muted d-block mt-1">This tip is visible to visitors</small>
                    @else
                        <span class="badge bg-warning">Inactive</span>
                        <small class="text-muted d-block mt-1">This tip is hidden from visitors</small>
                    @endif
                </p>
                
                <h6 class="mt-3">Display</h6>
                <p class="small mb-0">Tips are randomly displayed on the lessons page sidebar. Make sure the content is helpful and encouraging for teachers.</p>
            </div>
        </div>
    </div>
</div>
@endsection
