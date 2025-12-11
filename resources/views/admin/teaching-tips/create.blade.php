@extends('admin.layout')

@section('title', 'Create Teaching Tip - Admin')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Create New Teaching Tip</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.teaching-tips.store') }}">
                    @csrf
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required 
                               placeholder="e.g., Use Visual Aids">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Content -->
                    <div class="mb-3">
                        <label for="content" class="form-label">Content *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="5" required 
                                  placeholder="Enter the teaching tip content...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Provide helpful advice for Sunday school teachers</small>
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category *</label>
                        <select class="form-select @error('category') is-invalid @enderror" 
                                id="category" name="category" required>
                            <option value="">Select Category</option>
                            <option value="Teaching Methods" {{ old('category') == 'Teaching Methods' ? 'selected' : '' }}>Teaching Methods</option>
                            <option value="Engagement" {{ old('category') == 'Engagement' ? 'selected' : '' }}>Engagement</option>
                            <option value="Storytelling" {{ old('category') == 'Storytelling' ? 'selected' : '' }}>Storytelling</option>
                            <option value="Classroom Management" {{ old('category') == 'Classroom Management' ? 'selected' : '' }}>Classroom Management</option>
                            <option value="Activities" {{ old('category') == 'Activities' ? 'selected' : '' }}>Activities</option>
                            <option value="Prayer" {{ old('category') == 'Prayer' ? 'selected' : '' }}>Prayer</option>
                            <option value="General" {{ old('category') == 'General' ? 'selected' : '' }}>General</option>
                        </select>
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Icon -->
                    <div class="mb-3">
                        <label for="icon" class="form-label">Icon *</label>
                        <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                               id="icon" name="icon" value="{{ old('icon', 'lightbulb') }}" required 
                               placeholder="e.g., lightbulb, book-reader, users">
                        @error('icon')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Font Awesome icon name (without 'fa-' prefix)</small>
                        <div class="mt-2">
                            <span class="badge bg-light text-dark me-1">lightbulb</span>
                            <span class="badge bg-light text-dark me-1">book-reader</span>
                            <span class="badge bg-light text-dark me-1">users</span>
                            <span class="badge bg-light text-dark me-1">chalkboard-teacher</span>
                            <span class="badge bg-light text-dark me-1">hands-helping</span>
                            <span class="badge bg-light text-dark me-1">heart</span>
                        </div>
                    </div>
                    
                    <!-- Display Order -->
                    <div class="mb-3">
                        <label for="display_order" class="form-label">Display Order</label>
                        <input type="number" class="form-control @error('display_order') is-invalid @enderror" 
                               id="display_order" name="display_order" value="{{ old('display_order', 0) }}" min="0">
                        @error('display_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Lower numbers appear first (0 = default)</small>
                    </div>
                    
                    <!-- Active Status -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1" 
                                   {{ old('is_active', true) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">
                                <strong>Active</strong> - Display this tip on the website
                            </label>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Teaching Tip
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
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Guidelines</h6>
            </div>
            <div class="card-body">
                <h6>Creating Effective Tips</h6>
                <ul class="small">
                    <li>Keep tips concise and actionable</li>
                    <li>Focus on practical advice</li>
                    <li>Use encouraging language</li>
                    <li>Include specific examples when possible</li>
                </ul>
                
                <h6 class="mt-3">Categories</h6>
                <ul class="small">
                    <li><strong>Teaching Methods:</strong> How to teach effectively</li>
                    <li><strong>Engagement:</strong> Keeping students interested</li>
                    <li><strong>Storytelling:</strong> Bible story techniques</li>
                    <li><strong>Classroom Management:</strong> Handling behavior</li>
                    <li><strong>Activities:</strong> Hands-on learning ideas</li>
                    <li><strong>Prayer:</strong> Prayer-related guidance</li>
                </ul>
                
                <h6 class="mt-3">Display</h6>
                <p class="small mb-0">Tips are randomly displayed on the lessons page sidebar. Only active tips will be shown to visitors.</p>
            </div>
        </div>
    </div>
</div>
@endsection
