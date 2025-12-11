@extends('admin.layout')

@section('title', 'Content Management - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Content Management</h1>
        <p class="text-muted">Overview and management of all platform content</p>
    </div>
    <div>
        <button class="btn btn-warning" onclick="confirmCleanup()">
            <i class="fas fa-broom"></i> Cleanup
        </button>
    </div>
</div>

<!-- Content Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card bg-primary text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-book-open fa-3x mb-3"></i>
                <h3>{{ $stats['total_lessons'] }}</h3>
                <p class="mb-1">Total Lessons</p>
                <small>{{ $stats['published_lessons'] }} published, {{ $stats['draft_lessons'] }} drafts</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-info text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-blog fa-3x mb-3"></i>
                <h3>{{ $stats['total_blogs'] }}</h3>
                <p class="mb-1">Blog Posts</p>
                <small>{{ $stats['published_blogs'] }} published, {{ $stats['draft_blogs'] }} drafts</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-warning text-white h-100">
            <div class="card-body text-center">
                <i class="fab fa-telegram fa-3x mb-3"></i>
                <h3>{{ $stats['telegram_imports'] }}</h3>
                <p class="mb-1">Telegram Imports</p>
                <small>{{ $stats['pending_imports'] }} pending processing</small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-hdd fa-3x mb-3"></i>
                <h3>{{ $storageStats['total'] }}</h3>
                <p class="mb-1">Storage Used</p>
                <small>Images: {{ $storageStats['images'] }}, Docs: {{ $storageStats['documents'] }}</small>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Content -->
    <div class="col-lg-8">
        <div class="card mb-4">
            <div class="card-header">
                <ul class="nav nav-tabs card-header-tabs" id="contentTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="lessons-tab" data-bs-toggle="tab" data-bs-target="#lessons" type="button" role="tab">
                            Recent Lessons
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="blogs-tab" data-bs-toggle="tab" data-bs-target="#blogs" type="button" role="tab">
                            Recent Blogs
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="imports-tab" data-bs-toggle="tab" data-bs-target="#imports" type="button" role="tab">
                            Recent Imports
                        </button>
                    </li>
                </ul>
            </div>
            <div class="card-body">
                <div class="tab-content" id="contentTabsContent">
                    <!-- Lessons Tab -->
                    <div class="tab-pane fade show active" id="lessons" role="tabpanel">
                        @if($recentLessons->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentLessons as $lesson)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $lesson->title }}</h6>
                                        <small class="text-muted">
                                            Age: {{ $lesson->age_group }} | 
                                            Created: {{ $lesson->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div>
                                        @if($lesson->published_at)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                        <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.lessons.index') }}" class="btn btn-primary">View All Lessons</a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-book-open fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No lessons found</p>
                                <a href="{{ route('admin.lessons.create') }}" class="btn btn-primary">Create First Lesson</a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Blogs Tab -->
                    <div class="tab-pane fade" id="blogs" role="tabpanel">
                        @if($recentBlogs->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentBlogs as $blog)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">{{ $blog->title }}</h6>
                                        <small class="text-muted">
                                            Created: {{ $blog->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div>
                                        @if($blog->published_at)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-warning">Draft</span>
                                        @endif
                                        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-outline-primary ms-2">
                                            Edit
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.blogs.index') }}" class="btn btn-primary">View All Blogs</a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fas fa-blog fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No blog posts found</p>
                                <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">Create First Post</a>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Imports Tab -->
                    <div class="tab-pane fade" id="imports" role="tabpanel">
                        @if($recentImports->count() > 0)
                            <div class="list-group list-group-flush">
                                @foreach($recentImports as $import)
                                <div class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1">Import #{{ $import->id }}</h6>
                                        <small class="text-muted">
                                            Type: {{ ucfirst($import->media_type ?? 'Text') }} | 
                                            {{ $import->created_at->diffForHumans() }}
                                        </small>
                                    </div>
                                    <div>
                                        @php
                                            $statusClasses = [
                                                'pending' => 'bg-warning',
                                                'processing' => 'bg-info',
                                                'completed' => 'bg-success',
                                                'failed' => 'bg-danger',
                                            ];
                                        @endphp
                                        <span class="badge {{ $statusClasses[$import->processing_status] ?? 'bg-secondary' }}">
                                            {{ ucfirst($import->processing_status) }}
                                        </span>
                                        <a href="{{ route('admin.telegram-imports.show', $import) }}" class="btn btn-sm btn-outline-primary ms-2">
                                            View
                                        </a>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <div class="text-center mt-3">
                                <a href="{{ route('admin.telegram-imports.index') }}" class="btn btn-primary">View All Imports</a>
                            </div>
                        @else
                            <div class="text-center py-4">
                                <i class="fab fa-telegram fa-3x text-muted mb-3"></i>
                                <p class="text-muted">No telegram imports found</p>
                                <a href="{{ route('admin.settings') }}" class="btn btn-primary">Configure Telegram Bot</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Age Group Distribution -->
    <div class="col-lg-4">
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Content by Age Group</h5>
            </div>
            <div class="card-body">
                @if(count($ageGroupStats) > 0)
                    @foreach($ageGroupStats as $ageGroup => $count)
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span>{{ $ageGroup ?: 'Not specified' }}</span>
                        <span class="badge bg-primary">{{ $count }}</span>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-3">
                        <i class="fas fa-chart-pie fa-2x text-muted mb-2"></i>
                        <p class="text-muted mb-0">No age group data available</p>
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.lessons.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> New Lesson
                    </a>
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-info">
                        <i class="fas fa-plus"></i> New Blog Post
                    </a>
                    <a href="{{ route('admin.telegram-imports.index') }}" class="btn btn-warning">
                        <i class="fab fa-telegram"></i> Process Imports
                    </a>
                    <button class="btn btn-secondary" onclick="confirmCleanup()">
                        <i class="fas fa-broom"></i> Cleanup Content
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Cleanup Confirmation Modal -->
<div class="modal fade" id="cleanupModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Confirm Cleanup</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>This will remove:</p>
                <ul>
                    <li>Failed telegram imports older than 30 days</li>
                    <li>Orphaned files not referenced in database</li>
                    <li>Temporary files and cache</li>
                </ul>
                <p class="text-warning"><strong>This action cannot be undone.</strong></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <form method="POST" action="{{ route('admin.content.cleanup') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-warning">Proceed with Cleanup</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function confirmCleanup() {
    var cleanupModal = new bootstrap.Modal(document.getElementById('cleanupModal'));
    cleanupModal.show();
}
</script>
@endpush