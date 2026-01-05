@extends('layouts.app')

@section('title', $lesson['title'] . ' - ' . config('app.name'))

@php
function getAttachmentIcon($type) {
    $icons = [
        'pdf' => 'fas fa-file-pdf',
        'doc' => 'fas fa-file-word',
        'docx' => 'fas fa-file-word',
        'xls' => 'fas fa-file-excel',
        'xlsx' => 'fas fa-file-excel',
        'ppt' => 'fas fa-file-powerpoint',
        'pptx' => 'fas fa-file-powerpoint',
        'txt' => 'fas fa-file-alt',
        'jpg' => 'fas fa-file-image',
        'jpeg' => 'fas fa-file-image',
        'png' => 'fas fa-file-image',
        'gif' => 'fas fa-file-image',
        'zip' => 'fas fa-file-archive'
    ];
    $colors = [
        'pdf' => 'color: #d32f2f;',
        'doc' => 'color: #2196F3;',
        'docx' => 'color: #2196F3;',
        'xls' => 'color: #4CAF50;',
        'xlsx' => 'color: #4CAF50;',
        'ppt' => 'color: #FF9800;',
        'pptx' => 'color: #FF9800;',
        'txt' => 'color: #757575;',
        'jpg' => 'color: #00BCD4;',
        'jpeg' => 'color: #00BCD4;',
        'png' => 'color: #00BCD4;',
        'gif' => 'color: #00BCD4;',
        'zip' => 'color: #424242;'
    ];
    $icon = $icons[strtolower($type)] ?? 'fas fa-file';
    $color = $colors[strtolower($type)] ?? 'color: #757575;';
    return $icon . '" style="' . $color;
}
@endphp

@section('content')
<div style="padding: 2rem 0; background: var(--background-light);">
    <div class="container">
        <nav style="margin-bottom: 1.5rem; font-size: 0.9rem;">
            <a href="{{ route('home') }}" style="color: var(--text-light); text-decoration: none;">Home</a>
            <span style="margin: 0 0.5rem; color: var(--text-light);">/</span>
            <a href="{{ route('lessons.index') }}" style="color: var(--text-light); text-decoration: none;">Lessons</a>
            <span style="margin: 0 0.5rem; color: var(--text-light);">/</span>
            <span style="color: var(--primary-color);">{{ $lesson['title'] }}</span>
        </nav>

        <div class="lesson-layout">
            <div class="lesson-main">
                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 10px; box-shadow: var(--shadow-light);">
                    <h1 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.8rem; line-height: 1.3;">{{ $lesson['title'] }}</h1>
                    <div class="lesson-meta">
                        <div class="meta-item"><strong>Scripture:</strong> {{ $lesson['scripture'] }}</div>
                        <div class="meta-item"><strong>Theme:</strong> {{ $lesson['theme'] }}</div>
                        <div class="meta-item"><strong>Age:</strong> {{ $lesson['age_group'] }}</div>
                        <div class="meta-item"><strong>Duration:</strong> {{ $lesson['duration'] }}</div>
                    </div>

                    <section class="lesson-section"><h2>Overview</h2><p>{{ $lesson['overview'] }}</p></section>
                    <section class="lesson-section"><h2>Learning Objectives</h2><ul class="objectives-list">@foreach($lesson['objectives'] as $objective)<li>{{ $objective }}</li>@endforeach</ul></section>
                    <section class="lesson-section"><h2>Lesson Content</h2><p>{!! nl2br(e($lesson['content'])) !!}</p></section>
                    <section class="lesson-section"><h2>Discussion Questions</h2>@foreach($lesson['discussion_questions'] as $question)<div class="question-item"><i class="fas fa-question-circle" style="color: var(--secondary-color);"></i> {{ $question }}</div>@endforeach</section>

                    @php
                        $videoAttachments = collect($lesson['attachments'] ?? [])->filter(fn($a) => in_array(strtolower($a['type'] ?? ''), ['mp4', 'avi', 'mov', 'wmv', 'webm']));
                        $audioAttachments = collect($lesson['attachments'] ?? [])->filter(fn($a) => in_array(strtolower($a['type'] ?? ''), ['mp3', 'wav', 'ogg', 'm4a']));
                        $hasVideo = !empty($lesson['video_url']) || $videoAttachments->count() > 0;
                        $hasAudio = !empty($lesson['audio_url']) || $audioAttachments->count() > 0;
                    @endphp

                    @if($hasVideo || $hasAudio)
                    <section class="lesson-section">
                        <h2 style="margin-bottom: 1.5rem;">Multimedia Resources</h2>
                        @if($hasVideo)
                        <div class="media-item" style="margin-bottom: 1.5rem;">
                            <h4><i class="fas fa-video"></i> Video Lesson</h4>
                            <div style="margin-top: 0.5rem;">
                                <!-- Video Player Code -->
                            </div>
                        </div>
                        @endif
                        @if($hasAudio)
                        <div class="media-item">
                            <h4><i class="fas fa-volume-up"></i> Audio Story</h4>
                            <div style="margin-top: 0.5rem;">
                                <!-- Audio Player Code -->
                            </div>
                        </div>
                        @endif
                    </section>
                    @endif

                    @if(!empty($lesson['attachments']) && count($lesson['attachments']) > 0)
                    <section class="lesson-section">
                        <h2 style="margin-bottom: 1.5rem;"><i class="fas fa-download"></i> Downloads & Resources</h2>
                        <!-- Attachment code -->
                    </section>
                    @endif
                </div>
            </div>

            <aside class="lesson-sidebar">
                @if(count($relatedLessons) > 0)
                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 10px; box-shadow: var(--shadow-light);">
                    <h3 style="color: var(--primary-color); margin-bottom: 1.5rem; text-align: center;">Related Lessons</h3>
                    @foreach($relatedLessons as $related)
                    <div class="related-lesson-card">
                        <a href="{{ route('lessons.show', $related['id']) }}">
                            @if(isset($related['thumbnail']) && !in_array($related['thumbnail'], ['default.jpg', 'video-placeholder.jpg', 'audio-placeholder.jpg']))
                                <img src="{{ $related['thumbnail'] }}" alt="{{ $related['title'] }}">
                            @else
                                <div class="placeholder-img"><i class="fas fa-book"></i></div>
                            @endif
                            <div class="related-info">
                                <h4>{{ $related['title'] }}</h4>
                                <small>{{ $related['age_group'] }}</small>
                            </div>
                        </a>
                    </div>
                    @endforeach
                </div>
                @endif
            </aside>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.lesson-layout { display: grid; grid-template-columns: 1fr; gap: 2rem; }
@media (min-width: 1024px) { .lesson-layout { grid-template-columns: 3fr 1fr; } }
.lesson-main .lesson-section h2 { color: var(--primary-color); margin-top: 2rem; margin-bottom: 1rem; border-bottom: 2px solid #eee; padding-bottom: 0.5rem; }
.related-lesson-card { margin-bottom: 1rem; }
.related-lesson-card a { display: flex; align-items: center; gap: 1rem; text-decoration: none; color: inherit; background: #f8f9fa; border-radius: 8px; padding: 0.75rem; transition: all 0.2s ease; }
.related-lesson-card a:hover { transform: translateY(-3px); box-shadow: var(--shadow-light); background: white; }
.related-lesson-card img, .related-lesson-card .placeholder-img { width: 80px; height: 60px; object-fit: cover; border-radius: 5px; }
.related-lesson-card .placeholder-img { display: flex; align-items: center; justify-content: center; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); color: white; font-size: 1.5rem; }
.related-info h4 { font-size: 0.9rem; margin: 0 0 0.25rem 0; color: var(--text-dark); }
.related-info small { font-size: 0.8rem; color: var(--text-light); }
</style>
@endpush