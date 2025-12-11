@extends('admin.layout')

@section('title', 'Edit Blog Post - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit Blog Post</h1>
        <p class="text-muted">Update blog post content</p>
    </div>
    <div>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Blog Posts
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.blogs.update', $blog->id) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Post Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $blog->title }}" required>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="author" class="form-label">Author</label>
                                <input type="text" class="form-control" id="author" name="author" value="{{ $blog->author }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="status" class="form-label">Status</label>
                                <select class="form-select" id="status" name="status" required>
                                    <option value="draft" {{ $blog->status === 'draft' ? 'selected' : '' }}>Draft</option>
                                    <option value="published" {{ $blog->status === 'published' ? 'selected' : '' }}>Published</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="content" class="form-label">Post Content</label>
                        <textarea class="form-control" id="content" name="content" rows="10">{{ $blog->content }}</textarea>
                    </div>
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Post
                        </button>
                        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
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
                <h5 class="mb-0">Post Info</h5>
            </div>
            <div class="card-body">
                <p><strong>ID:</strong> {{ $blog->id }}</p>
                <p><strong>Created:</strong> {{ $blog->created_at ?? 'N/A' }}</p>
                <p><strong>Last Updated:</strong> {{ $blog->updated_at ?? 'N/A' }}</p>
            </div>
        </div>
    </div>
</div>
@endsection