@extends('admin.layout')

@section('title', 'Edit Resource - Admin')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-edit"></i> Edit Resource</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.resources.update', $resource) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $resource->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $resource->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label">Resource Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="worksheet" {{ old('type', $resource->type) == 'worksheet' ? 'selected' : '' }}>Worksheet</option>
                            <option value="coloring_page" {{ old('type', $resource->type) == 'coloring_page' ? 'selected' : '' }}>Coloring Page</option>
                            <option value="activity_guide" {{ old('type', $resource->type) == 'activity_guide' ? 'selected' : '' }}>Activity Guide</option>
                            <option value="craft" {{ old('type', $resource->type) == 'craft' ? 'selected' : '' }}>Craft</option>
                            <option value="game" {{ old('type', $resource->type) == 'game' ? 'selected' : '' }}>Game</option>
                            <option value="lesson_plan" {{ old('type', $resource->type) == 'lesson_plan' ? 'selected' : '' }}>Lesson Plan</option>
                            <option value="other" {{ old('type', $resource->type) == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Current File Info -->
                    <div class="alert alert-info">
                        <h6 class="mb-2"><i class="fas fa-file"></i> Current File</h6>
                        <p class="mb-1"><strong>File:</strong> {{ basename($resource->file_url) }}</p>
                        <p class="mb-1"><strong>Type:</strong> {{ strtoupper($resource->file_type) }}</p>
                        <p class="mb-1"><strong>Size:</strong> {{ $resource->file_size_formatted }}</p>
                        <p class="mb-0"><strong>Downloads:</strong> {{ number_format($resource->downloads_count) }}</p>
                    </div>
                    
                    <!-- Replace File -->
                    <div class="mb-3">
                        <label for="file" class="form-label">Replace File (Optional)</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Leave empty to keep current file. Max: 10MB</small>
                    </div>
                    
                    <!-- Current Thumbnail -->
                    @if($resource->thumbnail)
                    <div class="mb-3">
                        <label class="form-label">Current Thumbnail</label>
                        <div>
                            <img src="{{ asset('storage/' . $resource->thumbnail) }}" alt="Thumbnail" class="img-thumbnail" style="max-width: 200px;">
                        </div>
                    </div>
                    @endif
                    
                    <!-- Replace Thumbnail -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">{{ $resource->thumbnail ? 'Replace' : 'Add' }} Thumbnail (Optional)</label>
                        <input type="file" class="form-control @error('thumbnail') is-invalid @enderror" 
                               id="thumbnail" name="thumbnail" 
                               accept=".jpg,.jpeg,.png">
                        @error('thumbnail')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Upload a preview image (JPG, PNG - Max: 2MB)</small>
                    </div>
                    
                    <!-- Category -->
                    <div class="mb-3">
                        <label for="category" class="form-label">Category</label>
                        <input type="text" class="form-control @error('category') is-invalid @enderror" 
                               id="category" name="category" value="{{ old('category', $resource->category) }}">
                        @error('category')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Age Group -->
                    <div class="mb-3">
                        <label for="age_group" class="form-label">Age Group *</label>
                        <select class="form-select @error('age_group') is-invalid @enderror" 
                                id="age_group" name="age_group" required>
                            <option value="">Select Age Group</option>
                            <option value="3-5" {{ old('age_group', $resource->age_group) == '3-5' ? 'selected' : '' }}>3-5 years</option>
                            <option value="6-8" {{ old('age_group', $resource->age_group) == '6-8' ? 'selected' : '' }}>6-8 years</option>
                            <option value="9-11" {{ old('age_group', $resource->age_group) == '9-11' ? 'selected' : '' }}>9-11 years</option>
                            <option value="12+" {{ old('age_group', $resource->age_group) == '12+' ? 'selected' : '' }}>12+ years</option>
                            <option value="All Ages" {{ old('age_group', $resource->age_group) == 'All Ages' ? 'selected' : '' }}>All Ages</option>
                        </select>
                        @error('age_group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Featured -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                   {{ old('is_featured', $resource->is_featured) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Featured</strong> - Display prominently on resources page
                            </label>
                        </div>
                    </div>
                    
                    <!-- Metadata -->
                    <div class="alert alert-light">
                        <small class="text-muted">
                            <strong>Created:</strong> {{ $resource->created_at->format('M d, Y g:i A') }}<br>
                            <strong>Last Updated:</strong> {{ $resource->updated_at->format('M d, Y g:i A') }}
                        </small>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Resource
                        </button>
                        <a href="{{ route('admin.resources.index') }}" class="btn btn-secondary">
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
                <h6 class="mb-0"><i class="fas fa-chart-line"></i> Resource Stats</h6>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <h6>Downloads</h6>
                    <h3 class="text-success">{{ number_format($resource->downloads_count) }}</h3>
                </div>
                
                <div class="mb-3">
                    <h6>Status</h6>
                    @if($resource->is_featured)
                        <span class="badge bg-warning">Featured</span>
                    @else
                        <span class="badge bg-secondary">Regular</span>
                    @endif
                </div>
                
                <div>
                    <h6>File Info</h6>
                    <p class="small mb-0">
                        <strong>Type:</strong> {{ strtoupper($resource->file_type) }}<br>
                        <strong>Size:</strong> {{ $resource->file_size_formatted }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
