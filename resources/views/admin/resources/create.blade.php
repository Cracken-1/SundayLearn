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
                        @enderror
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
                            <option value="video" {{ old('type') == 'video' ? 'selected' : '' }}>Video</option>
                            <option value="other" {{ old('type') == 'other' ? 'selected' : '' }}>Other</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <!-- File Upload - Choose Type -->
                    <div class="mb-4">
                        <label class="form-label">Resource Type & File *</label>
                        
                        <!-- Video Files -->
                        <div class="mb-3">
                            <label for="video_file" class="form-label">
                                <i class="fas fa-video text-danger"></i> Video File
                            </label>
                            <input type="file" class="form-control @error('video_file') is-invalid @enderror" 
                                   id="video_file" name="video_file" 
                                   accept=".mp4,.avi,.mov,.wmv,.webm,.mkv,.flv">
                            @error('video_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Supported: MP4, AVI, MOV, WMV, WebM, MKV, FLV (Max: 100MB)
                            </small>
                        </div>

                        <!-- Audio Files -->
                        <div class="mb-3">
                            <label for="audio_file" class="form-label">
                                <i class="fas fa-music text-success"></i> Audio File
                            </label>
                            <input type="file" class="form-control @error('audio_file') is-invalid @enderror" 
                                   id="audio_file" name="audio_file" 
                                   accept=".mp3,.wav,.ogg,.m4a,.aac,.flac,.wma">
                            @error('audio_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Supported: MP3, WAV, OGG, M4A, AAC, FLAC, WMA (Max: 100MB)
                            </small>
                        </div>

                        <!-- Document Files -->
                        <div class="mb-3">
                            <label for="document_file" class="form-label">
                                <i class="fas fa-file-alt text-primary"></i> Document/Image File
                            </label>
                            <input type="file" class="form-control @error('document_file') is-invalid @enderror" 
                                   id="document_file" name="document_file" 
                                   accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar">
                            @error('document_file')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="text-muted">
                                Supported: PDF, Word, Excel, PowerPoint, Images, Text, ZIP, RAR (Max: 100MB)
                            </small>
                        </div>
                        
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle"></i>
                            <strong>Note:</strong> Please select only one file type. The system will automatically detect the resource type based on your selection.
                        </div>
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
                    <li>Maximum file size: 50MB</li>
                    <li>Supported formats: PDF, Word, Excel, PowerPoint, Images, ZIP, Video, Audio</li>
                    <li>Use descriptive file names</li>
                </ul>
                
                <h6 class="mt-3">Resource Types</h6>
                <ul class="small">
                    <li><strong>Worksheet:</strong> Printable activities</li>
                    <li><strong>Coloring Page:</strong> Coloring activities</li>
                    <li><strong>Activity Guide:</strong> Step-by-step instructions</li>
                    <li><strong>Craft:</strong> Craft projects</li>
                    <li><strong>Game:</strong> Interactive games</li>
                    <li><strong>Video:</strong> Video resources and lessons</li>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const videoInput = document.getElementById('video_file');
    const audioInput = document.getElementById('audio_file');
    const documentInput = document.getElementById('document_file');
    const typeSelect = document.getElementById('type');
    
    // Clear other inputs when one is selected
    videoInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            audioInput.value = '';
            documentInput.value = '';
            typeSelect.value = 'video';
        }
    });
    
    audioInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            videoInput.value = '';
            documentInput.value = '';
            typeSelect.value = 'audio';
        }
    });
    
    documentInput.addEventListener('change', function() {
        if (this.files.length > 0) {
            videoInput.value = '';
            audioInput.value = '';
            // Keep the user-selected type for documents
        }
    });
    
    // File size validation
    [videoInput, audioInput, documentInput].forEach(input => {
        input.addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            
            files.forEach(file => {
                if (file.size > 100 * 1024 * 1024) { // 100MB
                    const alert = document.createElement('div');
                    alert.className = 'alert alert-warning alert-dismissible fade show mt-2';
                    alert.innerHTML = `
                        <i class="fas fa-exclamation-triangle"></i>
                        <strong>Warning:</strong> File "${file.name}" exceeds 100MB. Upload may take longer or fail.
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    `;
                    e.target.parentNode.appendChild(alert);
                    
                    // Auto-dismiss after 5 seconds
                    setTimeout(() => {
                        if (alert.parentNode) {
                            alert.remove();
                        }
                    }, 5000);
                }
            });
        });
    });
});
</script>
@endpush

@endsection
