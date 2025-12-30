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
                <form method="POST" action="{{ route('admin.blogs.update', $blog->id) }}" enctype="multipart/form-data">
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

                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Featured Image</label>
                        <input type="file" class="form-control" id="featured_image" name="featured_image">
                        @if ($blog->featured_image)
                            <div class="mt-2">
                                <img src="{{ asset('storage/' . $blog->featured_image) }}" alt="Featured Image" style="max-width: 200px;">
                            </div>
                        @endif
                    </div>

                    <div class="mb-3">
                        <label for="video_url" class="form-label">Video</label>
                        <input type="file" class="form-control" id="video_url" name="video_url">
                        @if ($blog->video_url)
                            <div class="mt-2">
                                <video width="320" height="240" controls>
                                    <source src="{{ asset('storage/' . $blog->video_url) }}" type="video/mp4">
                                    Your browser does not support the video tag.
                                </video>
                            </div>
                        @endif
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