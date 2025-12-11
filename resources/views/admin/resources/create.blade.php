@extends('admin.layout')

@section('title', 'Create Resource - Admin')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-file-upload"></i> Upload New Resource</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.resources.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Title -->
                    <div class="mb-3">
                        <label for="title" class="form-label">Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required 
                               placeholder="e.g., Noah's Ark Coloring Page">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Description -->
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3" 
                                  placeholder="Brief description of the resource...">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror>
                    </div>
                    
                    <!-- Type -->
                    <div class="mb-3">
                        <label for="type" class="form-label">Resource Type *</label>
                        <select class="form-select @error('type') is-invalid @enderror" 
                                id="type" name="type" required>
                            <option value="">Select Type</option>
                            <option value="worksheet" {{ old('type') == 'worksheet' ? 'selected' : '' }}>Worksheet</option>
                            <option value="coloring_page" {{ old('type') == 'coloring_page' ? 'selected' : '' }}>Coloring Page</option>
                            <option value="activity_guide" {{ old('type') == 'activity_guide' ? 'selected' : '' }}>Activity Guide</option>
                            <option value="craft" {{ old('type') == 'craft' ? 'selected' : '' }}>Craft</option>
                            <option value="game" {{ old('type') == 'game' ? 'selected' : '' }}>Game</option>
                            <option value="lesson_plan" {{ old('type') == 'lesson_plan' ? 'selected' : '' }}>Lesson Plan</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- File Upload -->
                    <div class="mb-3">
                        <label for="file" class="form-label">File *</label>
                        <input type="file" class="form-control @error('file') is-invalid @enderror" 
                               id="file" name="file" required 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png,.zip">
                        @error('file')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            Accepted: PDF, DOC, DOCX, XLS, XLSX, PPT, PPTX, JPG, PNG, ZIP (Max: 10MB)
                        </small>
                    </div>
                    
                    <!-- Thumbnail Upload -->
                    <div class="mb-3">
                        <label for="thumbnail" class="form-label">Thumbnail (Optional)</label>
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
                               id="category" name="category" value="{{ old('category') }}" 
                               placeholder="e.g., Old Testament, New Testament, Holidays">
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
                            <option value="3-5" {{ old('age_group') == '3-5' ? 'selected' : '' }}>3-5 years</option>
                            <option value="6-8" {{ old('age_group') == '6-8' ? 'selected' : '' }}>6-8 years</option>
                            <option value="9-11" {{ old('age_group') == '9-11' ? 'selected' : '' }}>9-11 years</option>
                            <option value="12+" {{ old('age_group') == '12+' ? 'selected' : '' }}>12+ years</option>
                            <option value="All Ages" {{ old('age_group') == 'All Ages' ? 'selected' : '' }}>All Ages</option>
                        </select>
                        @error('age_group')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- Featured -->
                    <div class="mb-4">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" value="1" 
                                   {{ old('is_featured') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_featured">
                                <strong>Featured</strong> - Display prominently on resources page
                            </label>
                        </div>
                    </div>
                    
                    <!-- Submit Buttons -->
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-upload"></i> Upload Resource
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
                <h6 class="mb-0"><i class="fas fa-info-circle"></i> Upload Guidelines</h6>
            </div>
            <div class="card-body">
                <h6>File Requirements</h6>
                <ul class="small">
                    <li>Maximum file size: 10MB</li>
                    <li>Supported formats: PDF, Word, Excel, PowerPoint, Images, ZIP</li>
                    <li>Use descriptive file names</li>
                </ul>
                
                <h6 class="mt-3">Resource Types</h6>
                <ul class="small">
                    <li><strong>Worksheet:</strong> Printable activities</li>
                    <li><strong>Coloring Page:</strong> Coloring activities</li>
                    <li><strong>Activity Guide:</strong> Step-by-step instructions</li>
                    <li><strong>Craft:</strong> Craft projects</li>
                    <li><strong>Game:</strong> Interactive games</li>
                    <li><strong>Lesson Plan:</strong> Complete lesson plans</li>
                </ul>
                
                <h6 class="mt-3">Best Practices</h6>
                <ul class="small mb-0">
                    <li>Add clear, descriptive titles</li>
                    <li>Include age-appropriate content</li>
                    <li>Upload thumbnails for better visibility</li>
                    <li>Mark popular resources as featured</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
