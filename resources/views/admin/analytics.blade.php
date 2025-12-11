@extends('admin.layout')

@section('title', 'Analytics - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Analytics Dashboard</h1>
        <p class="text-muted">Platform performance and usage statistics</p>
    </div>
    <div>
        <select class="form-select" style="width: auto;">
            <option>Last 30 Days</option>
            <option>Last 7 Days</option>
            <option>This Month</option>
            <option>Last Month</option>
        </select>
    </div>
</div>

<!-- Overview Stats -->
<div class="row mb-4">
    <div class="col-md-3 mb-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <i class="fas fa-eye fa-2x mb-2"></i>
                <h4>{{ number_format($analyticsStats['total_page_views']) }}</h4>
                <small>Total Page Views</small>
                <div class="text-info small mt-1">
                    <i class="fas fa-calendar"></i> Today: {{ number_format($analyticsStats['today_page_views']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card bg-success text-white text-center">
            <div class="card-body">
                <i class="fas fa-book-open fa-2x mb-2"></i>
                <h4>{{ number_format($analyticsStats['total_lesson_views']) }}</h4>
                <small>Lesson Views</small>
                <div class="text-light small mt-1">
                    <i class="fas fa-calendar"></i> Today: {{ number_format($analyticsStats['today_lesson_views']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card" style="background: linear-gradient(135deg, #CD853F 0%, #DAA520 100%); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-download fa-2x mb-2"></i>
                <h4>{{ number_format($analyticsStats['total_downloads']) }}</h4>
                <small>Downloads</small>
                <div class="text-light small mt-1">
                    <i class="fas fa-calendar"></i> Today: {{ number_format($analyticsStats['today_downloads']) }}
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3 mb-3">
        <div class="card" style="background: linear-gradient(135deg, #B8860B 0%, #DAA520 100%); color: white;">
            <div class="card-body text-center">
                <i class="fas fa-search fa-2x mb-2"></i>
                <h4>{{ number_format($analyticsStats['total_searches']) }}</h4>
                <small>Total Searches</small>
                <div class="text-light small mt-1">
                    <i class="fas fa-chart-line"></i> All time
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Popular Content -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
                @if($recentActivity->count() > 0)
                    <div class="list-group list-group-flush">
                        @foreach($recentActivity->take(5) as $activity)
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-1">{{ $activity->title }}</h6>
                                <small class="text-muted">
                                    {{ ucfirst($activity->type) }} • {{ $activity->created_at->diffForHumans() }}
                                </small>
                            </div>
                            <span class="badge bg-primary rounded-pill">{{ $activity->views }} views</span>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No recent activity data available</p>
                        <small class="text-muted">Content views will appear here once tracking is active</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <!-- Age Group Distribution -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Content by Age Group</h5>
            </div>
            <div class="card-body">
                @if(count($ageGroupStats) > 0)
                    @php
                        $total = array_sum($ageGroupStats);
                    @endphp
                    @foreach($ageGroupStats as $ageGroup => $count)
                        @php
                            $percentage = $total > 0 ? round(($count / $total) * 100, 1) : 0;
                        @endphp
                        <div class="mb-3">
                            <div class="d-flex justify-content-between">
                                <span>Ages {{ $ageGroup }}</span>
                                <span>{{ $percentage }}% ({{ $count }})</span>
                            </div>
                            <div class="progress">
                                <div class="progress-bar" style="width: {{ $percentage }}%; background: linear-gradient(135deg, #8B4513 0%, #CD853F 100%);"></div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-pie fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No age group data available</p>
                        <small class="text-muted">Create lessons with age groups to see distribution</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Content Statistics -->
<div class="row mb-4">
    <div class="col-lg-{{ auth()->guard('admin')->user()->canAccessIntegrations() ? '6' : '12' }}">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Content Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-{{ auth()->guard('admin')->user()->canAccessIntegrations() ? '6' : '3' }} mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="text-primary mb-1">{{ $contentStats['total_lessons'] }}</h4>
                            <small class="text-muted">Total Lessons</small>
                            <div class="text-success small mt-1">{{ $contentStats['published_lessons'] }} Published</div>
                        </div>
                    </div>
                    <div class="col-{{ auth()->guard('admin')->user()->canAccessIntegrations() ? '6' : '3' }} mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="text-info mb-1">{{ $contentStats['total_blogs'] }}</h4>
                            <small class="text-muted">Blog Posts</small>
                            <div class="text-success small mt-1">{{ $contentStats['published_blogs'] }} Published</div>
                        </div>
                    </div>
                    <div class="col-{{ auth()->guard('admin')->user()->canAccessIntegrations() ? '6' : '3' }} mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="text-warning mb-1">{{ $contentStats['total_events'] }}</h4>
                            <small class="text-muted">Events</small>
                        </div>
                    </div>
                    <div class="col-{{ auth()->guard('admin')->user()->canAccessIntegrations() ? '6' : '3' }} mb-3">
                        <div class="p-3 bg-light rounded">
                            <h4 class="text-secondary mb-1">{{ $contentStats['total_resources'] }}</h4>
                            <small class="text-muted">Resources</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    @if(auth()->guard('admin')->user()->canAccessIntegrations())
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Telegram Import Status</h5>
            </div>
            <div class="card-body">
                @if($telegramStats['total_imports'] > 0)
                    <div class="row text-center">
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-warning bg-opacity-10 rounded">
                                <h4 class="text-warning mb-1">{{ $telegramStats['pending'] }}</h4>
                                <small class="text-muted">Pending</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-info bg-opacity-10 rounded">
                                <h4 class="text-info mb-1">{{ $telegramStats['processing'] }}</h4>
                                <small class="text-muted">Processing</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-success bg-opacity-10 rounded">
                                <h4 class="text-success mb-1">{{ $telegramStats['completed'] }}</h4>
                                <small class="text-muted">Completed</small>
                            </div>
                        </div>
                        <div class="col-6 mb-3">
                            <div class="p-3 bg-danger bg-opacity-10 rounded">
                                <h4 class="text-danger mb-1">{{ $telegramStats['failed'] }}</h4>
                                <small class="text-muted">Failed</small>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fab fa-telegram fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No Telegram imports yet</p>
                        <small class="text-muted">Configure Telegram bot to start importing content</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Content Performance</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-primary bg-opacity-10 rounded">
                            <h4 class="text-primary mb-1">{{ $contentStats['lessons_with_video'] ?? 0 }}</h4>
                            <small class="text-muted">Lessons with Video</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-info bg-opacity-10 rounded">
                            <h4 class="text-info mb-1">{{ $contentStats['lessons_with_audio'] ?? 0 }}</h4>
                            <small class="text-muted">Lessons with Audio</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-success bg-opacity-10 rounded">
                            <h4 class="text-success mb-1">{{ number_format($analyticsStats['month_lesson_views']) }}</h4>
                            <small class="text-muted">Monthly Lesson Views</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-warning bg-opacity-10 rounded">
                            <h4 class="text-warning mb-1">{{ number_format($analyticsStats['month_downloads']) }}</h4>
                            <small class="text-muted">Monthly Downloads</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<!-- Weekly Analytics Chart -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Weekly Performance</h5>
            </div>
            <div class="card-body">
                <div class="row text-center mb-4">
                    <div class="col-md-4">
                        <div class="p-3">
                            <h4 class="text-primary">{{ number_format($analyticsStats['week_page_views']) }}</h4>
                            <small class="text-muted">Page Views This Week</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <h4 class="text-success">{{ number_format($analyticsStats['week_lesson_views']) }}</h4>
                            <small class="text-muted">Lesson Views This Week</small>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="p-3">
                            <h4 class="text-warning">{{ number_format($analyticsStats['week_downloads']) }}</h4>
                            <small class="text-muted">Downloads This Week</small>
                        </div>
                    </div>
                </div>
                
                @if(count($dailyAnalytics) > 0)
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Page Views</th>
                                    <th>Lesson Views</th>
                                    <th>Downloads</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach(array_slice($dailyAnalytics, -7, 7, true) as $date => $data)
                                <tr>
                                    <td>{{ $date }}</td>
                                    <td>{{ number_format($data['page_views']) }}</td>
                                    <td>{{ number_format($data['lesson_views']) }}</td>
                                    <td>{{ number_format($data['downloads']) }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="fas fa-chart-line fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No daily analytics data available</p>
                        <small class="text-muted">Analytics tracking will populate this section</small>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection