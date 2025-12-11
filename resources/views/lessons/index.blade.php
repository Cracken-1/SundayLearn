@extends('layouts.app')

@section('title', 'Lessons - SundayLearn')

@section('content')
<div class="lessons-page">
    <div class="container">
        <div class="page-header">
            <h1>Bible Lessons</h1>
            <p>Engaging biblical lessons with multimedia resources for all age groups</p>
            
            <!-- Search Bar at Top -->
            <div style="max-width: 600px; margin: 2rem auto 0;">
                <div style="position: relative;">
                    <input type="text" id="lessonSearch" placeholder="Search lessons by title, scripture, or theme..." style="width: 100%; padding: 1rem 3rem 1rem 1rem; border: 2px solid var(--border-color); border-radius: 50px; font-size: 1rem; box-shadow: var(--shadow-light);">
                    <i class="fas fa-search" style="position: absolute; right: 1.5rem; top: 50%; transform: translateY(-50%); color: var(--text-light); font-size: 1.2rem;"></i>
                </div>
            </div>
        </div>

        @php
            $dbLessonsCount = $lessons->where('is_from_db', true)->count();
            $sampleLessonsCount = $lessons->where('is_from_db', false)->count();
        @endphp
        
        @if($dbLessonsCount > 0)
        <div style="background: linear-gradient(135deg, #28a745, #20c997); color: white; padding: 1rem 2rem; border-radius: 10px; margin-bottom: 2rem; text-align: center; box-shadow: var(--shadow-light);">
            <i class="fas fa-database"></i> 
            <strong>{{ $dbLessonsCount }} Live Lesson{{ $dbLessonsCount != 1 ? 's' : '' }}</strong> from database
            @if($sampleLessonsCount > 0)
                + {{ $sampleLessonsCount }} sample lesson{{ $sampleLessonsCount != 1 ? 's' : '' }}
            @endif
            | Look for the <span style="background: rgba(255,255,255,0.3); padding: 0.2rem 0.5rem; border-radius: 8px;"><i class="fas fa-database"></i> LIVE</span> badge on cards!
        </div>
        @endif

        <div class="lessons-layout" style="min-height: calc(100vh - 200px);">
            <!-- Left Sidebar -->
            <aside class="lessons-sidebar">
                <div class="filter-section">
                    <h3><i class="fas fa-users"></i> Age Groups</h3>
                    <ul class="filter-list">
                        <li><a href="{{ route('lessons.index') }}" class="{{ !request('age') ? 'active' : '' }}">All Ages</a></li>
                        <li>
                            <a href="{{ route('lessons.index', ['age' => '3-5']) }}" data-age="3-5">
                                Ages 3-5 <small style="display: block; color: var(--text-light); font-size: 0.8rem;">Preschool</small>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lessons.index', ['age' => '6-8']) }}" data-age="6-8">
                                Ages 6-8 <small style="display: block; color: var(--text-light); font-size: 0.8rem;">Early Elementary</small>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lessons.index', ['age' => '9-12']) }}" data-age="9-12">
                                Ages 9-12 <small style="display: block; color: var(--text-light); font-size: 0.8rem;">Upper Elementary</small>
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('lessons.index', ['age' => 'teen']) }}" data-age="teen">
                                Teen <small style="display: block; color: var(--text-light); font-size: 0.8rem;">13+ years</small>
                            </a>
                        </li>
                    </ul>
                </div>

                <div class="filter-section">
                    <h3><i class="fas fa-play-circle"></i> Media Type</h3>
                    <ul class="filter-list">
                        <li><a href="{{ route('lessons.index') }}" class="{{ !request('media') ? 'active' : '' }}">All Types</a></li>
                        <li><a href="{{ route('lessons.index', ['media' => 'video']) }}" data-media="video"><i class="fas fa-video"></i> Video</a></li>
                        <li><a href="{{ route('lessons.index', ['media' => 'audio']) }}" data-media="audio"><i class="fas fa-volume-up"></i> Audio</a></li>
                        <li><a href="{{ route('lessons.index', ['media' => 'text']) }}" data-media="text"><i class="fas fa-file-alt"></i> Text Only</a></li>
                    </ul>
                </div>

                <div class="filter-section">
                    <h3><i class="fas fa-bookmark"></i> Topics</h3>
                    <ul class="filter-list">
                        <li><a href="{{ route('lessons.index') }}"><i class="fas fa-bible"></i> All Topics</a></li>
                        <li><a href="{{ route('lessons.index', ['topic' => 'old-testament']) }}"><i class="fas fa-scroll"></i> Old Testament</a></li>
                        <li><a href="{{ route('lessons.index', ['topic' => 'new-testament']) }}"><i class="fas fa-cross"></i> New Testament</a></li>
                        <li><a href="{{ route('lessons.index', ['topic' => 'parables']) }}"><i class="fas fa-book-reader"></i> Parables</a></li>
                        <li><a href="{{ route('lessons.index', ['topic' => 'miracles']) }}"><i class="fas fa-star"></i> Miracles</a></li>
                    </ul>
                </div>
            </aside>

            <!-- Main Content -->

            <div class="lessons-grid">
                @foreach($lessons as $lesson)
                <div class="lesson-card">
                    <div class="lesson-thumbnail">
                        @if($lesson['thumbnail'] && !in_array($lesson['thumbnail'], ['default.jpg', 'video-placeholder.jpg', 'audio-placeholder.jpg']))
                            <img src="{{ $lesson['thumbnail'] }}" alt="{{ $lesson['title'] }}" 
                                 onerror="this.style.display='none';">
                        @endif
                        
                        @if(!isset($lesson['thumbnail']) || in_array($lesson['thumbnail'], ['default.jpg', 'video-placeholder.jpg', 'audio-placeholder.jpg']) || empty($lesson['thumbnail']))
                            @if(isset($lesson['thumbnail']) && $lesson['thumbnail'] === 'video-placeholder.jpg')
                                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                                    <i class="fas fa-video" style="font-size: 3rem; color: white;"></i>
                                </div>
                            @elseif(isset($lesson['thumbnail']) && $lesson['thumbnail'] === 'audio-placeholder.jpg')
                                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                                    <i class="fas fa-headphones" style="font-size: 3rem; color: white;"></i>
                                </div>
                            @else
                                <div style="background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%);">
                                    <i class="fas fa-book" style="font-size: 3rem; color: white;"></i>
                                </div>
                            @endif
                        @endif
                        
                        @if(isset($lesson['has_video']) && $lesson['has_video'] || isset($lesson['has_audio']) && $lesson['has_audio'])
                        <div class="media-indicators" style="position: absolute; bottom: 10px; left: 10px; display: flex; gap: 0.5rem;">
                            @if(isset($lesson['has_video']) && $lesson['has_video'])
                            <span class="media-icon video" style="background: rgba(0,0,0,0.7); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                                <i class="fas fa-video"></i>
                            </span>
                            @endif
                            @if(isset($lesson['has_audio']) && $lesson['has_audio'])
                            <span class="media-icon audio" style="background: rgba(0,0,0,0.7); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                                <i class="fas fa-volume-up"></i>
                            </span>
                            @endif
                            @if(isset($lesson['has_documents']) && $lesson['has_documents'])
                            <span class="media-icon documents" style="background: rgba(0,0,0,0.7); color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.75rem;">
                                <i class="fas fa-file-pdf"></i>
                            </span>
                            @endif
                        </div>
                        @endif
                        
                        @if(isset($lesson['is_from_db']))
                            @if($lesson['is_from_db'])
                            <span style="position: absolute; top: 10px; right: 10px; background: #28a745; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">
                                <i class="fas fa-database"></i> LIVE
                            </span>
                            @else
                            <span style="position: absolute; top: 10px; right: 10px; background: #6c757d; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.7rem; font-weight: 600;">
                                <i class="fas fa-star"></i> SAMPLE
                            </span>
                            @endif
                        @endif
                    </div>
                    
                    <div class="lesson-info">
                        <h3>{{ $lesson['title'] }}</h3>
                        <p class="scripture">{{ $lesson['scripture'] }}</p>
                        <p class="theme">{{ $lesson['theme'] }}</p>
                        
                        <div class="lesson-meta">
                            <span class="age-group"><i class="fas fa-users"></i> {{ $lesson['age_group'] }}</span>
                            <span><i class="fas fa-clock"></i> {{ $lesson['duration'] }}</span>
                        </div>
                        
                        <a href="{{ route('lessons.show', $lesson['id']) }}" class="btn btn-primary" style="width: 100%; margin-top: auto;">View Lesson</a>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Right Sidebar -->
            <aside class="lessons-right-sidebar">
                @php
                    $featuredLesson = $lessons->where('is_featured', true)->first();
                    $christmasEvent = $upcomingEvents->where('event_type', 'holiday')->where('title', 'like', '%Christmas%')->first();
                @endphp
                
                @if($featuredLesson || $christmasEvent)
                <div class="filter-section" style="background: linear-gradient(135deg, var(--secondary-color), #B8860B); padding: 1.5rem; border-radius: 10px; color: white; margin: -1.5rem -1.5rem 1.5rem -1.5rem;">
                    <h3 style="color: white; margin-bottom: 1rem;"><i class="fas fa-star"></i> Featured</h3>
                    
                    @if($christmasEvent && $christmasEvent->days_until >= 0 && $christmasEvent->days_until <= 30)
                        <p style="font-size: 0.95rem; margin-bottom: 1rem; opacity: 0.95;">
                            <strong>{{ $christmasEvent->title }}</strong> in {{ $christmasEvent->days_until }} {{ $christmasEvent->days_until == 1 ? 'day' : 'days' }}
                        </p>
                        <a href="{{ route('lessons.index', ['topic' => 'christmas']) }}" class="btn" style="background: white; color: var(--primary-color); width: 100%; padding: 0.75rem; text-align: center; font-weight: 600;">
                            <i class="fas fa-gifts"></i> View Christmas Lessons
                        </a>
                    @elseif($featuredLesson)
                        <p style="font-size: 0.95rem; margin-bottom: 1rem; opacity: 0.95;">
                            <strong>Featured:</strong> {{ $featuredLesson['title'] }}
                        </p>
                        <a href="{{ route('lessons.show', $featuredLesson['id']) }}" class="btn" style="background: white; color: var(--primary-color); width: 100%; padding: 0.75rem; text-align: center; font-weight: 600;">
                            <i class="fas fa-star"></i> View Featured Lesson
                        </a>
                    @else
                        <p style="font-size: 0.95rem; margin-bottom: 1rem; opacity: 0.95;">
                            <strong>This Week:</strong> Explore our Bible lessons
                        </p>
                        <a href="{{ route('lessons.index') }}" class="btn" style="background: white; color: var(--primary-color); width: 100%; padding: 0.75rem; text-align: center; font-weight: 600;">
                            <i class="fas fa-book"></i> Browse All Lessons
                        </a>
                    @endif
                </div>
                @endif

                <div class="filter-section">
                    <h3><i class="fas fa-fire"></i> Trending Now</h3>
                    @if(isset($trendingLessons) && $trendingLessons->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @php
                            $colors = ['var(--secondary-color)', 'var(--primary-color)', 'var(--accent-color)'];
                        @endphp
                        @foreach($trendingLessons as $index => $trending)
                        <div style="padding: 1rem; background: var(--background-light); border-radius: 8px; border-left: 4px solid {{ $colors[$index] ?? 'var(--primary-color)' }}; transition: transform 0.2s;">
                            <a href="{{ route('lessons.show', $trending['id']) }}" style="text-decoration: none; color: var(--text-dark);">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <div style="background: {{ $colors[$index] ?? 'var(--primary-color)' }}; color: white; width: 30px; height: 30px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 0.9rem;">{{ $index + 1 }}</div>
                                    <strong style="flex: 1;">{{ $trending['title'] }}</strong>
                                </div>
                                <small style="color: var(--text-light); display: block; padding-left: 38px;">
                                    <i class="fas fa-eye"></i> {{ number_format($trending['views_count']) }} views • <i class="fas fa-users"></i> {{ $trending['age_group'] }}
                                </small>
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p style="text-align: center; color: var(--text-light); padding: 1rem;">
                        <i class="fas fa-chart-line"></i><br>
                        No trending data yet
                    </p>
                    @endif
                </div>

                <div class="filter-section">
                    <h3><i class="fas fa-calendar-check"></i> Upcoming</h3>
                    @if(isset($upcomingEvents) && $upcomingEvents->count() > 0)
                    <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                        @foreach($upcomingEvents as $event)
                        <div style="padding: 1rem; background: {{ $event->is_featured ? 'linear-gradient(135deg, ' . $event->color . ', ' . $event->color . 'dd)' : 'var(--background-light)' }}; border-radius: 8px; color: {{ $event->is_featured ? 'white' : 'var(--text-dark)' }};">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.5rem;">
                                <strong style="font-size: 1.1rem;">
                                    <i class="fas fa-{{ $event->icon }}"></i> {{ $event->title }}
                                </strong>
                                @if($event->days_until >= 0)
                                <span style="background: {{ $event->is_featured ? 'rgba(255,255,255,0.3)' : 'var(--primary-color)' }}; color: {{ $event->is_featured ? 'white' : 'white' }}; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem;">
                                    {{ $event->days_until }} {{ $event->days_until == 1 ? 'day' : 'days' }}
                                </span>
                                @endif
                            </div>
                            <small style="opacity: 0.95;">{{ $event->event_date->format('F j, Y') }} • {{ $event->description }}</small>
                        </div>
                        @endforeach
                    </div>
                    @else
                    <p style="text-align: center; color: var(--text-light); padding: 1rem;">
                        <i class="fas fa-calendar"></i><br>
                        No upcoming events
                    </p>
                    @endif
                </div>

                <div class="filter-section">
                    <h3><i class="fas fa-lightbulb"></i> Teaching Tip</h3>
                    @if(isset($teachingTip) && $teachingTip)
                    <div style="padding: 1.25rem; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 10px; color: white; box-shadow: var(--shadow-light);">
                        <div style="font-size: 2rem; margin-bottom: 0.75rem; text-align: center;">
                            <i class="fas fa-{{ $teachingTip->icon }}"></i>
                        </div>
                        <p style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem;">
                            <strong>{{ $teachingTip->title }}</strong><br>
                            {{ Str::limit($teachingTip->content, 120) }}
                        </p>
                        <a href="{{ route('blog.index') }}" style="color: white; text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(255,255,255,0.2); padding: 0.5rem; border-radius: 5px;">
                            More Tips <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @else
                    <div style="padding: 1.25rem; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 10px; color: white; box-shadow: var(--shadow-light);">
                        <div style="font-size: 2rem; margin-bottom: 0.75rem; text-align: center;">💡</div>
                        <p style="font-size: 0.95rem; line-height: 1.6; margin-bottom: 1rem;">
                            <strong>Pro Tip:</strong> Use visual aids and props to make Bible stories come alive for younger students!
                        </p>
                        <a href="{{ route('blog.index') }}" style="color: white; text-decoration: none; font-size: 0.9rem; font-weight: 600; display: flex; align-items: center; justify-content: center; gap: 0.5rem; background: rgba(255,255,255,0.2); padding: 0.5rem; border-radius: 5px;">
                            More Tips <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                    @endif
                </div>

                <div class="filter-section">
                    <h3><i class="fas fa-download"></i> Resources</h3>
                    @php
                        $resourceTypes = \App\Models\Resource::selectRaw('type, COUNT(*) as count')
                            ->groupBy('type')
                            ->orderBy('count', 'desc')
                            ->take(3)
                            ->get();
                    @endphp
                    
                    @if($resourceTypes->count() > 0)
                    <div style="display: grid; gap: 0.5rem;">
                        @foreach($resourceTypes as $resourceType)
                        <a href="{{ route('resources.index', ['type' => $resourceType->type]) }}" class="btn btn-outline" style="width: 100%; padding: 0.75rem; font-size: 0.9rem; text-align: center; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem;">
                            <span style="display: flex; align-items: center; gap: 0.5rem;">
                                <i class="fas fa-{{ $resourceType->type == 'coloring_page' ? 'palette' : ($resourceType->type == 'worksheet' ? 'file-pdf' : ($resourceType->type == 'activity_guide' ? 'clipboard-list' : ($resourceType->type == 'craft' ? 'cut' : ($resourceType->type == 'game' ? 'gamepad' : 'file')))) }}"></i>
                                {{ ucwords(str_replace('_', ' ', $resourceType->type)) }}
                            </span>
                            <span style="background: var(--primary-color); color: white; padding: 0.2rem 0.5rem; border-radius: 10px; font-size: 0.8rem;">{{ $resourceType->count }}</span>
                        </a>
                        @endforeach
                        
                        <a href="{{ route('resources.index') }}" class="btn btn-primary" style="width: 100%; padding: 0.75rem; font-size: 0.9rem; text-align: center; margin-top: 0.5rem;">
                            <i class="fas fa-download"></i> View All Resources
                        </a>
                    </div>
                    @else
                    <div style="text-align: center; padding: 1rem; color: var(--text-light);">
                        <i class="fas fa-download" style="font-size: 2rem; margin-bottom: 0.5rem; opacity: 0.5;"></i>
                        <p style="margin: 0; font-size: 0.9rem;">No resources available yet</p>
                        <a href="{{ route('resources.index') }}" class="btn btn-outline" style="width: 100%; padding: 0.75rem; font-size: 0.9rem; margin-top: 0.5rem;">
                            <i class="fas fa-plus"></i> Check Back Soon
                        </a>
                    </div>
                    @endif
                </div>
            </aside>
        </div>
    </div>
</div>
@endsection