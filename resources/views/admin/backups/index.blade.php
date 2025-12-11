@extends('admin.layout')

@section('title', 'Backup Management - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Backup Management</h1>
        <p class="text-muted">Create and manage system backups</p>
    </div>
    <div>
        <form method="POST" action="{{ route('admin.backups.create') }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-primary" 
                    @if(!$zipAvailable) disabled title="ZipArchive extension not available" @endif
                    onclick="return confirm('Create a new backup? This may take a few minutes.')">
                <i class="fas fa-plus"></i> Create Backup
            </button>
        </form>
        @if(!$zipAvailable)
        <small class="text-danger d-block mt-1">
            <i class="fas fa-exclamation-circle"></i> Enable ZipArchive extension first
        </small>
        @endif
    </div>
</div>

<!-- Backup Stats -->
<div class="row mb-4">
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-archive fa-2x text-primary mb-2"></i>
                <h4 class="text-primary">{{ $stats['total_backups'] }}</h4>
                <small class="text-muted">Total Backups</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-hdd fa-2x text-info mb-2"></i>
                <h4 class="text-info">{{ $stats['total_size'] }}</h4>
                <small class="text-muted">Total Size</small>
            </div>
        </div>
    </div>
    <div class="col-md-4 mb-3">
        <div class="card text-center">
            <div class="card-body">
                <i class="fas fa-clock fa-2x text-success mb-2"></i>
                <h4 class="text-success">{{ $stats['last_backup'] ? \Carbon\Carbon::parse($stats['last_backup'])->diffForHumans() : 'Never' }}</h4>
                <small class="text-muted">Last Backup</small>
            </div>
        </div>
    </div>
</div>

<!-- ZipArchive Warning -->
@if(!$zipAvailable)
<div class="alert alert-danger mb-4">
    <h5 class="alert-heading"><i class="fas fa-exclamation-triangle"></i> ZipArchive Extension Not Available</h5>
    <p class="mb-2">The PHP ZipArchive extension is required to create backups but is not currently enabled.</p>
    <p class="mb-2"><strong>To enable it:</strong></p>
    <ol class="mb-2">
        <li>Open your <code>php.ini</code> file (for XAMPP: <code>C:\xampp\php\php.ini</code>)</li>
        <li>Find the line <code>;extension=zip</code></li>
        <li>Remove the semicolon to uncomment it: <code>extension=zip</code></li>
        <li>Save the file and restart Apache</li>
    </ol>
    <p class="mb-0"><small class="text-muted">After enabling, refresh this page to create backups.</small></p>
</div>
@endif

<!-- Backup Instructions -->
<div class="alert alert-info mb-4">
    <h5 class="alert-heading"><i class="fas fa-info-circle"></i> Backup Information</h5>
    <p class="mb-2">Backups include:</p>
    <ul class="mb-2">
        <li>Complete database export (structure and data)</li>
        <li>Uploaded files and media</li>
        <li>Configuration files (.env, composer files)</li>
    </ul>
    <p class="mb-0"><strong>Note:</strong> Store backups in a secure location. Regular backups are recommended before major updates.</p>
</div>

<!-- Backups List -->
<div class="card">
    <div class="card-header">
        <h5 class="mb-0">Available Backups</h5>
    </div>
    <div class="card-body">
        @if(count($backups) > 0)
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>Backup Name</th>
                            <th>Size</th>
                            <th>Created</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $backup)
                        <tr>
                            <td>
                                <i class="fas fa-file-archive text-primary me-2"></i>
                                {{ $backup['name'] }}
                            </td>
                            <td>{{ $backup['size'] }}</td>
                            <td>
                                {{ \Carbon\Carbon::parse($backup['created_at'])->format('M j, Y H:i') }}
                                <small class="text-muted d-block">
                                    {{ \Carbon\Carbon::parse($backup['created_at'])->diffForHumans() }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.backups.download', $backup['name']) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-download"></i> Download
                                    </a>
                                    <form method="POST" action="{{ route('admin.backups.destroy', $backup['name']) }}" 
                                          class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger" 
                                                onclick="return confirm('Are you sure you want to delete this backup?')">
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
        @else
            <div class="text-center py-5">
                <i class="fas fa-archive fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">No backups found</h5>
                <p class="text-muted">Create your first backup to secure your data.</p>
                <form method="POST" action="{{ route('admin.backups.create') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-primary" onclick="return confirm('Create a new backup? This may take a few minutes.')">
                        <i class="fas fa-plus"></i> Create First Backup
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

<!-- Backup Best Practices -->
<div class="row mt-4">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Backup Best Practices</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled">
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Create backups before major updates</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Store backups in multiple locations</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Test backup restoration periodically</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Keep at least 3 recent backups</li>
                    <li class="mb-2"><i class="fas fa-check text-success me-2"></i> Schedule regular automated backups</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Restoration Instructions</h5>
            </div>
            <div class="card-body">
                <ol class="small">
                    <li>Download the backup file</li>
                    <li>Extract the ZIP archive</li>
                    <li>Import database.sql to your database</li>
                    <li>Replace .env file with backed up version</li>
                    <li>Restore storage files to storage/app/public/</li>
                    <li>Run <code>composer install</code></li>
                    <li>Run <code>php artisan migrate</code></li>
                    <li>Clear cache: <code>php artisan cache:clear</code></li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection