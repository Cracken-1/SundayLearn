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
                    
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Attachments (PDF, DOCX, Images, etc.)</label>
                        <input type="file" class="form-control @error('attachments.*') is-invalid @enderror" 
                               id="attachments" name="attachments[]" multiple 
                               accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.txt,.jpg,.jpeg,.png,.gif,.zip">
                        <small class="text-muted">
                            Supported formats: PDF, Word, Excel, PowerPoint, Images, Text, ZIP (Max 10MB per file)
                        </small>
                        @error('attachments.*')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div id="attachment-preview" class="mb-3" style="display: none;">
                        <label class="form-label">Selected Files:</label>
                        <div id="attachment-list" class="list-group"></div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- SEO & Advanced -->
                    <h5 class="mb-3">SEO & Advanced Options</h5>
                    
                    <div class="mb-3">
                        <label for="tags" class="form-label">Tags</label>
                        <input type="text" class="form-control" id="tags" name="tags" 
                               value="{{ old('tags') }}" placeholder="faith, courage, miracles">
                        <small class="text-muted">Comma-separated tags</small>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="meta_title" class="form-label">Meta Title</label>
                                <input type="text" class="form-control" id="meta_title" name="meta_title" 
                                       value="{{ old('meta_title') }}">
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
                    
                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description" rows="2" 
                                  placeholder="SEO description">{{ old('meta_description') }}</textarea>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Create Lesson
                        </button>
                        <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
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
document.getElementById('attachments').addEventListener('change', function(e) {
    const files = Array.from(e.target.files);
    const preview = document.getElementById('attachment-preview');
    const list = document.getElementById('attachment-list');
    
    if (files.length > 0) {
        preview.style.display = 'block';
        list.innerHTML = '';
        
        files.forEach((file, index) => {
            const fileSize = (file.size / 1024 / 1024).toFixed(2);
            const fileIcon = getFileIcon(file.name);
            
            const item = document.createElement('div');
            item.className = 'list-group-item d-flex justify-content-between align-items-center';
            item.innerHTML = `
                <div>
                    <i class="${fileIcon}" style="margin-right: 0.5rem;"></i>
                    <strong>${file.name}</strong>
                    <span class="text-muted ms-2">(${fileSize} MB)</span>
                </div>
                <span class="badge bg-primary">${getFileType(file.name)}</span>
            `;
            list.appendChild(item);
        });
    } else {
        preview.style.display = 'none';
    }
});

function getFileIcon(filename) {
    const ext = filename.split('.').pop().toLowerCase();
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
        'zip': 'fas fa-file-archive text-dark'
    };
    return icons[ext] || 'fas fa-file text-secondary';
}

function getFileType(filename) {
    const ext = filename.split('.').pop().toUpperCase();
    return ext;
}
</script>
@endpush
@endsection