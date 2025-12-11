@extends('admin.layout')

@section('title', 'View Lesson - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ $lesson->title }}</h1>
        <p class="text-muted">Lesson Details</p>
    </div>
    <div>
        <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Lesson
        </a>
        <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Lessons
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5>Lesson Content</h5>
                <p>{{ $lesson->content }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">Lesson Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $lesson->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Age Group:</strong></td>
                        <td><span class="badge bg-info">{{ $lesson->age_group }}</span></td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($lesson->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Views:</strong></td>
                        <td>{{ $lesson->views_count }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $lesson->created_at->format('M j, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        @if(!empty($lesson->attachments) && count($lesson->attachments) > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Attachments ({{ count($lesson->attachments) }})</h5>
            </div>
            <div class="card-body">
                <div class="list-group">
                    @foreach($lesson->attachments as $index => $attachment)
                    <div class="list-group-item d-flex justify-content-between align-items-center">
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
                            <small>
                                <strong>{{ $attachment['name'] ?? 'Attachment' }}</strong>
                                <br>
                                <span class="text-muted">
                                    {{ strtoupper($attachment['type'] ?? 'FILE') }}
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </span>
                            </small>
                        </div>
                        <div>
                            <a href="{{ $attachment['url'] ?? '#' }}" class="btn btn-sm btn-primary" download>
                                <i class="fas fa-download"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection