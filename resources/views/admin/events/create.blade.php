@extends('admin.layout')

@section('title', 'Create Event - Admin')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Create New Event</h1>
        <p class="text-muted">Add a new event or holiday</p>
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
                <form method="POST" action="{{ route('admin.events.store') }}">
                    @csrf
                    
                    <div class="mb-3">
                        <label for="title" class="form-label">Event Title *</label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required>
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control @error('description') is-invalid @enderror" 
                                  id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="event_date" class="form-label">Event Date *</label>
                                <input type="date" class="form-control @error('event_date') is-invalid @enderror" 
                                       id="event_date" name="event_date" value="{{ old('event_date') }}" required>
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
                                    <option value="holiday" {{ old('event_type') == 'holiday' ? 'selected' : '' }}>Holiday</option>
                                    <option value="special" {{ old('event_type') == 'special' ? 'selected' : '' }}>Special Event</option>
                                    <option value="seasonal" {{ old('event_type') == 'seasonal' ? 'selected' : '' }}>Seasonal</option>
                                    <option value="other" {{ old('event_type') == 'other' ? 'selected' : '' }}>Other</option>
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
                                       id="color" name="color" value="{{ old('color', '#dc3545') }}">
                                @error('color')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="icon" class="form-label">Icon (Font Awesome)</label>
                                <input type="text" class="form-control @error('icon') is-invalid @enderror" 
                                       id="icon" name="icon" value="{{ old('icon', 'calendar') }}" 
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
                                       id="display_order" name="display_order" value="{{ old('display_order', 0) }}">
                                @error('display_order')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <div class="form-check mt-4">
                                    <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured" 
                                           {{ old('is_featured') ? 'checked' : '' }}>
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
                            <i class="fas fa-save"></i> Create Event
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
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0"><i class="fas fa-info-circle"></i> Event Guidelines</h5>
            </div>
            <div class="card-body">
                <h6 class="text-primary">Common Events</h6>
                <ul class="small mb-3">
                    <li>Christmas - December 25</li>
                    <li>Easter - Variable date</li>
                    <li>Pentecost - 50 days after Easter</li>
                    <li>Advent - 4 Sundays before Christmas</li>
                    <li>Good Friday - Friday before Easter</li>
                </ul>
                
                <h6 class="text-primary">Icon Examples</h6>
                <ul class="small mb-3">
                    <li><i class="fas fa-gifts"></i> gifts - Christmas</li>
                    <li><i class="fas fa-cross"></i> cross - Easter</li>
                    <li><i class="fas fa-dove"></i> dove - Pentecost</li>
                    <li><i class="fas fa-heart"></i> heart - Valentine's</li>
                    <li><i class="fas fa-calendar-star"></i> calendar-star - Special</li>
                </ul>
                
                <h6 class="text-primary">Color Suggestions</h6>
                <ul class="small">
                    <li><span style="color: #dc3545;">●</span> Red - Christmas, Pentecost</li>
                    <li><span style="color: #28a745;">●</span> Green - Regular season</li>
                    <li><span style="color: #6f42c1;">●</span> Purple - Advent, Lent</li>
                    <li><span style="color: #ffc107;">●</span> Gold - Easter</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection
