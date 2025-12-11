@extends('admin.layout')

@section('title', 'Teaching Tips Management - Admin')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Teaching Tips Management</h1>
        <p class="text-muted">Manage rotating teaching tips displayed on the lessons page</p>
    </div>
    <div>
        <a href="{{ route('admin.teaching-tips.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Tip
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Tips</p>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-lightbulb fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Active Tips</p>
                        <h3 class="mb-0 text-success">{{ $stats['active'] }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Inactive Tips</p>
                        <h3 class="mb-0 text-warning">{{ $stats['inactive'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-pause-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th style="width: 5%">#</th>
                        <th style="width: 25%">Title</th>
                        <th style="width: 35%">Content</th>
                        <th style="width: 10%">Category</th>
                        <th style="width: 5%">Icon</th>
                        <th style="width: 10%">Status</th>
                        <th style="width: 10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($teachingTips as $tip)
                    <tr>
                        <td>{{ $tip->display_order }}</td>
                        <td><strong>{{ $tip->title }}</strong></td>
                        <td>
                            <small class="text-muted">
                                {{ Str::limit($tip->content, 80) }}
                            </small>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ $tip->category }}</span>
                        </td>
                        <td class="text-center">
                            <i class="fas fa-{{ $tip->icon }} text-primary"></i>
                        </td>
                        <td>
                            @if($tip->is_active)
                                <span class="badge bg-success">Active</span>
                            @else
                                <span class="badge bg-warning">Inactive</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.teaching-tips.edit', $tip) }}" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.teaching-tips.destroy', $tip) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this teaching tip?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="fas fa-lightbulb fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">No teaching tips found. Create your first tip to help teachers!</p>
                            <a href="{{ route('admin.teaching-tips.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Teaching Tip
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($teachingTips->hasPages())
        <div class="mt-3">
            {{ $teachingTips->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
