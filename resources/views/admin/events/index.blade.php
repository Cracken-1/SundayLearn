@extends('admin.layout')

@section('title', 'Events Management - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Events Management</h1>
        <p class="text-muted">Manage upcoming events and holidays</p>
    </div>
    <div>
        <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
            <i class="fas fa-plus"></i> Add New Event
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
                        <p class="text-muted mb-1">Total Events</p>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-calendar fa-2x"></i>
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
                        <p class="text-muted mb-1">Upcoming</p>
                        <h3 class="mb-0">{{ $stats['upcoming'] }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-clock fa-2x"></i>
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
                        <h3 class="mb-0">{{ $stats['featured'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-star fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Events Table -->
<div class="card">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Days Until</th>
                        <th>Featured</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($events as $event)
                    <tr>
                        <td>
                            <i class="fas fa-{{ $event->icon }}" style="color: {{ $event->color }}"></i>
                            <strong>{{ $event->title }}</strong>
                        </td>
                        <td>{{ $event->event_date->format('M j, Y') }}</td>
                        <td><span class="badge bg-secondary">{{ ucfirst($event->event_type) }}</span></td>
                        <td>
                            @if($event->days_until >= 0)
                                <span class="badge bg-info">{{ $event->days_until }} days</span>
                            @else
                                <span class="badge bg-secondary">Past</span>
                            @endif
                        </td>
                        <td>
                            @if($event->is_featured)
                                <i class="fas fa-star text-warning"></i>
                            @else
                                <i class="far fa-star text-muted"></i>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-sm btn-primary">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-4">
                            <i class="fas fa-calendar fa-3x text-muted mb-3"></i>
                            <p class="text-muted">No events found</p>
                            <a href="{{ route('admin.events.create') }}" class="btn btn-primary">Create First Event</a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($events->hasPages())
        <div class="mt-3">
            {{ $events->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
