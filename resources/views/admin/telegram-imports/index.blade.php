@extends('admin.layout')

@section('title', 'Telegram Imports - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Telegram Imports</h1>
        <p class="text-muted">Manage content received from Telegram bot</p>
    </div>
    <div>
        <select class="form-select" onchange="window.location.href='?status=' + this.value">
            <option value="">All Status</option>
            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Processing</option>
            <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Completed</option>
            <option value="failed" {{ request('status') == 'failed' ? 'selected' : '' }}>Failed</option>
        </select>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-primary">{{ $stats['total'] }}</h4>
                <small class="text-muted">Total</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-warning">{{ $stats['pending'] }}</h4>
                <small class="text-muted">Pending</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-info">{{ $stats['processing'] }}</h4>
                <small class="text-muted">Processing</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-success">{{ $stats['completed'] }}</h4>
                <small class="text-muted">Completed</small>
            </div>
        </div>
    </div>
    <div class="col-md-2 col-sm-6 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <h4 class="text-danger">{{ $stats['failed'] }}</h4>
                <small class="text-muted">Failed</small>
            </div>
        </div>
    </div>
</div>

<!-- Imports Table -->
<div class="card">
    <div class="card-body">
        @if($imports->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Message ID</th>
                            <th>Media Type</th>
                            <th>Caption</th>
                            <th>Status</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($imports as $import)
                        <tr>
                            <td>{{ $import->id }}</td>
                            <td>{{ $import->telegram_message_id }}</td>
                            <td>
                                @if($import->media_type)
                                    <span class="badge bg-secondary">{{ ucfirst($import->media_type) }}</span>
                                @else
                                    <span class="text-muted">Text</span>
                                @endif
                            </td>
                            <td>{{ Str::limit($import->caption, 50) }}</td>
                            <td>
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
                            </td>
                            <td>{{ $import->created_at->format('M j, Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.telegram-imports.show', $import) }}" class="btn btn-sm btn-outline-primary">
                                    View
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <div class="mt-3">
                {{ $imports->links() }}
            </div>
        @else
            <div class="text-center py-5">
                <i class="fab fa-telegram fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No Telegram imports found</h5>
                <p class="text-muted">Imports from your Telegram bot will appear here once configured.</p>
                <a href="{{ route('admin.settings') }}" class="btn btn-primary">
                    <i class="fas fa-cog"></i> Configure Telegram Bot
                </a>
            </div>
        @endif
    </div>
</div>
@endsection