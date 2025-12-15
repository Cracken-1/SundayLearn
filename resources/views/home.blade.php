@extends('layouts.app')

@section('title', 'Home - SundayLearn')

@section('content')
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <div style="display: inline-block; background: rgba(255,255,255,0.1); padding: 0.5rem 1rem; border-radius: 20px; margin-bottom: 1rem; font-size: 0.9rem; font-weight: 600;">
                <i class="fas fa-star" style="color: var(--secondary-color);"></i> Trusted by 1000+ Teachers
            </div>
            <h1>Welcome to SundayLearn</h1>
            <p>Empowering Sunday school teachers with engaging biblical lessons, multimedia resources, and teaching tools designed to bring God's Word to life.</p>
            <div class="hero-buttons">
                <a href="{{ route('lessons.index') }}" class="btn btn-primary">
                    <i class="fas fa-play-circle"></i> Start Teaching Today
                </a>
                <a href="{{ route('resources.index') }}" class="btn btn-secondary">
                    <i class="fas fa-download"></i> Free Resources
                </a>
            </div>
            <div style="margin-top: 2rem; display: flex; align-items: center; gap: 2rem; opacity: 0.9; font-size: 0.9rem;">
                <div><i class="fas fa-check-circle" style="color: var(--secondary-color);"></i> 100% Free</div>
                <div><i class="fas fa-check-circle" style="color: var(--secondary-color);"></i> No Registration Required</div>
                <div><i class="fas fa-check-circle" style="color: var(--secondary-color);"></i> Instant Access</div>
            </div>
        </div>
        <div class="hero-image">
            <div style="position: relative;">
                <i class="fas fa-bible" style="font-size: 8rem; opacity: 0.3;"></i>
                <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: rgba(255,255,255,0.1); border-radius: 50%; padding: 1.5rem;">
                    <i class="fas fa-heart" style="font-size: 2rem; color: var(--secondary-color);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Search Section -->
<section style="padding: 3rem 0; background: var(--background-white); border-bottom: 3px solid var(--secondary-color);">
    <div class="container">
        <div style="text-align: center; max-width: 800px; margin: 0 auto;">
            <h2 style="margin-bottom: 1rem;">Find the Perfect Lesson</h2>
            <p style="color: var(--text-light); margin-bottom: 2rem;">Search through our collection of Bible lessons by story, scripture, or theme</p>
            <div style="position: relative; max-width: 600px; margin: 0 auto;">
                <input type="text" placeholder="Search for David and Goliath, Genesis 1, courage..." style="width: 100%; padding: 1.25rem 4rem 1.25rem 1.5rem; border: 3px solid var(--border-color); border-radius: 50px; font-size: 1.1rem; box-shadow: var(--shadow-medium);">
                <button style="position: absolute; right: 0.5rem; top: 50%; transform: translateY(-50%); background: var(--primary-color); color: white; border: none; border-radius: 50%; width: 50px; height: 50px; cursor: pointer;">
                    <i class="fas fa-search" style="font-size: 1.2rem;"></i>
                </button>
            </div>
            <div style="margin-top: 1.5rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <span style="background: var(--background-light); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; color: var(--text-light);">
                    Popular: <a href="{{ route('lessons.show', 1) }}" style="color: var(--primary-color); text-decoration: none;">David & Goliath</a>
                </span>
                <span style="background: var(--background-light); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; color: var(--text-light);">
                    <a href="{{ route('lessons.show', 5) }}" style="color: var(--primary-color); text-decoration: none;">Christmas Story</a>
                </span>
                <span style="background: var(--background-light); padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.9rem; color: var(--text-light);">
                    <a href="{{ route('lessons.show', 2) }}" style="color: var(--primary-color); text-decoration: none;">Noah's Ark</a>
                </span>
            </div>
        </div>
    </div>
</section>

<section class="quick-access">
    <div class="container">
        <h2>Start Teaching in Minutes</h2>
        <p style="text-align: center; color: var(--text-light); margin-bottom: 3rem; font-size: 1.1rem;">Everything you need for an engaging Sunday school lesson</p>
        <div class="cards-grid" style="grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));">
            <div class="access-card" style="border-left: 4px solid var(--secondary-color);">
                <div class="card-icon" style="background: linear-gradient(135deg, var(--secondary-color), #B8860B);">
                    <i class="fas fa-calendar-day"></i>
                </div>
                <h3>Today's Lesson</h3>
                <p>Complete lesson plan with objectives, activities, and discussion questions ready to use</p>
                <div style="margin: 1rem 0; padding: 0.5rem; background: var(--background-light); border-radius: 5px; font-size: 0.9rem;">
                    <strong>Featured:</strong> David and Goliath
                </div>
                <a href="{{ route('lessons.show', 1) }}" class="card-link">Start Teaching <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="access-card" style="border-left: 4px solid var(--primary-color);">
                <div class="card-icon" style="background: linear-gradient(135deg, var(--primary-color), #654321);">
                    <i class="fas fa-users"></i>
                </div>
                <h3>Age-Appropriate Content</h3>
                <p>Lessons designed specifically for preschool, elementary, and teen groups</p>
                <div style="margin: 1rem 0; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                    <span style="background: var(--background-light); padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem;">3-5 years</span>
                    <span style="background: var(--background-light); padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem;">6-8 years</span>
                    <span style="background: var(--background-light); padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem;">9-12 years</span>
                </div>
                <a href="{{ route('lessons.index') }}" class="card-link">Browse by Age <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="access-card" style="border-left: 4px solid var(--accent-color);">
                <div class="card-icon" style="background: linear-gradient(135deg, var(--accent-color), #A0522D);">
                    <i class="fas fa-download"></i>
                </div>
                <h3>Instant Downloads</h3>
                <p>Worksheets, coloring pages, crafts, and activities ready to print and use</p>
                <div style="margin: 1rem 0; padding: 0.5rem; background: var(--background-light); border-radius: 5px; font-size: 0.9rem;">
                    <i class="fas fa-file-pdf" style="color: #d32f2f;"></i> <strong>50+</strong> Resources Available
                </div>
                <a href="{{ route('resources.index') }}" class="card-link">Get Resources <i class="fas fa-arrow-right"></i></a>
            </div>
            <div class="access-card" style="border-left: 4px solid #228B22;">
                <div class="card-icon" style="background: linear-gradient(135deg, #228B22, #006400);">
                    <i class="fas fa-video"></i>
                </div>
                <h3>Multimedia Lessons</h3>
                <p>Video stories, audio narrations, and interactive elements to engage students</p>
                <div style="margin: 1rem 0; display: flex; gap: 0.5rem;">
                    <span style="background: #e3f2fd; color: #1976d2; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem;">
                        <i class="fas fa-video"></i> Video
                    </span>
                    <span style="background: #f3e5f5; color: #7b1fa2; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.8rem;">
                        <i class="fas fa-volume-up"></i> Audio
                    </span>
                </div>
                <a href="{{ route('lessons.index', ['media' => 'video']) }}" class="card-link">View Media <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>
</section>

<!-- Lesson Categories -->
<section style="padding: 4rem 0; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); color: white;">
    <div class="container">
        <div style="text-align: center; margin-bottom: 3rem;">
            <h2 style="color: white; margin-bottom: 1rem;">Explore Bible Stories by Topic</h2>
            <p style="opacity: 0.95; font-size: 1.1rem;">Discover lessons organized by biblical themes and stories</p>
        </div>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 2rem;">
            <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 15px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-scroll"></i>
                </div>
                <h3 style="color: white; margin-bottom: 1rem;">Old Testament</h3>
                <p style="opacity: 0.9; margin-bottom: 1.5rem; font-size: 0.95rem;">Creation, Noah's Ark, Moses, David & Goliath, and more foundational stories</p>
                <a href="{{ route('lessons.index', ['topic' => 'old-testament']) }}" style="background: white; color: var(--primary-color); padding: 0.75rem 1.5rem; border-radius: 25px; text-decoration: none; font-weight: 600; display: inline-block;">
                    Explore Stories
                </a>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 15px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-cross"></i>
                </div>
                <h3 style="color: white; margin-bottom: 1rem;">New Testament</h3>
                <p style="opacity: 0.9; margin-bottom: 1.5rem; font-size: 0.95rem;">Jesus' birth, miracles, parables, and the early church stories</p>
                <a href="{{ route('lessons.index', ['topic' => 'new-testament']) }}" style="background: white; color: var(--primary-color); padding: 0.75rem 1.5rem; border-radius: 25px; text-decoration: none; font-weight: 600; display: inline-block;">
                    Discover Jesus
                </a>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 15px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-book-reader"></i>
                </div>
                <h3 style="color: white; margin-bottom: 1rem;">Parables</h3>
                <p style="opacity: 0.9; margin-bottom: 1.5rem; font-size: 0.95rem;">Good Samaritan, Prodigal Son, and other teaching stories of Jesus</p>
                <a href="{{ route('lessons.index', ['topic' => 'parables']) }}" style="background: white; color: var(--primary-color); padding: 0.75rem 1.5rem; border-radius: 25px; text-decoration: none; font-weight: 600; display: inline-block;">
                    Learn Parables
                </a>
            </div>
            <div style="background: rgba(255,255,255,0.1); padding: 2rem; border-radius: 15px; text-align: center; backdrop-filter: blur(10px); border: 1px solid rgba(255,255,255,0.2);">
                <div style="font-size: 3rem; margin-bottom: 1rem;">
                    <i class="fas fa-star"></i>
                </div>
                <h3 style="color: white; margin-bottom: 1rem;">Miracles</h3>
                <p style="opacity: 0.9; margin-bottom: 1.5rem; font-size: 0.95rem;">Feeding 5000, walking on water, and God's amazing power</p>
                <a href="{{ route('lessons.index', ['topic' => 'miracles']) }}" style="background: white; color: var(--primary-color); padding: 0.75rem 1.5rem; border-radius: 25px; text-decoration: none; font-weight: 600; display: inline-block;">
                    See Miracles
                </a>
            </div>
        </div>
    </div>
</section>

<section style="padding: 4rem 0; background: var(--background-light);">
    <div class="container">

        <div style="text-align: center; margin-bottom: 3rem;">
            <h2>Featured Lessons This Week</h2>
            <p style="color: var(--text-light); font-size: 1.1rem;">Hand-picked lessons that teachers love most</p>
        </div>
        <div class="lessons-grid">
            @foreach($featuredLessons as $lesson)
            <div class="lesson-card">
                <div class="lesson-thumbnail" style="position: relative; overflow: hidden;">
                    @if($lesson['thumbnail'] && !in_array($lesson['thumbnail'], ['default.jpg', 'video-placeholder.jpg', 'video-attachment-placeholder.jpg', 'audio-placeholder.jpg']))
                        <img src="{{ $lesson['thumbnail'] }}" alt="{{ $lesson['title'] }}" 
                             style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                    @elseif(in_array($lesson['thumbnail'], ['video-placeholder.jpg', 'video-attachment-placeholder.jpg']))
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-video" style="font-size: 3rem; color: white;"></i>
                        </div>
                    @elseif($lesson['thumbnail'] === 'audio-placeholder.jpg')
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-headphones" style="font-size: 3rem; color: white;"></i>
                        </div>
                    @else
                        <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); display: flex; align-items: center; justify-content: center;">
                            <i class="fas fa-book" style="font-size: 3rem; color: white;"></i>
                        </div>
                    @endif
                    @if($lesson['has_video'] || $lesson['has_audio'])
                    <div class="media-indicators">
                        @if($lesson['has_video'])
                        <span class="media-icon video"><i class="fas fa-video"></i></span>
                        @endif
                        @if($lesson['has_audio'])
                        <span class="media-icon audio"><i class="fas fa-volume-up"></i></span>
                        @endif
                    </div>
                    @endif

                </div>
                <div class="lesson-info">
                    <h3>{{ $lesson['title'] }}</h3>
                    <p class="scripture">{{ $lesson['scripture'] }}</p>
                    <div class="lesson-meta">
                        <span><i class="fas fa-users"></i> {{ $lesson['age_group'] }}</span>
                        <span><i class="fas fa-clock"></i> {{ $lesson['duration'] }}</span>
                    </div>
                    <a href="{{ route('lessons.show', $lesson['id']) }}" class="btn btn-primary" style="width: 100%;">View Lesson</a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section style="padding: 4rem 0; background: var(--background-white);">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 1rem;">Why Teachers Love SundayLearn</h2>
        <p style="text-align: center; color: var(--text-light); margin-bottom: 3rem; font-size: 1.1rem;">Join thousands of Sunday school teachers using our platform</p>
        
        <div style="background: linear-gradient(135deg, var(--background-white), var(--background-light)); padding: 3rem; border-radius: 20px; box-shadow: var(--shadow-medium); margin-bottom: 4rem;">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 2rem;">
                @foreach($stats as $stat)
                <div style="text-align: center; padding: 1.5rem; background: white; border-radius: 15px; box-shadow: var(--shadow-light); position: relative; overflow: hidden;">
                    <div style="position: absolute; top: 0; left: 0; right: 0; height: 4px; background: linear-gradient(90deg, var(--secondary-color), var(--primary-color));"></div>
                    <div style="font-size: 2.5rem; color: var(--secondary-color); margin-bottom: 0.75rem;">
                        <i class="fas fa-{{ $stat['icon'] }}"></i>
                    </div>
                    <div style="font-size: 2.2rem; font-weight: bold; color: var(--primary-color); margin-bottom: 0.5rem; font-family: var(--font-heading);">
                        {{ $stat['number'] }}
                    </div>
                    <div style="color: var(--text-light); font-size: 1rem; font-weight: 500;">
                        {{ $stat['label'] }}
                    </div>
                </div>
                @endforeach
            </div>
            <div style="text-align: center; margin-top: 2rem;">
                <p style="color: var(--text-light); font-size: 1.1rem; margin-bottom: 1.5rem;">Join thousands of teachers who trust SundayLearn for their classroom needs</p>
                <a href="{{ route('lessons.index') }}" class="btn btn-primary" style="padding: 1rem 2rem; font-size: 1.1rem;">
                    <i class="fas fa-rocket"></i> Get Started Now
                </a>
            </div>
        </div>

        <!-- This Week's Focus -->
        @if($featuredEvent)
        <div style="background: var(--background-light); padding: 3rem; border-radius: 20px; margin-bottom: 4rem; border-left: 6px solid {{ $featuredEvent->color }};">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem; align-items: center;">
                <div>
                    <div style="background: {{ $featuredEvent->color }}; color: white; padding: 0.5rem 1rem; border-radius: 20px; display: inline-block; font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">
                        <i class="fas fa-calendar-week"></i> This Week's Focus
                    </div>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.8rem;">{{ $featuredEvent->title }}</h3>
                    <p style="color: var(--text-light); margin-bottom: 1rem; line-height: 1.6;">
                        {{ $featuredEvent->description }}
                    </p>
                    <p style="color: var(--text-light); margin-bottom: 1.5rem; font-size: 0.95rem;">
                        <i class="fas fa-calendar"></i> {{ $featuredEvent->event_date->format('F j, Y') }}
                        @if($featuredEvent->days_until >= 0)
                            • {{ $featuredEvent->days_until }} {{ $featuredEvent->days_until == 1 ? 'day' : 'days' }} away
                        @endif
                    </p>
                    <a href="{{ route('lessons.index', ['topic' => strtolower(str_replace(' ', '-', $featuredEvent->title))]) }}" class="btn btn-primary">
                        <i class="fas fa-{{ $featuredEvent->icon }}"></i> View Related Lessons
                    </a>
                </div>
                <div style="text-align: center;">
                    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--shadow-light); display: inline-block;">
                        <div style="font-size: 4rem; color: {{ $featuredEvent->color }}; margin-bottom: 1rem;">
                            <i class="fas fa-{{ $featuredEvent->icon }}"></i>
                        </div>
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">{{ ucfirst($featuredEvent->event_type) }} Event</h4>
                        <p style="color: var(--text-light); font-size: 0.9rem;">{{ $featuredEvent->title }} Resources</p>
                    </div>
                </div>
            </div>
        </div>
        @else
        <div style="background: var(--background-light); padding: 3rem; border-radius: 20px; margin-bottom: 4rem; border-left: 6px solid var(--secondary-color);">
            <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 3rem; align-items: center;">
                <div>
                    <div style="background: var(--secondary-color); color: white; padding: 0.5rem 1rem; border-radius: 20px; display: inline-block; font-size: 0.9rem; font-weight: 600; margin-bottom: 1rem;">
                        <i class="fas fa-calendar-week"></i> This Week's Focus
                    </div>
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.8rem;">Explore Bible Lessons</h3>
                    <p style="color: var(--text-light); margin-bottom: 1.5rem; line-height: 1.6;">
                        Discover engaging biblical lessons with multimedia resources designed to bring God's Word to life for students of all ages.
                    </p>
                    <a href="{{ route('lessons.index') }}" class="btn btn-primary">
                        <i class="fas fa-book"></i> Browse All Lessons
                    </a>
                </div>
                <div style="text-align: center;">
                    <div style="background: white; padding: 2rem; border-radius: 15px; box-shadow: var(--shadow-light); display: inline-block;">
                        <div style="font-size: 4rem; color: var(--secondary-color); margin-bottom: 1rem;">
                            <i class="fas fa-bible"></i>
                        </div>
                        <h4 style="color: var(--primary-color); margin-bottom: 0.5rem;">Teaching Resources</h4>
                        <p style="color: var(--text-light); font-size: 0.9rem;">Lessons & Materials</p>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <h2 style="text-align: center; margin-bottom: 1rem;">Latest Teaching Tips</h2>
        <p style="text-align: center; color: var(--text-light); margin-bottom: 3rem; font-size: 1.1rem;">Expert advice to make your lessons more engaging</p>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            @foreach($recentPosts as $post)
            <div class="access-card" style="border-top: 4px solid var(--secondary-color); position: relative;">
                <div style="position: absolute; top: -2px; right: 1rem; background: var(--secondary-color); color: white; padding: 0.25rem 0.75rem; border-radius: 0 0 10px 10px; font-size: 0.8rem; font-weight: 600;">
                    NEW
                </div>
                <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                    <div style="width: 50px; height: 50px; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white;">
                        <i class="fas fa-lightbulb"></i>
                    </div>
                    <div>
                        <small style="color: var(--text-light);">{{ \Carbon\Carbon::parse($post['published_at'])->format('M j, Y') }}</small>
                        <div style="font-size: 0.9rem; color: var(--secondary-color); font-weight: 600;">Teaching Tip</div>
                    </div>
                </div>
                <h3 style="margin-bottom: 1rem;">{{ $post['title'] }}</h3>
                <p style="margin-bottom: 1.5rem;">{{ $post['excerpt'] }}</p>
                <a href="{{ route('blog.show', $post['id']) }}" class="card-link">Read Full Article <i class="fas fa-arrow-right"></i></a>
            </div>
            @endforeach
        </div>
    </div>
</section>

<section style="padding: 4rem 0; background: var(--background-light);">
    <div class="container">
        <h2 style="text-align: center; margin-bottom: 1rem;">What Teachers Are Saying</h2>
        <p style="text-align: center; color: var(--text-light); margin-bottom: 3rem; font-size: 1.1rem;">Real feedback from Sunday school teachers</p>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            <div style="background: var(--background-white); padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow-medium); position: relative; border-top: 4px solid var(--secondary-color);">
                <div style="position: absolute; top: -12px; left: 2rem; background: var(--secondary-color); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    ⭐ 5 STARS
                </div>
                <div style="color: var(--secondary-color); font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p style="font-style: italic; margin-bottom: 1.5rem; line-height: 1.7; font-size: 1.05rem;">
                    "SundayLearn has transformed my lesson planning! I used to spend hours searching for materials, now everything I need is in one place."
                </p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--secondary-color), #B8860B); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                        SM
                    </div>
                    <div>
                        <strong style="color: var(--primary-color);">Sarah Martinez</strong>
                        <div style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">First Baptist Church</div>
                        <div style="color: var(--secondary-color); font-size: 0.8rem;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: var(--background-white); padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow-medium); position: relative; border-top: 4px solid var(--primary-color);">
                <div style="position: absolute; top: -12px; left: 2rem; background: var(--primary-color); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    ⭐ 5 STARS
                </div>
                <div style="color: var(--secondary-color); font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p style="font-style: italic; margin-bottom: 1.5rem; line-height: 1.7; font-size: 1.05rem;">
                    "The age-appropriate content is exactly what I needed. My kids are more engaged than ever before!"
                </p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--primary-color), #654321); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                        JT
                    </div>
                    <div>
                        <strong style="color: var(--primary-color);">James Thompson</strong>
                        <div style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">Grace Community Church</div>
                        <div style="color: var(--secondary-color); font-size: 0.8rem;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div style="background: var(--background-white); padding: 2.5rem; border-radius: 15px; box-shadow: var(--shadow-medium); position: relative; border-top: 4px solid var(--accent-color);">
                <div style="position: absolute; top: -12px; left: 2rem; background: var(--accent-color); color: white; padding: 0.5rem 1rem; border-radius: 20px; font-size: 0.8rem; font-weight: 600;">
                    ⭐ 5 STARS
                </div>
                <div style="color: var(--secondary-color); font-size: 2.5rem; margin-bottom: 1rem;">
                    <i class="fas fa-quote-left"></i>
                </div>
                <p style="font-style: italic; margin-bottom: 1.5rem; line-height: 1.7; font-size: 1.05rem;">
                    "The downloadable resources are a game-changer. Professional quality materials that save me so much time!"
                </p>
                <div style="display: flex; align-items: center; gap: 1rem;">
                    <div style="width: 60px; height: 60px; background: linear-gradient(135deg, var(--accent-color), #A0522D); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-weight: bold; font-size: 1.2rem;">
                        LW
                    </div>
                    <div>
                        <strong style="color: var(--primary-color);">Lisa Williams</strong>
                        <div style="color: var(--text-light); font-size: 0.9rem; margin-bottom: 0.25rem;">St. Paul's Methodist</div>
                        <div style="color: var(--secondary-color); font-size: 0.8rem;">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Call to Action -->
        <div style="text-align: center; margin-top: 3rem; padding: 3rem; background: linear-gradient(135deg, var(--primary-color), var(--accent-color)); border-radius: 20px; color: white;">
            <h3 style="color: white; margin-bottom: 1rem; font-size: 2rem;">Ready to Transform Your Teaching?</h3>
            <p style="opacity: 0.95; margin-bottom: 2rem; font-size: 1.1rem;">Join thousands of teachers who have already discovered the joy of stress-free lesson planning</p>
            <div style="display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap;">
                <a href="{{ route('lessons.index') }}" class="btn" style="background: white; color: var(--primary-color); padding: 1rem 2rem; font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-rocket"></i> Start Teaching Today
                </a>
                <a href="{{ route('resources.index') }}" class="btn" style="background: rgba(255,255,255,0.2); color: white; border: 2px solid white; padding: 1rem 2rem; font-size: 1.1rem; font-weight: 600;">
                    <i class="fas fa-download"></i> Get Free Resources
                </a>
            </div>
        </div>
    </div>
</section>
@endsection