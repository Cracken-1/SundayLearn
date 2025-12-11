@extends('admin.layout')

@section('title', 'Lessons Management - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Lessons Management</h1>
        <p class="text-muted">Manage Sunday school lessons and content</p>
    </div>
    <div>
        <a href="{{ route('admin.lessons.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> New Lesson
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <i class="fas fa-book fa-2x mb-2"></i>
                <h4>{{ $stats['total'] }}</h4>
                <small>Total Lessons</small>
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
        <div class="card" style="background: linear-gradient(135deg, #CD853F 0%, #DAA520 100%); color: white;" >
            <div class="card-body text-center">
                <i class="fas fa-calendar fa-2x mb-2"></i>
                <h4>{{ $stats['this_month'] }}</h4>
                <small>This Month</small>
            </div>
        </div>
    </div>
</div>

<!-- Lessons Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Age Group</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Created</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($lessons as $lesson)
                    <tr>
                        <td>{{ $lesson->id }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <i class="fas fa-book text-primary me-2"></i>
                                <strong>{{ $lesson->title }}</strong>
                            </div>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $lesson->age_group }}</span>
                        </td>
                        <td>
                            @if($lesson->status === 'published')
                                <span class="badge bg-success">Published</span>
                            @else
                                <span class="badge bg-secondary">Draft</span>
                            @endif
                        </td>
                        <td>{{ $lesson->views_count }}</td>
                        <td>{{ $lesson->created_at->format('M j, Y') }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.lessons.show', $lesson->id) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.lessons.edit', $lesson->id) }}" class="btn btn-sm btn-outline-secondary">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form method="POST" action="{{ route('admin.lessons.destroy', $lesson->id) }}" class="d-inline">
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