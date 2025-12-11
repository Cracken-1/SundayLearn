@extends('admin.layout')

@section('title', 'Admin Dashboard - SundayLearn')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        @php
            $now = now();
            $hour = $now->hour;
            
            // More precise time-based greetings
            if ($hour >= 5 && $hour < 12) {
                $greeting = 'Good morning';
                $icon = 'sun';
            } elseif ($hour >= 12 && $hour < 17) {
                $greeting = 'Good afternoon';
                $icon = 'cloud-sun';
            } elseif ($hour >= 17 && $hour < 21) {
                $greeting = 'Good evening';
                $icon = 'sunset';
            } else {
                $greeting = 'Good night';
                $icon = 'moon';
            }
        @endphp
        <h1 class="h3 mb-0">
            <i class="fas fa-{{ $icon }} me-2" style="color: var(--secondary-color);"></i>
            {{ $greeting }}, {{ $currentUser->name }}
        </h1>
        <p class="text-muted">{{ ucfirst(str_replace('_', ' ', $currentUser->role)) }} Panel</p>
        <div class="d-flex align-items-center gap-3 mt-2">
            <div class="d-flex align-items-center">
                <div class="me-2" style="width: 8px; height: 8px; background: #28a745; border-radius: 50%;"></div>
                <small class="text-muted">
                    <i class="fas fa-clock me-1"></i>
                    Last login: {{ $currentUser->getLastLoginFormatted() }}
                </small>
            </div>
            @if($currentUser->isSuperAdmin())
                <span class="badge" style="background: linear-gradient(135deg, #8B4513, #DAA520); color: white;">
                    <i class="fas fa-crown me-1"></i>Full Access
                </span>
            @elseif($currentUser->isAdmin())
                <span class="badge" style="background: linear-gradient(135deg, #DAA520, #B8860B); color: white;">
                    <i class="fas fa-shield-alt me-1"></i>Administrator
                </span>
            @else
                <span class="badge" style="background: linear-gradient(135deg, #CD853F, #A0522D); color: white;">
                    <i class="fas fa-edit me-1"></i>Content Editor
                </span>
            @endif
        </div>
    </div>
    <div class="text-end">
        <div class="d-flex align-items-center justify-content-end gap-2 mb-2">
            <div class="dropdown">
                <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                    <i class="fas fa-bolt me-1"></i>Quick Actions
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('admin.lessons.create') }}">
                        <i class="fas fa-plus me-2"></i>New Lesson
                    </a></li>
                    <li><a class="dropdown-item" href="{{ route('admin.blogs.create') }}">
                        <i class="fas fa-pen me-2"></i>New Blog Post
                    </a></li>
                    @if($currentUser->canManageUsers())
                    <li><a class="dropdown-item" href="{{ route('admin.users.create') }}">
                        <i class="fas fa-user-plus me-2"></i>New User
                    </a></li>
                    @endif
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="{{ route('admin.settings') }}">
                        <i class="fas fa-cog me-2"></i>Settings
                    </a></li>
                </ul>
            </div>
            <span class="badge bg-success">
                <i class="fas fa-check-circle me-1"></i>Online
            </span>
        </div>
        <div class="d-flex flex-wrap align-items-center gap-2">
            <small class="text-muted">
                <i class="fas fa-calendar me-1"></i>{{ now()->format('l, F j, Y') }}
            </small>
            <small class="text-muted">
                <i class="fas fa-clock me-1"></i>
                <span id="live-time">{{ now()->format('g:i:s A') }}</span>
                <span class="mx-1" style="color: var(--secondary-color); font-weight: 500;">{{ now()->format('T') }}</span>
            </small>
            @if($currentUser->isLastLoginToday())
                <small class="text-muted">
                    <i class="fas fa-stopwatch me-1"></i>Session: {{ $currentUser->getSessionDuration() }}
                </small>
            @endif
            <small class="text-muted">
                <i class="fas fa-server me-1"></i>Server Time
            </small>
        </div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card h-100">
            <div class="card-body text-center">
                <i class="fas fa-book-open fa-3x mb-3"></i>
                <h2 class="mb-2">{{ $stats['total_lessons'] }}</h2>
                <p class="mb-0">Total Lessons</p>
                <small class="text-muted d-block mt-2">
                    <i class="fas fa-check-circle text-success"></i> {{ $stats['published_lessons'] }} Published
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #CD853F 0%, #DAA520 100%);">
            <div class="card-body text-center">
                <i class="fas fa-blog fa-3x mb-3"></i>
                <h2 class="mb-2">{{ $stats['total_blogs'] }}</h2>
                <p class="mb-0">Blog Posts</p>
                <small class="d-block mt-2" style="opacity: 0.9;">
                    <i class="fas fa-check-circle"></i> {{ $stats['published_blogs'] }} Published
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #B8860B 0%, #DAA520 100%);">
            <div class="card-body text-center">
                <i class="fas fa-eye fa-3x mb-3"></i>
                <h2 class="mb-2">{{ number_format($stats['total_views']) }}</h2>
                <p class="mb-0">Total Views</p>
                <small class="d-block mt-2" style="opacity: 0.9;">
                    <i class="fas fa-chart-line"></i> Across all lessons
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white h-100">
            <div class="card-body text-center">
                <i class="fas fa-calendar-week fa-3x mb-3"></i>
                <h2 class="mb-2">{{ $stats['lessons_this_week'] + $stats['blogs_this_week'] }}</h2>
                <p class="mb-0">This Week</p>
                <small class="d-block mt-2">
                    <i class="fas fa-book"></i> {{ $stats['lessons_this_week'] }} Lessons, 
                    <i class="fas fa-blog"></i> {{ $stats['blogs_this_week'] }} Blogs
                </small>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);">
            <div class="card-body text-center">
                <i class="fas fa-calendar-alt fa-3x mb-3"></i>
                <h2 class="mb-2">{{ $stats['total_events'] }}</h2>
                <p class="mb-0">Events</p>
                <small class="d-block mt-2" style="opacity: 0.9;">
                    <i class="fas fa-star"></i> {{ $stats['upcoming_events'] }} Upcoming
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);">
            <div class="card-body text-center">
                <i class="fas fa-lightbulb fa-3x mb-3"></i>
                <h2 class="mb-2">{{ $stats['total_teaching_tips'] }}</h2>
                <p class="mb-0">Teaching Tips</p>
                <small class="d-block mt-2" style="opacity: 0.9;">
                    <i class="fas fa-check-circle"></i> {{ $stats['active_teaching_tips'] }} Active
                </small>
            </div>
        </div>
    </div>
    
    <div class="col-md-3 mb-3">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #343a40 0%, #23272b 100%);">
            <div class="card-body text-center">
                <i class="fas fa-download fa-3x mb-3"></i>
                <h2 class="mb-2">{{ $stats['total_resources'] }}</h2>
                <p class="mb-0">Resources</p>
                <small class="d-block mt-2" style="opacity: 0.9;">
                    <i class="fas fa-arrow-down"></i> {{ number_format($stats['total_downloads']) }} Downloads
                </small>
            </div>
        </div>
    </div>
    
    @if($currentUser->canManageUsers())
    <div class="col-md-3 mb-3">
        <div class="card text-white h-100" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">
            <div class="card-body text-center">
                <i class="fas fa-envelope fa-3x mb-3"></i>
                <h2 class="mb-2">{{ $stats['total_newsletters'] }}</h2>
                <p class="mb-0">Subscribers</p>
                <small class="d-block mt-2" style="opacity: 0.9;">
                    <i class="fas fa-check-circle"></i> {{ $stats['subscribed_newsletters'] }} Active
                </small>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Management Cards -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Platform Management</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @if($currentUser->canAccessIntegrations())
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <i class="fab fa-telegram fa-3x text-primary mb-3"></i>
                                <h6>Telegram Imports</h6>
                                <p class="text-muted small">Manage bot content</p>
                                <span class="badge bg-secondary">{{ $stats['telegram_imports']['total'] }} imports</span>
                                <div class="mt-3">
                                    <a href="{{ route('admin.telegram-imports.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right"></i> Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-success">
                            <div class="card-body text-center">
                                <i class="fas fa-book-open fa-3x text-success mb-3"></i>
                                <h6>Lessons</h6>
                                <p class="text-muted small">Manage content</p>
                                <span class="badge bg-secondary">{{ $stats['total_lessons'] }} lessons</span>
                                <div class="mt-3">
                                    <a href="{{ route('admin.lessons.index') }}" class="btn btn-success btn-sm">
                                        <i class="fas fa-arrow-right"></i> Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-info">
                            <div class="card-body text-center">
                                <i class="fas fa-blog fa-3x text-info mb-3"></i>
                                <h6>Blog Posts</h6>
                                <p class="text-muted small">Teaching tips</p>
                                <span class="badge bg-secondary">{{ $stats['total_blogs'] }} articles</span>
                                <div class="mt-3">
                                    <a href="{{ route('admin.blogs.index') }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-arrow-right"></i> Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-warning">
                            <div class="card-body text-center">
                                <i class="fas fa-chart-bar fa-3x text-warning mb-3"></i>
                                <h6>Analytics</h6>
                                <p class="text-muted small">View reports</p>
                                <span class="badge bg-secondary">Reports</span>
                                <div class="mt-3">
                                    <a href="{{ route('admin.analytics') }}" class="btn btn-warning btn-sm">
                                        <i class="fas fa-arrow-right"></i> View
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Second Row of Management Cards -->
                <div class="row">
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-danger">
                            <div class="card-body text-center">
                                <i class="fas fa-calendar-alt fa-3x text-danger mb-3"></i>
                                <h6>Events</h6>
                                <p class="text-muted small">Holidays & special days</p>
                                <span class="badge bg-secondary">{{ $stats['total_events'] }} events</span>
                                <div class="mt-3">
                                    <a href="{{ route('admin.events.index') }}" class="btn btn-danger btn-sm">
                                        <i class="fas fa-arrow-right"></i> Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-secondary">
                            <div class="card-body text-center">
                                <i class="fas fa-lightbulb fa-3x text-secondary mb-3"></i>
                                <h6>Teaching Tips</h6>
                                <p class="text-muted small">Helpful advice</p>
                                <span class="badge bg-secondary">{{ $stats['total_teaching_tips'] }} tips</span>
                                <div class="mt-3">
                                    <a href="{{ route('admin.teaching-tips.index') }}" class="btn btn-secondary btn-sm">
                                        <i class="fas fa-arrow-right"></i> Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-dark">
                            <div class="card-body text-center">
                                <i class="fas fa-download fa-3x text-dark mb-3"></i>
                                <h6>Resources</h6>
                                <p class="text-muted small">Downloadable materials</p>
                                <span class="badge bg-secondary">{{ $stats['total_resources'] }} files</span>
                                <div class="mt-3">
                                    <a href="{{ route('admin.resources.index') }}" class="btn btn-dark btn-sm">
                                        <i class="fas fa-arrow-right"></i> Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-lg-3 mb-3">
                        <div class="card h-100 border-primary">
                            <div class="card-body text-center">
                                <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
                                <h6>Newsletter</h6>
                                <p class="text-muted small">Email subscribers</p>
                                <span class="badge bg-secondary">{{ $stats['subscribed_newsletters'] }} subscribers</span>
                                <div class="mt-3">
                                    <a href="{{ route('admin.newsletters.index') }}" class="btn btn-primary btn-sm">
                                        <i class="fas fa-arrow-right"></i> Manage
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- System Status -->
@if($systemStatus && $currentUser->canAccessSystemSettings())
<div class="row mb-4">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">System Status</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($systemStatus as $component => $status)
                    <div class="col-md-6 mb-3">
                        <div class="d-flex justify-content-between align-items-center p-3 bg-light rounded">
                            <div>
                                <strong>{{ ucfirst(str_replace('_', ' ', $component)) }}</strong>
                                <div class="text-muted small">{{ $status['details'] ?? '' }}</div>
                            </div>
                            <span class="badge 
                                @if($status['status'] === 'online') bg-success
                                @elseif($status['status'] === 'warning') bg-warning
                                @else bg-danger
                                @endif">
                                @if($status['status'] === 'online')
                                    <i class="fas fa-check-circle"></i> Online
                                @elseif($status['status'] === 'warning')
                                    <i class="fas fa-exclamation-triangle"></i> Warning
                                @else
                                    <i class="fas fa-times-circle"></i> Error
                                @endif
                            </span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
@endif

    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    @if($currentUser->canAccessIntegrations())
                    <a href="{{ route('admin.telegram-imports.index') }}" class="btn btn-primary">
                        <i class="fab fa-telegram"></i> Telegram Imports
                    </a>
                    @endif
                    <a href="{{ route('admin.lessons.create') }}" class="btn btn-success">
                        <i class="fas fa-plus"></i> New Lesson
                    </a>
                    <a href="{{ route('admin.blogs.create') }}" class="btn btn-info">
                        <i class="fas fa-plus"></i> New Blog Post
                    </a>
                    <a href="{{ route('admin.content.index') }}" class="btn btn-info">
                        <i class="fas fa-layer-group"></i> Content Manager
                    </a>
                    @if($currentUser->canManageUsers())
                    <a href="{{ route('admin.users.index') }}" class="btn btn-warning">
                        <i class="fas fa-users"></i> Users
                    </a>
                    @endif
                    @if($currentUser->canManageBackups())
                    <a href="{{ route('admin.backups.index') }}" class="btn btn-secondary">
                        <i class="fas fa-archive"></i> Backups
                    </a>
                    @endif
                    @if($currentUser->canAccessSystemSettings())
                    <a href="{{ route('admin.settings') }}" class="btn btn-secondary">
                        <i class="fas fa-cog"></i> Settings
                    </a>
                    @endif
                    <a href="{{ route('home') }}" class="btn btn-outline-primary" target="_blank">
                        <i class="fas fa-external-link-alt"></i> View Website
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                @if($currentUser->canAccessIntegrations() && count($stats['recent_activity']) > 0)
                    @foreach($stats['recent_activity'] as $activity)
                    <div class="d-flex align-items-center mb-3 p-2 bg-light rounded">
                        <div class="me-3">
                            <i class="fab fa-telegram text-primary"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="fw-bold">New Import</div>
                            <small class="text-muted">{{ $activity->created_at->diffForHumans() }}</small>
                        </div>
                    </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent activity</p>
                        @if($currentUser->canAccessIntegrations())
                            <small class="text-muted">Telegram imports will appear here</small>
                        @else
                            <small class="text-muted">Content activity will appear here</small>
                        @endif
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Telegram Import Status -->
@if($currentUser->canAccessIntegrations() && $stats['telegram_imports']['total'] > 0)
<div class="row mb-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Telegram Import Status</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3">
                        <div class="p-3">
                            <h4 class="text-warning">{{ $stats['telegram_imports']['pending'] }}</h4>
                            <small class="text-muted">Pending</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h4 class="text-info">{{ $stats['telegram_imports']['processing'] }}</h4>
                            <small class="text-muted">Processing</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h4 class="text-success">{{ $stats['telegram_imports']['completed'] }}</h4>
                            <small class="text-muted">Completed</small>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="p-3">
                            <h4 class="text-danger">{{ $stats['telegram_imports']['failed'] }}</h4>
                            <small class="text-muted">Failed</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

@endsection

@push('scripts')
<script>
// Live clock and greeting update
function updateTimeAndGreeting() {
    const now = new Date();
    const hour = now.getHours();
    
    // Update clock
    const options = {
        hour: 'numeric',
        minute: '2-digit',
        second: '2-digit',
        hour12: true,
        timeZone: Intl.DateTimeFormat().resolvedOptions().timeZone
    };
    const timeString = now.toLocaleTimeString('en-US', options);
    
    const liveTimeElement = document.getElementById('live-time');
    if (liveTimeElement) {
        liveTimeElement.textContent = timeString;
    }
    
    // Update greeting and icon based on current time
    let greeting, icon;
    if (hour >= 5 && hour < 12) {
        greeting = 'Good morning';
        icon = 'sun';
    } else if (hour >= 12 && hour < 17) {
        greeting = 'Good afternoon';
        icon = 'cloud-sun';
    } else if (hour >= 17 && hour < 21) {
        greeting = 'Good evening';
        icon = 'sunset';
    } else {
        greeting = 'Good night';
        icon = 'moon';
    }
    
    // Update greeting text and icon
    const greetingElement = document.querySelector('h1.h3 i');
    const greetingText = document.querySelector('h1.h3');
    
    if (greetingElement && greetingText) {
        greetingElement.className = `fas fa-${icon} me-2`;
        // Update only the greeting part, keeping the user name
        const userName = '{{ $currentUser->name }}';
        greetingText.innerHTML = `<i class="fas fa-${icon} me-2" style="color: var(--secondary-color);"></i>${greeting}, ${userName}`;
    }
}

// Update time and greeting every second
setInterval(updateTimeAndGreeting, 1000);

// Initial call
updateTimeAndGreeting();

// Auto-refresh system status every 30 seconds
setInterval(function() {
    // You can add AJAX call here to refresh system status
    console.log('System status check...');
}, 30000);

// Add tooltips to status badges
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endpush