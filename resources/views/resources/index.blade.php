@extends('layouts.app')

@section('title', 'Resources - SundayLearn')

@section('content')
<section class="page-header">
    <div class="container">
        <h1><i class="fas fa-download"></i> Teaching Resources</h1>
        <p>Free downloadable materials to enhance your Sunday school lessons</p>
    </div>
</section>

<section style="padding: 2rem 0;">
    <div class="container">
        <!-- Search and Filters -->
        <div style="background: var(--background-light); padding: 2rem; border-radius: 15px; margin-bottom: 3rem;">
            <form method="GET" action="{{ route('resources.index') }}">
                <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; margin-bottom: 1.5rem;">
                    <input type="text" name="search" placeholder="Search resources..." 
                           value="{{ request('search') }}"
                           style="padding: 1rem; border: 2px solid var(--border-color); border-radius: 10px; font-size: 1rem;">
                    <button type="submit" class="btn btn-primary" style="padding: 1rem 2rem;">
                        <i class="fas fa-search"></i> Search
                    </button>
                </div>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                    <select name="type" style="padding: 0.75rem; border: 2px solid var(--border-color); border-radius: 8px;">
                        <option value="">All Types</option>
                        @foreach($types as $type)
                            <option value="{{ $type->type }}" {{ request('type') == $type->type ? 'selected' : '' }}>
                                {{ ucfirst($type->type) }} ({{ $type->count }})
                            </option>
                        @endforeach
                    </select>
                    
                    <select name="age_group" style="padding: 0.75rem; border: 2px solid var(--border-color); border-radius: 8px;">
                        <option value="">All Ages</option>
                        @foreach($ageGroups as $ageGroup)
                            <option value="{{ $ageGroup->age_group }}" {{ request('age_group') == $ageGroup->age_group ? 'selected' : '' }}>
                                {{ $ageGroup->age_group }} ({{ $ageGroup->count }})
                            </option>
                        @endforeach
                    </select>
                    
                    <button type="submit" class="btn btn-secondary">
                        <i class="fas fa-filter"></i> Apply Filters
                    </button>
                    
                    @if(request()->hasAny(['search', 'type', 'age_group', 'category']))
                        <a href="{{ route('resources.index') }}" class="btn btn-outline">
                            <i class="fas fa-times"></i> Clear
                        </a>
                    @endif
                </div>
            </form>
        </div>

        @if($featuredResources->count() > 0)
        <!-- Featured Resources -->
        <div style="margin-bottom: 3rem;">
            <h2 style="margin-bottom: 2rem;"><i class="fas fa-star"></i> Featured Resources</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 2rem;">
                @foreach($featuredResources as $resource)
                <div class="resource-card featured">
                    <div class="resource-icon">
                        <i class="fas fa-{{ $resource->getTypeIcon() }}"></i>
                    </div>
                    <div class="resource-info">
                        <h3>{{ $resource->title }}</h3>
                        <p>{{ $resource->description }}</p>
                        <div class="resource-meta">
                            <span><i class="fas fa-tag"></i> {{ ucfirst($resource->type) }}</span>
                            @if($resource->age_group)
                                <span><i class="fas fa-users"></i> {{ $resource->age_group }}</span>
                            @endif
                            <span><i class="fas fa-download"></i> {{ $resource->downloads }} downloads</span>
                        </div>
                        <a href="{{ route('resources.download', $resource->id) }}" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">
                            <i class="fas fa-download"></i> Download
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Resources List -->
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
            <h2>All Resources ({{ $resources->total() }})</h2>
            <div style="display: flex; gap: 1rem;">
                <select onchange="window.location.href=this.value" style="padding: 0.5rem; border: 2px solid var(--border-color); border-radius: 8px;">
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'newest']) }}" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest First</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'popular']) }}" {{ request('sort') == 'popular' ? 'selected' : '' }}>Most Popular</option>
                    <option value="{{ request()->fullUrlWithQuery(['sort' => 'title']) }}" {{ request('sort') == 'title' ? 'selected' : '' }}>A-Z</option>
                </select>
            </div>
        </div>

        @if($resources->count() > 0)
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem; margin-bottom: 3rem;">
            @foreach($groupedResources as $group)
                @if($group['type'] === 'lesson')
                    <!-- Lesson Group Card -->
                    <div class="resource-card lesson-card">
                        <div class="resource-icon" style="background: linear-gradient(135deg, var(--secondary-color), #2980b9);">
                            <i class="fas fa-book-open"></i>
                        </div>
                        <div class="resource-info">
                            <div style="margin-bottom: 1rem;">
                                <span style="font-size: 0.8rem; text-transform: uppercase; letter-spacing: 1px; color: var(--text-light);">Lesson Bundle</span>
                                <h3 style="margin-top: 0.25rem;">
                                    <a href="{{ route('lessons.show', $group['lesson']->id) }}" style="text-decoration: none; color: inherit;">
                                        {{ $group['lesson']->title }}
                                    </a>
                                </h3>
                            </div>

                            <div class="lesson-files-list">
                                @foreach($group['items'] as $resource)
                                    <div class="lesson-file-item" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem; border-bottom: 1px solid #eee; gap: 0.5rem;">
                                        <div style="display: flex; align-items: center; gap: 0.75rem; overflow: hidden;">
                                            <i class="fas fa-{{ $resource->getTypeIcon() }}" style="color: var(--secondary-color); font-size: 1.1rem; width: 20px; text-align: center;"></i>
                                            <div style="overflow: hidden;">
                                                <div style="font-weight: 500; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $resource->title }}</div>
                                                <div style="font-size: 0.75rem; color: var(--text-light);">
                                                    {{ ucfirst($resource->type) }} • {{ $resource->file_size_formatted }}
                                                </div>
                                            </div>
                                        </div>
                                        <a href="{{ route('resources.download', $resource->id) }}" class="btn btn-sm btn-outline" style="padding: 0.25rem 0.5rem; font-size: 0.8rem;">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>
                                @endforeach
                            </div>
                            
                            <a href="{{ route('lessons.show', $group['lesson']->id) }}" class="btn btn-primary" style="width: 100%; margin-top: 1.5rem;">
                                <i class="fas fa-eye"></i> View Lesson
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Standalone Resource Card -->
                    @php $resource = $group['item']; @endphp
                    <div class="resource-card">
                        <div class="resource-icon">
                            <i class="fas fa-{{ $resource->getTypeIcon() }}"></i>
                        </div>
                        <div class="resource-info">
                            <h3>{{ $resource->title }}</h3>
                            <p>{{ Str::limit($resource->description, 100) }}</p>
                            
                            <div class="resource-meta">
                                <span><i class="fas fa-tag"></i> {{ ucfirst($resource->type) }}</span>
                                @if($resource->age_group)
                                    <span><i class="fas fa-users"></i> {{ $resource->age_group }}</span>
                                @endif
                                <span><i class="fas fa-download"></i> {{ $resource->downloads }}</span>
                            </div>

                            <a href="{{ route('resources.download', $resource->id) }}" class="btn btn-primary" style="width: 100%; margin-top: auto;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Pagination -->
        <div style="display: flex; justify-content: center;">
            {{ $resources->appends(request()->query())->links() }}
        </div>
        @else
        <div style="text-align: center; padding: 4rem 2rem; background: var(--background-light); border-radius: 15px;">
            <div style="font-size: 4rem; color: var(--text-light); margin-bottom: 1rem;">
                <i class="fas fa-search"></i>
            </div>
            <h3>No resources found</h3>
            <p style="color: var(--text-light); margin-bottom: 2rem;">
                @if(request()->hasAny(['search', 'type', 'age_group', 'category']))
                    Try adjusting your search criteria or clearing the filters.
                @else
                    Resources will appear here once they are added to the system.
                @endif
            </p>
            @if(request()->hasAny(['search', 'type', 'age_group', 'category']))
                <a href="{{ route('resources.index') }}" class="btn btn-primary">
                    <i class="fas fa-times"></i> Clear Filters
                </a>
            @endif
        </div>
        @endif
    </div>
</section>

<style>
.resource-card {
    background: var(--background-white);
    border-radius: 15px;
    padding: 2rem;
    box-shadow: var(--shadow-light);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid var(--border-color);
}

.resource-card:hover {
    transform: translateY(-5px);
    box-shadow: var(--shadow-medium);
}

.resource-card.featured {
    border: 2px solid var(--secondary-color);
    position: relative;
}

.resource-card.featured::before {
    content: "Featured";
    position: absolute;
    top: -10px;
    right: 1rem;
    background: var(--secondary-color);
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 12px;
    font-size: 0.8rem;
    font-weight: 600;
}

.resource-icon {
    width: 60px;
    height: 60px;
    background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 1.5rem;
    margin-bottom: 1.5rem;
}

.resource-info h3 {
    color: var(--primary-color);
    margin-bottom: 1rem;
    font-size: 1.2rem;
}

.resource-info p {
    color: var(--text-light);
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.resource-meta {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    margin-bottom: 1rem;
}

.resource-meta span {
    font-size: 0.9rem;
    color: var(--text-light);
    display: flex;
    align-items: center;
    gap: 0.25rem;
}

.resource-meta i {
    color: var(--secondary-color);
}
</style>
@endsection