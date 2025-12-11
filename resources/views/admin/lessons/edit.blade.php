@extends('admin.layout')

@section('title', 'Edit Lesson - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit Lesson</h1>
        <p class="text-muted">Update lesson content</p>
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
                <form method="POST" action="{{ route('admin.lessons.update', $lesson->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Lesson Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $lesson->title }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="age_group" class="form-label">Age Group</label>
                                <select class="form-select" id="age_group" name="age_group" required>
                                    <option value="">Select Age Group</option>
                                    <option value="3-5" {{ $lesson->age_group === '3-5' ? 'selected' : '' }}>Ages 3-5</option>
                                    <option value="6-8" {{ $lesson->age_group === '6-8' ? 'selected' : '' }}>Ages 6-8</option>
                                    <option value="9-12" {{ $lesson->age_group === '9-12' ? 'selected' : '' }}>Ages 9-12</option>
                                    <option value="teen" {{ $lesson->age_group === 'teen' ? 'selected' : '' }}>Teen</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft" {{ $lesson->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $lesson->status === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Lesson Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10">{{ $lesson->content }}</textarea>
                    </div>
                    
                    <hr class="my-4">
                    
                    <!-- Existing Attachments -->
                    @if(!empty($lesson->attachments) && count($lesson->attachments) > 0)
                    <h5 class="mb-3">Current Attachments</h5>
                    <div class="mb-3">
                        <div class="list-group" id="existing-attachments">
                            @foreach($lesson->attachments as $index => $attachment)
                            <div class="list-group-item d-flex justify-content-between align-items-center" data-index="{{ $index }}">
                                <div>
                                    @php
                                        $type = strtolower($attachment['type'] ?? 'file');
                                        $iconClass = match($type) {
                                            'pdf' => 'fas fa-file-pdf text-danger',
                                            'doc', 'docx' => 'fas fa-file-word text-primary',
                                            'xls', 'xlsx' => 'fas fa-file-excel text-success',
                                            'ppt', 'pptx' => 'fas fa-file-powerpoint text-warning',
                                            'txt' => 'fas fa-file-alt text-secondary',
                                            'jpg', 'jpeg', 'png', 'gif' => 'fas fa-file-image text-info',
                                            'zip' => 'fas fa-file-archive text-dark',
                                            default => 'fas fa-file text-secondary'
                                        };
                                    @endphp
                                    <i class="{{ $iconClass }}" style="margin-right: 0.5rem;"></i>
                                    <strong>{{ $attachment['name'] ?? 'Attachment' }}</strong>
                                    <span class="text-muted ms-2">
                                        ({{ strtoupper($attachment['type'] ?? 'FILE') }}
                                        @if(isset($attachment['size']))
                                            - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                        @endif)
                                    </span>
                                </div>
                                <div>
                                    <a href="{{ $attachment['url'] ?? '#' }}" class="btn btn-sm btn-primary me-2" download>
                                        <i class="fas fa-download"></i>
                                    </a>
                                    <button type="button" class="btn btn-sm btn-danger remove-attachment" data-index="{{ $index }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    <!-- Add New Attachments -->
                    <h5 class="mb-3">Add New Attachments</h5>
                    <div class="mb-3">
                        <label for="attachments" class="form-label">Upload Files (PDF, DOCX, Images, etc.)</label>
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
                        <label class="form-label">New Files to Upload:</label>
                        <div id="attachment-list" class="list-group"></div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Lesson
                        </button>
                        <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
                            Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Lesson Info</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $lesson->id }}</p>
                <p><strong>Created:</strong> {{ $lesson->created_at ?? 'N/A' }}</p>
                <p><strong>Last Updated:</strong> {{ $lesson->updated_at ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// File preview for new attachments
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
                <span class="badge bg-success">New</span>
            `;
            list.appendChild(item);
        });
    } else {
        preview.style.display = 'none';
    }
});

// Remove existing attachment
document.querySelectorAll('.remove-attachment').forEach(button => {
    button.addEventListener('click', function() {
        const index = this.dataset.index;
        const lessonId = {{ $lesson->id }};
        
        if (confirm('Are you sure you want to remove this attachment?')) {
            fetch(`/admin/lessons/${lessonId}/attachments/${index}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Remove the item from DOM
                    this.closest('.list-group-item').remove();
                    
                    // Check if there are no more attachments
                    const container = document.getElementById('existing-attachments');
                    if (container && container.children.length === 0) {
                        container.closest('.mb-3').previousElementSibling.remove(); // Remove h5
                        container.closest('.mb-3').remove(); // Remove container
                    }
                    
                    alert('Attachment removed successfully');
                } else {
                    alert('Error removing attachment: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error removing attachment');
            });
        }
    });
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
</script>
@endpush
@endsection