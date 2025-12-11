@extends('admin.layout')

@section('title', 'View Blog Post - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">{{ $blog->title }}</h1>
        <p class="text-muted">Blog Post Details</p>
    </div>
    <div>
        <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-primary">
            <i class="fas fa-edit"></i> Edit Post
        </a>
        <a href="{{ route('admin.blogs.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Blog Posts
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <h5>Post Content</h5>
                <p>{{ $blog->content }}</p>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Post Info</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>ID:</strong></td>
                        <td>{{ $blog->id }}</td>
                    </tr>
                    <tr>
                        <td><strong>Author:</strong></td>
                        <td>{{ $blog->author }}</td>
                    </tr>
                    <tr>
                        <td><strong>Status:</strong></td>
                        <td>
                            @if($blog->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <td><strong>Views:</strong></td>
                        <td>{{ $blog->views_count }}</td>
                    </tr>
                    <tr>
                        <td><strong>Created:</strong></td>
                        <td>{{ $blog->created_at->format('M j, Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection