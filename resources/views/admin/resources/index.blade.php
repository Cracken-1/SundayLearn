@extends('admin.layout')

@section('title', 'Resources Management - Admin')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Resources Management</h1>
        <p class="text-muted">Manage downloadable resources for teachers</p>
    </div>
    <div>
        <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Resource
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
                        <p class="text-muted mb-1">Total Resources</p>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-file-alt fa-2x"></i>
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
                        <p class="text-muted mb-1">Featured</p>
                        <h3 class="mb-0 text-warning">{{ $stats['featured'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-star fa-2x"></i>
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
                        <p class="text-muted mb-1">Total Downloads</p>
                        <h3 class="mb-0 text-success">{{ number_format($stats['total_downloads']) }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-download fa-2x"></i>
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
                        <th style="width: 25%">Title</th>
                        <th style="width: 15%">Type</th>
                        <th style="width: 10%">File Type</th>
                        <th style="width: 10%">Size</th>
                        <th style="width: 10%">Age Group</th>
                        <th style="width: 10%">Downloads</th>
                        <th style="width: 10%">Featured</th>
                        <th style="width: 10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($resources as $resource)
                    <tr>
                        <td>
                            <strong>{{ $resource->title }}</strong>
                            @if($resource->thumbnail)
                                <br><small class="text-muted"><i class="fas fa-image"></i> Has thumbnail</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge bg-info">{{ ucwords(str_replace('_', ' ', $resource->type)) }}</span>
                        </td>
                        <td>
                            <span class="badge bg-secondary">{{ strtoupper($resource->file_type) }}</span>
                        </td>
                        <td>
                            <small class="text-muted">{{ $resource->file_size_formatted }}</small>
                        </td>
                        <td>
                            <span class="badge bg-light text-dark">{{ $resource->age_group }}</span>
                        </td>
                        <td>
                            <span class="badge bg-success">{{ number_format($resource->downloads_count) }}</span>
                        </td>
                        <td>
                            @if($resource->is_featured)
                                <i class="fas fa-star text-warning" title="Featured"></i>
                            @else
                                <i class="far fa-star text-muted" title="Not Featured"></i>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.resources.edit', $resource) }}" class="btn btn-sm btn-primary" title="Edit">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.resources.destroy', $resource) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure? This will delete the file permanently.')">
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
                        <td colspan="8" class="text-center py-5">
                            <i class="fas fa-file-alt fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">No resources found. Upload your first resource!</p>
                            <a href="{{ route('admin.resources.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus"></i> Create First Resource
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($resources->hasPages())
        <div class="mt-3">
            {{ $resources->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
