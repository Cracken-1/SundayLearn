@extends('admin.layout')

@section('title', 'Edit Event - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit Event</h1>
        <p class="text-muted">Update event details</p>
    </div>
    <div>
        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Back to Events
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-body">
                <form method="POST" action="{{ route('admin.events.update', $event) }}">
                    @csrf
                    @method('PUT')
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Event Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $event->title) }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description', $event->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="event_date" class="form-label">Event Date *</label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                       id="event_date" name="event_date" value="{{ old('event_date', $event->event_date->format('Y-m-d')) }}" required>
                                @error('event_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="event_type" class="form-label">Event Type *</label>
                                <select class="form-select @error('event_type') is-invalid @enderror" 
                                        id="event_type" name="event_type" required>
                                    <option value="">Select Type</option>
                                    <option value="holiday" {{ old('event_type', $event->event_type) == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    <option value="special" {{ old('event_type', $event->event_type) == 'special' ? 'selected' : '' }}>Special Event</option>
                                    <option value="seasonal" {{ old('event_type', $event->event_type) == 'seasonal' ? 'selected' : '' }}>Seasonal</option>
                                    <option value="other" {{ old('event_type', $event->event_type) == 'other' ? 'selected' : '' }}>Other</option>
                                </select>
                                @error('event_type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="color" class="form-label">Color</label>
                                <input type="color" class="form-control form-control-color @error('color') is-invalid @enderror" 
                                       id="color" name="color" value="{{ old('color', $event->color) }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon (Font Awesome)</label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" name="icon" value="{{ old('icon', $event->icon) }}" 
                                       placeholder="e.g., gifts, cross, calendar-star">
                                <small class="text-muted">Enter Font Awesome icon name without 'fa-'</small>
                                @error('icon')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="display_order" class="form-label">Display Order</label>
                                <input type="number" class="form-control @error('display_order') is-invalid @enderror" 
                                       id="display_order" name="display_order" value="{{ old('display_order', $event->display_order) }}">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                           {{ old('is_featured', $event->is_featured) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_featured">
                                        <strong>Featured Event</strong>
                                        <br><small class="text-muted">Display prominently on website</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <hr class="my-4">
                    
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Update Event
                        </button>
                        <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Event Info</h5>
            </div>
            <div class="card-body">
                <p><strong>Created:</strong> {{ $event->created_at->format('M j, Y H:i') }}</p>
                <p><strong>Last Updated:</strong> {{ $event->updated_at->format('M j, Y H:i') }}</p>
                <p><strong>Days Until:</strong> 
                    @if($event->days_until >= 0)
                        <span class="badge bg-info">{{ $event->days_until }} days</span>
                    @else
                        <span class="badge bg-secondary">Past event</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>
@endsection
