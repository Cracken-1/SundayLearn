@extends('admin.layout')

@section('title', 'System Settings - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-cogs me-2" style="color: var(--secondary-color);"></i>
            System Settings
        </h1>
        <p class="text-muted">Configure your SundayLearn platform and personal preferences</p>
    </div>
    <div>
        <span class="badge bg-danger">
            <i class="fas fa-shield-alt me-1"></i>Administrator Only
        </span>
    </div>
</div>

<!-- Navigation Tabs -->
<ul class="nav nav-tabs mb-4" id="settingsTabs" role="tablist">
    <li class="nav-item" role="presentation">
        <button class="nav-link active" id="system-tab" data-bs-toggle="tab" data-bs-target="#system" type="button" role="tab">
            <i class="fas fa-server me-2"></i>System Configuration
        </button>
    </li>
    <li class="nav-item" role="presentation">
        <button class="nav-link" id="editor-tab" data-bs-toggle="tab" data-bs-target="#editor" type="button" role="tab">
            <i class="fas fa-edit me-2"></i>Editor Preferences
        </button>
    </li>
</ul>

<div class="tab-content" id="settingsTabContent">
    <!-- System Settings Tab -->
    <div class="tab-pane fade show active" id="system" role="tabpanel">

<div class="row">
    <!-- Telegram Configuration -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fab fa-telegram me-2"></i>Telegram Bot Configuration</h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('admin.settings.telegram') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="telegram_bot_token" class="form-label">Bot Token</label>
                        <input type="text" class="form-control" id="telegram_bot_token" name="telegram_bot_token" 
                               value="{{ config('telegram.bot_token') ? '***' . substr(config('telegram.bot_token'), -8) : '' }}"
                               placeholder="123456789:ABCdefGHIjklMNOpqrsTUVwxyz">
                        <div class="form-text">Get this from @BotFather on Telegram</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telegram_channel_id" class="form-label">Channel ID</label>
                        <input type="text" class="form-control" id="telegram_channel_id" name="telegram_channel_id" 
                               value="{{ config('telegram.channel_id') }}"
                               placeholder="@your_channel or -100123456789">
                        <div class="form-text">Your Telegram channel username or ID</div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="telegram_webhook_url" class="form-label">Webhook URL</label>
                        <input type="url" class="form-control" id="telegram_webhook_url" name="telegram_webhook_url" 
                               value="{{ config('telegram.webhook_url') }}"
                               placeholder="https://yourdomain.com/api/telegram/webhook">
                        <div class="form-text">Must be HTTPS for Telegram webhooks</div>
                    </div>
                    
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Telegram Settings
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- System Information -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-info-circle me-2"></i>System Information</h5>
            </div>
            <div class="card-body">
                <table class="table table-sm">
                    <tr>
                        <td><strong>PHP Version:</strong></td>
                        <td>{{ PHP_VERSION }}</td>
                    </tr>
                    <tr>
                        <td><strong>Laravel Version:</strong></td>
                        <td>{{ app()->version() }}</td>
                    </tr>
                    <tr>
                        <td><strong>Environment:</strong></td>
                        <td>{{ config('app.env') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Debug Mode:</strong></td>
                        <td>{{ config('app.debug') ? 'Enabled' : 'Disabled' }}</td>
                    </tr>
                    <tr>
                        <td><strong>Database:</strong></td>
                        <td>{{ config('database.default') }}</td>
                    </tr>
                    <tr>
                        <td><strong>Cache Driver:</strong></td>
                        <td>{{ config('cache.default') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- System Actions -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-tools me-2"></i>System Actions</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> These actions affect the entire system. Use with caution.
                </div>
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <form method="POST" action="{{ route('admin.settings.clear-cache') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="fas fa-broom"></i><br>
                                Clear Cache
                            </button>
                        </form>
                    </div>
                    <div class="col-md-3 mb-3">
                        <form method="POST" action="{{ route('admin.settings.optimize') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="fas fa-rocket"></i><br>
                                Optimize App
                            </button>
                        </form>
                    </div>
                    <div class="col-md-3 mb-3">
                        <form method="POST" action="{{ route('admin.settings.migrate') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-info w-100" 
                                    onclick="return confirm('Run database migrations?')">
                                <i class="fas fa-database"></i><br>
                                Run Migrations
                            </button>
                        </form>
                    </div>
                    <div class="col-md-3 mb-3">
                        <form method="POST" action="{{ route('admin.settings.cleanup') }}">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger w-100" 
                                    onclick="return confirm('Clean up storage files?')">
                                <i class="fas fa-trash"></i><br>
                                Cleanup Storage
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
    </div>
    <!-- End System Settings Tab -->

    <!-- Editor Preferences Tab -->
    <div class="tab-pane fade" id="editor" role="tabpanel">
        <div class="row">
            <!-- Content Preferences -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-sliders-h me-2"></i>Content Preferences</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.settings.update') }}">
                            @csrf
                            
                            <div class="mb-3">
                                <label for="default_lesson_duration" class="form-label">Default Lesson Duration</label>
                                <select class="form-control" id="default_lesson_duration" name="default_lesson_duration">
                                    <option value="30 minutes">30 minutes</option>
                                    <option value="45 minutes" selected>45 minutes</option>
                                    <option value="60 minutes">60 minutes</option>
                                    <option value="90 minutes">90 minutes</option>
                                </select>
                                <div class="form-text">Default duration for new lessons</div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="default_age_group" class="form-label">Default Age Group</label>
                                <select class="form-control" id="default_age_group" name="default_age_group">
                                    <option value="3-5">Ages 3-5</option>
                                    <option value="6-8" selected>Ages 6-8</option>
                                    <option value="9-12">Ages 9-12</option>
                                    <option value="teen">Teen</option>
                                    <option value="adult">Adult</option>
                                </select>
                                <div class="form-text">Default age group for new content</div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="auto_save_drafts" name="auto_save_drafts" checked>
                                    <label class="form-check-label" for="auto_save_drafts">
                                        Auto-save drafts while editing
                                    </label>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" id="show_preview" name="show_preview" checked>
                                    <label class="form-check-label" for="show_preview">
                                        Show live preview while editing
                                    </label>
                                </div>
                            </div>
                            
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save"></i> Save Preferences
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            
            <!-- Admin Statistics -->
            <div class="col-lg-6 mb-4">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>System Content Statistics</h5>
                    </div>
                    <div class="card-body">
                        @php
                            // Use global stats as in AdminDashboardController
                            $totalLessons = \App\Models\Lesson::count();
                            $totalBlogs = \App\Models\BlogPost::count();
                            $totalResources = class_exists(\App\Models\Resource::class) ? \App\Models\Resource::count() : 0;
                            $totalTips = class_exists(\App\Models\TeachingTip::class) ? \App\Models\TeachingTip::count() : 0;
                        @endphp
                        <div class="row text-center">
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-primary mb-1">{{ $totalLessons }}</h3>
                                    <small class="text-muted">Total Lessons</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-success mb-1">{{ $totalBlogs }}</h3>
                                    <small class="text-muted">Total Blog Posts</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-info mb-1">{{ $totalResources }}</h3>
                                    <small class="text-muted">Total Resources</small>
                                </div>
                            </div>
                            <div class="col-6 mb-3">
                                <div class="p-3 bg-light rounded">
                                    <h3 class="text-warning mb-1">{{ $totalTips }}</h3>
                                    <small class="text-muted">Total Teaching Tips</small>
                                </div>
                            </div>
                        </div>
                        
                        <hr>
                        
                        <div class="text-center">
                            <h6 class="text-muted mb-2">Administrator Since</h6>
                            <p class="mb-0">{{ $currentUser->created_at ? $currentUser->created_at->format('F j, Y') : 'Unknown' }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions for Admins -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <a href="{{ route('admin.lessons.create') }}" class="btn btn-outline-primary w-100">
                                    <i class="fas fa-plus"></i><br>
                                    New Lesson
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="{{ route('admin.blogs.create') }}" class="btn btn-outline-success w-100">
                                    <i class="fas fa-pen"></i><br>
                                    New Blog Post
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="{{ route('admin.resources.create') }}" class="btn btn-outline-info w-100">
                                    <i class="fas fa-file-upload"></i><br>
                                    Upload Resource
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="{{ route('admin.teaching-tips.create') }}" class="btn btn-outline-warning w-100">
                                    <i class="fas fa-lightbulb"></i><br>
                                    New Teaching Tip
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="{{ route('admin.users.create') }}" class="btn btn-outline-secondary w-100">
                                    <i class="fas fa-user-plus"></i><br>
                                    New User
                                </a>
                            </div>
                            <div class="col-md-2 mb-3">
                                <a href="{{ route('admin.backups.index') }}" class="btn btn-outline-dark w-100">
                                    <i class="fas fa-shield-alt"></i><br>
                                    Backups
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Content Guidelines -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0"><i class="fas fa-book-open me-2"></i>Content Guidelines</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <h6><i class="fas fa-check-circle text-success me-2"></i>Best Practices</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Use clear, age-appropriate language</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Include interactive activities</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Add relevant scripture references</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Provide takeaway messages</li>
                                </ul>
                            </div>
                            <div class="col-md-6">
                                <h6><i class="fas fa-info-circle text-info me-2"></i>Content Tips</h6>
                                <ul class="list-unstyled">
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Save drafts frequently</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Use the preview feature</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Add engaging images</li>
                                    <li><i class="fas fa-chevron-right text-muted me-2"></i>Test with your target age group</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Editor Preferences Tab -->
</div>

@push('scripts')
<script>
// Handle tab switching
document.addEventListener('DOMContentLoaded', function() {
    // If there's a hash in the URL, activate that tab
    if (window.location.hash) {
        const tabId = window.location.hash.substring(1);
        const tabButton = document.getElementById(tabId + '-tab');
        if (tabButton) {
            const tab = new bootstrap.Tab(tabButton);
            tab.show();
        }
    }
    
    // Update URL hash when tab changes
    const tabButtons = document.querySelectorAll('[data-bs-toggle="tab"]');
    tabButtons.forEach(button => {
        button.addEventListener('shown.bs.tab', function(e) {
            const targetId = e.target.getAttribute('data-bs-target').substring(1);
            window.location.hash = targetId;
        });
    });
});
</script>
@endpush

@endsection