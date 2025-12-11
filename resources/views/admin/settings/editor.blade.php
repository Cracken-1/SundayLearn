@extends('admin.layout')

@section('title', 'Editor Settings - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">
            <i class="fas fa-edit me-2" style="color: var(--secondary-color);"></i>
            Editor Settings
        </h1>
        <p class="text-muted">Manage your content creation preferences</p>
    </div>
    <div>
        <span class="badge bg-info">
            <i class="fas fa-user-edit me-1"></i>Editor Panel
        </span>
    </div>
</div>

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
    
    <!-- Editor Statistics -->
    <div class="col-lg-6 mb-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Your Content Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            @php
                                try {
                                    $userLessons = \App\Models\Lesson::where('created_by', $currentUser->id)->count();
                                } catch (\Exception $e) {
                                    $userLessons = 0;
                                }
                                try {
                                    $userBlogs = \App\Models\BlogPost::where('created_by', $currentUser->id)->count();
                                } catch (\Exception $e) {
                                    $userBlogs = 0;
                                }
                                try {
                                    $userResources = class_exists(\App\Models\Resource::class) ? \App\Models\Resource::where('created_by', $currentUser->id)->count() : 0;
                                } catch (\Exception $e) {
                                    $userResources = 0;
                                }
                                try {
                                    $userTips = class_exists(\App\Models\TeachingTip::class) ? \App\Models\TeachingTip::where('created_by', $currentUser->id)->count() : 0;
                                } catch (\Exception $e) {
                                    $userTips = 0;
                                }
                            @endphp
                            <h3 class="text-primary mb-1">{{ $userLessons }}</h3>
                            <small class="text-muted">Lessons Created</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h3 class="text-success mb-1">{{ $userBlogs }}</h3>
                            <small class="text-muted">Blog Posts</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h3 class="text-info mb-1">{{ $userResources }}</h3>
                            <small class="text-muted">Resources</small>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="p-3 bg-light rounded">
                            <h3 class="text-warning mb-1">{{ $userTips }}</h3>
                            <small class="text-muted">Teaching Tips</small>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-center">
                    <h6 class="text-muted mb-2">Member Since</h6>
                    <p class="mb-0">{{ $currentUser->created_at ? $currentUser->created_at->format('F j, Y') : 'Unknown' }}</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions for Editors -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0"><i class="fas fa-bolt me-2"></i>Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.lessons.create') }}" class="btn btn-outline-primary w-100">
                            <i class="fas fa-plus"></i><br>
                            New Lesson
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.blogs.create') }}" class="btn btn-outline-success w-100">
                            <i class="fas fa-pen"></i><br>
                            New Blog Post
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.resources.create') }}" class="btn btn-outline-info w-100">
                            <i class="fas fa-file-upload"></i><br>
                            Upload Resource
                        </a>
                    </div>
                    <div class="col-md-3 mb-3">
                        <a href="{{ route('admin.teaching-tips.create') }}" class="btn btn-outline-warning w-100">
                            <i class="fas fa-lightbulb"></i><br>
                            New Teaching Tip
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
@endsection