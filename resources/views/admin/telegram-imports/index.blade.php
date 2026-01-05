@extends('admin.layout')

@section('title', 'Telegram Imports - Admin')

@push('styles')
<style>
    .progress-bar-container {
        display: none;
        margin-top: 1rem;
    }
</style>
@endpush

@section('content')
@if($setup_required)
    <div class="text-center py-5">
        <i class="fab fa-telegram fa-4x text-muted mb-3"></i>
        <h5 class="text-muted">Welcome to Telegram Imports!</h5>
        <p class="text-muted">To get started, you need to configure your Telegram bot.</p>
        <div class="card mb-4">
            <div class="card-header">
                Telegram Settings
            </div>
            <div class="card-body">
                <form action="{{ route('admin.settings.telegram') }}" method="POST">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="telegram_bot_token" class="form-label">Bot Token</label>
                            <input type="password" class="form-control" id="telegram_bot_token" name="telegram_bot_token" placeholder="Enter your Telegram Bot Token">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="telegram_channel_id" class="form-label">Channel ID</label>
                            <input type="text" class="form-control" id="telegram_channel_id" name="telegram_channel_id" placeholder="Enter your Telegram Channel ID">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <label for="telegram_webhook_url" class="form-label">Webhook URL</label>
                            <input type="text" class="form-control" id="telegram_webhook_url" name="telegram_webhook_url" placeholder="Enter your Telegram Webhook URL">
                            <small class="form-text text-muted">This is the URL that Telegram will send updates to.</small>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                </form>
            </div>
        </div>
    </div>
@else
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Telegram Imports</h1>
            <p class="text-muted">Manage content received from Telegram bot</p>
        </div>
        <div>
            <form action="{{ route('admin.telegram-imports.import') }}" method="POST" class="d-inline-block" id="import-form">
                @csrf
                <button type="submit" class="btn btn-primary" id="import-button">
                    <i class="fas fa-sync"></i> Run Importer
                </button>
            </form>
        </div>
    </div>

    <div class="progress-bar-container" id="progress-bar-container">
        <div class="progress">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 100%"></div>
        </div>
    </div>

    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    @if(session('info'))
    <div class="alert alert-info">
        {{ session('info') }}
    </div>
    @endif

    <!-- Settings Card -->
    <div class="card mb-4">
        <div class="card-header">
            Telegram Settings
        </div>
        <div class="card-body">
            <form action="{{ route('admin.settings.telegram') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="telegram_bot_token" class="form-label">Bot Token</label>
                        <input type="password" class="form-control" id="telegram_bot_token" name="telegram_bot_token" value="{{ $settings['telegram_bot_token'] ?? '' }}" placeholder="Enter your Telegram Bot Token">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="telegram_channel_id" class="form-label">Channel ID</label>
                        <input type="text" class="form-control" id="telegram_channel_id" name="telegram_channel_id" value="{{ $settings['telegram_channel_id'] ?? '' }}" placeholder="Enter your Telegram Channel ID">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12 mb-3">
                        <label for="telegram_webhook_url" class="form-label">Webhook URL</label>
                        <input type="text" class="form-control" id="telegram_webhook_url" name="telegram_webhook_url" value="{{ $settings['telegram_webhook_url'] ?? '' }}" placeholder="Enter your Telegram Webhook URL">
                        <small class="form-text text-muted">This is the URL that Telegram will send updates to.</small>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary">Save Settings</button>
            </form>
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
                    <a href="#" class="btn btn-primary" onclick="document.getElementById('telegram_bot_token').focus();">
                        <i class="fas fa-cog"></i> Configure Telegram Bot
                    </a>
                </div>
            @endif
        </div>
    </div>
@endif
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const importForm = document.getElementById('import-form');
        const importButton = document.getElementById('import-button');
        const progressBarContainer = document.getElementById('progress-bar-container');

        if (importForm) {
            importForm.addEventListener('submit', function() {
                if (importButton) {
                    importButton.disabled = true;
                    importButton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Importing...';
                }
                if (progressBarContainer) {
                    progressBarContainer.style.display = 'block';
                }
            });
        }
    });
</script>
@endpush
