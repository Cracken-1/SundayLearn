@extends('admin.layout')

@section('title', 'Newsletter Subscribers - Admin')

@section('content')
<!-- Header Section -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Newsletter Subscribers</h1>
        <p class="text-muted">Manage email subscribers and export mailing lists</p>
    </div>
    <div>
        <a href="{{ route('admin.newsletters.export') }}" class="btn btn-success">
            <i class="fas fa-file-csv"></i> Export to CSV
        </a>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Total Subscribers</p>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="text-primary">
                        <i class="fas fa-users fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Subscribed</p>
                        <h3 class="mb-0 text-success">{{ $stats['subscribed'] }}</h3>
                    </div>
                    <div class="text-success">
                        <i class="fas fa-check-circle fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Unsubscribed</p>
                        <h3 class="mb-0 text-warning">{{ $stats['unsubscribed'] }}</h3>
                    </div>
                    <div class="text-warning">
                        <i class="fas fa-user-times fa-2x"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <p class="text-muted mb-1">Bounced</p>
                        <h3 class="mb-0 text-danger">{{ $stats['bounced'] }}</h3>
                    </div>
                    <div class="text-danger">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
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
                        <th style="width: 30%">Email</th>
                        <th style="width: 20%">Name</th>
                        <th style="width: 15%">Status</th>
                        <th style="width: 20%">Subscribed At</th>
                        <th style="width: 15%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($newsletters as $subscriber)
                    <tr>
                        <td>
                            <i class="fas fa-envelope text-muted me-2"></i>
                            <strong>{{ $subscriber->email }}</strong>
                        </td>
                        <td>{{ $subscriber->name ?? '-' }}</td>
                        <td>
                            @if($subscriber->status === 'subscribed')
                                <span class="badge bg-success">Subscribed</span>
                            @elseif($subscriber->status === 'unsubscribed')
                                <span class="badge bg-warning">Unsubscribed</span>
                            @elseif($subscriber->status === 'bounced')
                                <span class="badge bg-danger">Bounced</span>
                            @else
                                <span class="badge bg-secondary">{{ ucfirst($subscriber->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($subscriber->subscribed_at)
                                <small class="text-muted">
                                    {{ \Carbon\Carbon::parse($subscriber->subscribed_at)->format('M d, Y') }}
                                </small>
                            @else
                                <small class="text-muted">-</small>
                            @endif
                        </td>
                        <td>
                            <form action="{{ route('admin.newsletters.destroy', $subscriber) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this subscriber?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="fas fa-envelope-open fa-3x text-muted mb-3"></i>
                            <p class="text-muted mb-3">No newsletter subscribers yet.</p>
                            <p class="small text-muted">Subscribers will appear here when they sign up through the website.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($newsletters->hasPages())
        <div class="mt-3">
            {{ $newsletters->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Info Card -->
<div class="row mt-4">
    <div class="col-md-12">
        <div class="card border-info">
            <div class="card-body">
                <h6 class="card-title"><i class="fas fa-info-circle text-info"></i> About Newsletter Management</h6>
                <p class="card-text small mb-0">
                    Subscribers are automatically added when users sign up through the website newsletter form. 
                    You can export the list to CSV for use with email marketing services like Mailchimp, SendGrid, or Constant Contact. 
                    Subscribers can unsubscribe using the link in emails, which will update their status to "unsubscribed".
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
