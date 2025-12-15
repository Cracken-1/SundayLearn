@extends('admin.layout')

@section('title', 'Create Lesson - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Create New Lesson</h1>
        <p class="text-muted">Add a new Sunday school lesson</p>
    </div>
    <div>
        <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Lessons
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.lessons.store') }}" enctype="multipart/form-data">
                    @csrf
                    
                    <!-- Basic Information -->
                    <h5 class="mb-3">Basic Information</h5>
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Lesson Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="age_group" class="form-label">Age Group *</label>
                                <select class="form-select @error('age_group') is-invalid @enderror" 
                                        id="age_group" name="age_group" required>
                                    <option value="">Select Age Group</option>
                                    <option value="Children (3-5)">Children (3-5)</option>
                                    <option value="Children (6-8)">Children (6-8)</option>
                                    <option value="Children (9-12)">Children (9-12)</option>
                                    <option value="Teen (13-17)">Teen (13-17)</option>
                                    <option value="All Ages">All Ages</option>
                                </select>
                                @error('age_group')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="duration" class="form-label">Duration (minutes)</label>
                                <input type="number" class="form-control @error('duration') is-invalid @enderror" 
                                       id="duration" name="duration" value="{{ old('duration', 30) }}" min="1">
                                @error('duration')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status *</label>
                                <select class="form-select @error('status') is-invalid @enderror" 
                                        id="status" name="status" required>
                                    <option value="draft">Draft</option>
                                    <option value="published">Published</option>
                                </select>
                                @error('status')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="category" class="form-label">Category</label>
                                <select class="form-select" id="category" name="category">
                                    <option value="">Select Category</option>
                                    <option value="Old Testament">Old Testament</option>
                                    <option value="New Testament">New Testament</option>
                                    <option value="Parables">Parables</option>
                                    <option value="Miracles">Miracles</option>
                                    <option value="Life of Jesus">Life of Jesus</option>
                                    <option value="Character Building">Character Building</option>
                                    <option value="Holidays">Holidays</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="difficulty" class="form-label">Difficulty</label>
                                <select class="form-select" id="difficulty" name="difficulty">
                                    <option value="">Select Difficulty</option>
                                    <option value="beginner">Beginner</option>
                                    <option value="intermediate">Intermediate</option>
                                    <option value="advanced">Advanced</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="scripture" class="form-label">Scripture Reference</label>
                                <input type="text" class="form-control" id="scripture" name="scripture" 
                                       value="{{ old('scripture') }}" placeholder="e.g., John 3:16">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="theme" class="form-label">Theme</label>
                                <input type="text" class="form-control" id="theme" name="theme" 
                                       value="{{ old('theme') }}" placeholder="e.g., God's Love">
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Content -->
                    <h5 class="mb-3">Content</h5>
                    
                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Excerpt (Short Description)</label>
                        <textarea class="form-control" id="excerpt" name="excerpt" rows="2" 
                                  placeholder="Brief summary of the lesson">{{ old('excerpt') }}</textarea>
                        <small class="text-muted">A short description that appears in lesson listings</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="overview" class="form-label">Overview</label>
                        <textarea class="form-control" id="overview" name="overview" rows="3" 
                                  placeholder="Detailed overview of the lesson">{{ old('overview') }}</textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Lesson Content *</label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="10" required 
                                  placeholder="Main lesson content...">{{ old('content') }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="objectives" class="form-label">Learning Objectives</label>
                        <textarea class="form-control" id="objectives" name="objectives" rows="4" 
                                  placeholder="Enter each objective on a new line">{{ old('objectives') }}</textarea>
                        <small class="text-muted">One objective per line</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="discussion_questions" class="form-label">Discussion Questions</label>
                        <textarea class="form-control" id="discussion_questions" name="discussion_questions" rows="4" 
                                  placeholder="Enter each question on a new line">{{ old('discussion_questions') }}</textarea>
                        <small class="text-muted">One question per line</small>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Media -->
                    <h5 class="mb-3">Media & Resources</h5>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="video_url" class="form-label">Video URL</label>
                                <input type="url" class="form-control" id="video_url" name="video_url" 
                                       value="{{ old('video_url') }}" placeholder="https://youtube.com/...">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="audio_url" class="form-label">Audio URL</label>
                                <input type="url" class="form-control" id="audio_url" name="audio_url" 
                                       value="{{ old('audio_url') }}" placeholder="https://...">
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="thumbnail" class="form-label">Thumbnail Image URL</label>
                                <input type="text" class="form-control" id="thumbnail" name="thumbnail" 
                                       value="{{ old('thumbnail') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="image_url" class="form-label">Featured Image URL</label>
                                <input type="text" class="form-control" id="image_url" name="image_url" 
                                       value="{{ old('image_url') }}">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Video Attachments -->
                    <div class="mb-4">
                        <label for="video_attachments" class="form-label">
                            <i class="fas fa-video text-danger"></i> Video Files
                        </label>
                        <input type="file" class="form-control @error('video_attachments.*') is-invalid @enderror" 
                               id="video_attachments" name="video_attachments[]" multiple 
                               accept=".mp4,.avi,.mov,.wmv,.webm,.mkv,.flv">
                        <small class="text-muted">
                            Supported formats: MP4, AVI, MOV, WMV, WebM, MKV, FLV (Max 100MB per file)
                        </small>
                        @error('video_attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="video-preview" class="mt-2" style="display: none;">
                            <div id="video-list" class="list-group"></div>
                        </div>
                    </div>

                    <!-- Audio Attachments -->
                    <div class="mb-4">
                        <label for="audio_attachments" class="form-label">
                            <i class="fas fa-music text-success"></i> Audio Files
                        </label>
                        <input type="file" class="form-control @error('audio_attachments.*') is-invalid @enderror" 
                               id="audio_attachments" name="audio_attachments[]" multiple 
                               accept=".mp3,.wav,.ogg,.m4a,.aac,.flac,.wma">
                        <small class="text-muted">
                            Supported formats: MP3, WAV, OGG, M4A, AAC, FLAC, WMA (Max 100MB per file)
                        </small>
                        @error('audio_attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="audio-preview" class="mt-2" style="display: none;">
                            <div id="audio-list" class="list-group"></div>
                        </div>
                    </div>

                    <!-- Document Attachments -->
                    <div class="mb-4">
                        <label for="document_attachments" class="form-label">
                            <i class="fas fa-file-alt text-primary"></i> Documents & Images
                        </label>
                        <input type="file" class="form-control @error('document_attachments.*') is-invalid @enderror" 
                               id="document_attachments" name="document_attachments[]" multiple 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip,.rar">
                        <small class="text-muted">
                            Supported formats: PDF, Word, Excel, PowerPoint, Images, Text, ZIP, RAR (Max 100MB per file)
                        </small>
                        @error('document_attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        <div id="document-preview" class="mt-2" style="display: none;">
                            <div id="document-list" class="list-group"></div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="tags" class="form-label">Tags</label>
                                <input type="text" class="form-control" id="tags" name="tags" 
                                       value="{{ old('tags') }}" placeholder="faith, courage, miracles">
                                <small class="text-muted">Comma-separated tags</small>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="is_featured" class="form-label">Featured Lesson</label>
                                <select class="form-select" id="is_featured" name="is_featured">
                                    <option value="0">No</option>
                                    <option value="1">Yes</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary" id="submit-btn">
                            <i class="fas fa-save"></i> Create Lesson
                        </button>
                        <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                    
                    <!-- Upload Progress Bar -->
                    <div id="upload-progress" class="mt-3" style="display: none;">
                        <div class="progress" style="height: 25px;">
                            <div class="progress-bar progress-bar-striped progress-bar-animated" 
                                 role="progressbar" 
                                 style="width: 0%;" 
                                 id="progress-bar">
                                <span id="progress-text">Uploading... 0%</span>
                            </div>
                        </div>
                        <small class="text-muted mt-2 d-block" id="upload-status">Preparing upload...</small>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Lesson Guidelines</h5>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Required Fields</h6>
                <ul class="small mb-3">
                    <li>Title</li>
                    <li>Age Group</li>
                    <li>Status (Draft/Published)</li>
                    <li>Lesson Content</li>
                </ul>
                
                <h6 class="text-primary">Best Practices</h6>
                <ul class="small mb-3">
                    <li>Keep titles clear and engaging</li>
                    <li>Include scripture references</li>
                    <li>Add learning objectives</li>
                    <li>Include discussion questions</li>
                    <li>Provide multimedia resources</li>
                    <li>Use age-appropriate language</li>
                </ul>
                
                <h6 class="text-primary">Content Tips</h6>
                <ul class="small">
                    <li>Start with an engaging overview</li>
                    <li>Break content into sections</li>
                    <li>Include practical applications</li>
                    <li>Add interactive activities</li>
                    <li>Provide take-home materials</li>
                </ul>
            </div>
        </div>
        
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0"><i class="fas fa-lightbulb"></i> Quick Tips</h5>
            </div>
            <div class="card-body">
                <p class="small mb-2"><strong>Duration:</strong> Typical lessons are 30-45 minutes</p>
                <p class="small mb-2"><strong>Objectives:</strong> Enter one per line for better formatting</p>
                <p class="small mb-2"><strong>Questions:</strong> Include 3-5 discussion questions</p>
                <p class="small mb-0"><strong>Media:</strong> YouTube, Vimeo, or direct URLs work best</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Handle video attachments
document.getElementById('video_attachments').addEventListener('change', function(e) {
    handleFilePreview(e, 'video-preview', 'video-list', 'video');
});

// Handle audio attachments
document.getElementById('audio_attachments').addEventListener('change', function(e) {
    handleFilePreview(e, 'audio-preview', 'audio-list', 'audio');
});

// Handle document attachments
document.getElementById('document_attachments').addEventListener('change', function(e) {
    handleFilePreview(e, 'document-preview', 'document-list', 'document');
});

function handleFilePreview(event, previewId, listId, type) {
    const files = Array.from(event.target.files);
    const preview = document.getElementById(previewId);
    const list = document.getElementById(listId);
    
    if (files.length > 0) {
        preview.style.display = 'block';
        list.innerHTML = '';
        
        files.forEach((file, index) => {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            const fileIcon = getFileIcon(file.name, type);
            const isLargeFile = file.size > 100 * 1024 * 1024; // 100MB
            
            const item = document.createElement('div');
            item.className = `list-group-item d-flex justify-content-between align-items-center ${isLargeFile ? 'border-warning' : ''}`;
            item.innerHTML = `
                <div class="d-flex align-items-center">
                    <i class="${fileIcon}" style="margin-right: 0.75rem; font-size: 1.2em;"></i>
                    <div>
                        <strong>${file.name}</strong>
                        <div class="text-muted small">${fileSize} MB</div>
                        ${isLargeFile ? '<div class="text-warning small"><i class="fas fa-exclamation-triangle"></i> File size exceeds 100MB</div>' : ''}
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <span class="badge bg-${getBadgeColor(type)}">${getFileType(file.name)}</span>
                    <button type="button" class="btn btn-sm btn-outline-danger" onclick="removeFile(this, '${event.target.id}', ${index})">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
            `;
            list.appendChild(item);
        });
    } else {
        preview.style.display = 'none';
    }
}

function getFileIcon(filename, type) {
    const ext = filename.split('.').pop().toLowerCase();
    
    if (type === 'video') {
        return 'fas fa-file-video text-danger';
    } else if (type === 'audio') {
        return 'fas fa-file-audio text-success';
    } else {
        const icons = {
            'pdf': 'fas fa-file-pdf text-danger',
            'doc': 'fas fa-file-word text-primary',
            'docx': 'fas fa-file-word text-primary',
            'xls': 'fas fa-file-excel text-success',
            'xlsx': 'fas fa-file-excel text-success',
            'ppt': 'fas fa-file-powerpoint text-warning',
            'pptx': 'fas fa-file-powerpoint text-warning',
            'txt': 'fas fa-file-alt text-secondary',
            'jpg': 'fas fa-file-image text-info',
            'jpeg': 'fas fa-file-image text-info',
            'png': 'fas fa-file-image text-info',
            'gif': 'fas fa-file-image text-info',
            'zip': 'fas fa-file-archive text-dark',
            'rar': 'fas fa-file-archive text-dark'
        };
        return icons[ext] || 'fas fa-file text-secondary';
    }
}

function getBadgeColor(type) {
    const colors = {
        'video': 'danger',
        'audio': 'success',
        'document': 'primary'
    };
    return colors[type] || 'secondary';
}

function getFileType(filename) {
    const ext = filename.split('.').pop().toUpperCase();
    return ext;
}

function removeFile(button, inputId, index) {
    // Note: This is a visual removal only. 
    // Actual file removal from input requires recreating the FileList
    button.closest('.list-group-item').remove();
    
    // Check if list is empty and hide preview
    const listId = inputId.replace('_attachments', '-list');
    const previewId = inputId.replace('_attachments', '-preview');
    const list = document.getElementById(listId);
    const preview = document.getElementById(previewId);
    
    if (list.children.length === 0) {
        preview.style.display = 'none';
        document.getElementById(inputId).value = '';
    }
}

// File size validation and upload progress
document.addEventListener('DOMContentLoaded', function() {
    const fileInputs = ['video_attachments', 'audio_attachments', 'document_attachments'];
    const form = document.querySelector('form');
    const submitBtn = document.getElementById('submit-btn');
    const progressContainer = document.getElementById('upload-progress');
    const progressBar = document.getElementById('progress-bar');
    const progressText = document.getElementById('progress-text');
    const uploadStatus = document.getElementById('upload-status');
    
    // File size validation
    fileInputs.forEach(inputId => {
        document.getElementById(inputId).addEventListener('change', function(e) {
            const files = Array.from(e.target.files);
            let hasLargeFiles = false;
            
            files.forEach(file => {
                if (file.size > 100 * 1024 * 1024) { // 100MB
                    hasLargeFiles = true;
                }
            });
            
            if (hasLargeFiles) {
                const alert = document.createElement('div');
                alert.className = 'alert alert-warning alert-dismissible fade show mt-2';
                alert.innerHTML = `
                    <i class="fas fa-exclamation-triangle"></i>
                    <strong>Warning:</strong> Some files exceed 100MB. Upload may take longer or fail on slower connections.
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
    
    // Handle form submission with progress
    form.addEventListener('submit', function(e) {
        // Check if there are files to upload
        const hasFiles = fileInputs.some(inputId => {
            const input = document.getElementById(inputId);
            return input.files && input.files.length > 0;
        });
        
        if (hasFiles) {
            e.preventDefault();
            
            // Show progress bar
            progressContainer.style.display = 'block';
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
            
            // Create FormData
            const formData = new FormData(form);
            
            // Create XMLHttpRequest for progress tracking
            const xhr = new XMLHttpRequest();
            
            // Track upload progress
            xhr.upload.addEventListener('progress', function(e) {
                if (e.lengthComputable) {
                    const percentComplete = Math.round((e.loaded / e.total) * 100);
                    progressBar.style.width = percentComplete + '%';
                    progressText.textContent = `Uploading... ${percentComplete}%`;
                    
                    if (percentComplete < 30) {
                        uploadStatus.textContent = 'Uploading files...';
                    } else if (percentComplete < 70) {
                        uploadStatus.textContent = 'Processing attachments...';
                    } else if (percentComplete < 90) {
                        uploadStatus.textContent = 'Saving lesson data...';
                    } else {
                        uploadStatus.textContent = 'Finalizing...';
                    }
                }
            });
            
            // Handle completion
            xhr.addEventListener('load', function() {
                if (xhr.status === 200 || xhr.status === 302) {
                    progressBar.style.width = '100%';
                    progressText.textContent = 'Upload Complete!';
                    uploadStatus.textContent = 'Lesson created successfully. Redirecting...';
                    progressBar.classList.remove('progress-bar-striped', 'progress-bar-animated');
                    progressBar.classList.add('bg-success');
                    
                    // Handle redirect
                    setTimeout(() => {
                        if (xhr.responseURL) {
                            window.location.href = xhr.responseURL;
                        } else {
                            window.location.href = '{{ route("admin.lessons.index") }}';
                        }
                    }, 1000);
                } else {
                    // Handle error
                    progressBar.classList.add('bg-danger');
                    progressText.textContent = 'Upload Failed';
                    uploadStatus.textContent = 'An error occurred. Please try again.';
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Create Lesson';
                }
            });
            
            // Handle errors
            xhr.addEventListener('error', function() {
                progressBar.classList.add('bg-danger');
                progressText.textContent = 'Upload Failed';
                uploadStatus.textContent = 'Network error. Please check your connection and try again.';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="fas fa-save"></i> Create Lesson';
            });
            
            // Send request
            xhr.open('POST', form.action);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.send(formData);
        }
    });
});
</script>
@endpush
@endsection