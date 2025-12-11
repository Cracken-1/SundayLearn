@extends('admin.layout')

@section('title', 'Blog Management - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Blog Management</h1>
        <p class="text-muted">Manage teaching tips and blog articles</p>
    </div>
    <div>
        <a href="{{ route('admin.blogs.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Blog Post
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <i class="fas fa-blog fa-2x mb-2"></i>
                <h4>{{ $stats['total'] }}</h4>
                <small>Total Posts</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white text-center">
            <div class="card-body">
                <i class="fas fa-check-circle fa-2x mb-2"></i>
                <h4>{{ $stats['published'] }}</h4>
                <small>Published</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-secondary text-white text-center">
            <div class="card-body">
                <i class="fas fa-edit fa-2x mb-2"></i>
                <h4>{{ $stats['drafts'] }}</h4>
                <small>Drafts</small>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card" style="background: linear-gradient(135deg, #CD853F 0%, #DAA520 100%); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-calendar fa-2x mb-2"></i>
                <h4>{{ $stats['this_month'] }}</h4>
                <small>This Month</small>
            </div>
        </div>
    </div>
</div>

<!-- Blog Posts Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blogs as $blog)
                    <tr>
                        <td>{{ $blog->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-blog text-primary me-2"></i>
                                <strong>{{ $blog->title }}</strong>
                            </div>
                        </td>
                        <td>{{ $blog->author }}</td>
                        <td>
                            @if($blog->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $blog->views_count }}</td>
                        <td>{{ $blog->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.blogs.show', $blog->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.blogs.edit', $blog->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.blogs.destroy', $blog->id) }}" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" 
                                            onclick="return confirm('Are you sure?')">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection