@extends('layouts.app')

@section('title', $lesson['title'] . ' - SundayLearn')

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
        <!-- Breadcrumbs -->
        <nav style="margin-bottom: 1.5rem; font-size: 0.9rem;">
            <a href="{{ route('home') }}" style="color: var(--text-light); text-decoration: none;">Home</a>
            <span style="margin: 0 0.5rem; color: var(--text-light);">/</span>
            <a href="{{ route('lessons.index') }}" style="color: var(--text-light); text-decoration: none;">Lessons</a>
            <span style="margin: 0 0.5rem; color: var(--text-light);">/</span>
            <span style="color: var(--primary-color);">{{ $lesson['title'] }}</span>
        </nav>

        <div style="display: flex; gap: 1rem; margin-bottom: 2rem;">
            <a href="{{ route('lessons.index') }}" style="color: var(--primary-color); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Lessons
            </a>
            <button onclick="window.print()" class="btn btn-outline" style="margin-left: auto; padding: 0.5rem 1rem;">
                <i class="fas fa-print"></i> Print Lesson
            </button>
            <button onclick="shareLesson()" class="btn btn-outline" style="padding: 0.5rem 1rem;">
                <i class="fas fa-share-alt"></i> Share
            </button>
        </div>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div>
                <div style="background: var(--background-white); padding: 2rem; border-radius: 10px; box-shadow: var(--shadow-light);">
                    <h1 style="color: var(--primary-color); margin-bottom: 1rem;">{{ $lesson['title'] }}</h1>
                    
                    <div style="display: flex; gap: 2rem; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 2px solid var(--border-color);">
                        <div>
                            <strong>Scripture:</strong> {{ $lesson['scripture'] }}
                        </div>
                        <div>
                            <strong>Theme:</strong> {{ $lesson['theme'] }}
                        </div>
                        <div>
                            <strong>Age:</strong> {{ $lesson['age_group'] }}
                        </div>
                        <div>
                            <strong>Duration:</strong> {{ $lesson['duration'] }}
                        </div>
                    </div>

                    <section class="lesson-section">
                        <h2>Overview</h2>
                        <p>{{ $lesson['overview'] }}</p>
                    </section>

                    <section class="lesson-section">
                        <h2>Learning Objectives</h2>
                        <ul class="objectives-list">
                            @foreach($lesson['objectives'] as $objective)
                            <li>{{ $objective }}</li>
                            @endforeach
                        </ul>
                    </section>

                    <section class="lesson-section">
                        <h2>Lesson Content</h2>
                        <p>{{ $lesson['content'] }}</p>
                    </section>

                    <section class="lesson-section">
                        <h2>Discussion Questions</h2>
                        @foreach($lesson['discussion_questions'] as $question)
                        <div class="question-item">
                            <i class="fas fa-question-circle" style="color: var(--secondary-color);"></i>
                            {{ $question }}
                        </div>
                        @endforeach
                    </section>
                </div>
            </div>

            <aside>
                @if($lesson['video_url'] || $lesson['audio_url'])
                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;">Multimedia Resources</h3>
                    
                    @if($lesson['video_url'])
                    <div class="media-item" style="margin-bottom: 1.5rem;">
                        <h4><i class="fas fa-video"></i> Video Lesson</h4>
                        <div style="background: #f5f5f5; padding: 3rem; text-align: center; border-radius: 5px; margin-top: 0.5rem;">
                            <i class="fas fa-play-circle" style="font-size: 3rem; color: var(--primary-color);"></i>
                            <p style="margin-top: 1rem; color: #666;">Video player placeholder</p>
                        </div>
                    </div>
                    @endif

                    @if($lesson['audio_url'])
                    <div class="media-item">
                        <h4><i class="fas fa-volume-up"></i> Audio Story</h4>
                        <div style="background: #f5f5f5; padding: 2rem; text-align: center; border-radius: 5px; margin-top: 0.5rem;">
                            <i class="fas fa-headphones" style="font-size: 2rem; color: var(--primary-color);"></i>
                            <p style="margin-top: 0.5rem; color: #666;">Audio player placeholder</p>
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                @php
                    $videoAttachments = collect($lesson['attachments'] ?? [])->filter(function($a) {
                        return in_array(strtolower($a['type'] ?? ''), ['mp4', 'avi', 'mov', 'wmv', 'webm']);
                    });
                    $audioAttachments = collect($lesson['attachments'] ?? [])->filter(function($a) {
                        return in_array(strtolower($a['type'] ?? ''), ['mp3', 'wav', 'ogg', 'm4a']);
                    });
                    $imageAttachments = collect($lesson['attachments'] ?? [])->filter(function($a) {
                        return in_array(strtolower($a['type'] ?? ''), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    });
                    $documentAttachments = collect($lesson['attachments'] ?? [])->filter(function($a) {
                        return in_array(strtolower($a['type'] ?? ''), ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']);
                    });
                    $otherAttachments = collect($lesson['attachments'] ?? [])->filter(function($a) {
                        $type = strtolower($a['type'] ?? '');
                        return !in_array($type, ['mp4', 'avi', 'mov', 'wmv', 'webm', 'mp3', 'wav', 'ogg', 'm4a', 'jpg', 'jpeg', 'png', 'gif', 'webp', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']);
                    });
                @endphp
                
                @if(!empty($lesson['attachments']) && count($lesson['attachments']) > 0)
                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 10px; box-shadow: var(--shadow-light);">
                    <h3 style="margin-bottom: 1rem;"><i class="fas fa-download"></i> Downloads & Resources</h3>
                    
                    @if($videoAttachments->count() > 0)
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: var(--primary-color); font-size: 1rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-video"></i> Video Files
                        </h4>
                        @foreach($videoAttachments as $attachment)
                        <div class="download-item" style="display: flex; align-items: center; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 5px; margin-bottom: 0.5rem; transition: all 0.2s;">
                            <i class="{{ getAttachmentIcon($attachment['type'] ?? 'file') }}" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                            <div style="flex: 1;">
                                <strong>{{ $attachment['name'] ?? 'Video File' }}</strong>
                                <br>
                                <small style="color: #666;">
                                    {{ strtoupper($attachment['type'] ?? 'VIDEO') }} 
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </small>
                            </div>
                            <a href="{{ $attachment['url'] ?? '#' }}" class="btn btn-sm btn-primary" download style="padding: 0.5rem 1rem;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    @if($audioAttachments->count() > 0)
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: var(--secondary-color); font-size: 1rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-headphones"></i> Audio Files
                        </h4>
                        @foreach($audioAttachments as $attachment)
                        <div class="download-item" style="display: flex; align-items: center; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 5px; margin-bottom: 0.5rem;">
                            <i class="{{ getAttachmentIcon($attachment['type'] ?? 'file') }}" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                            <div style="flex: 1;">
                                <strong>{{ $attachment['name'] ?? 'Audio File' }}</strong>
                                <br>
                                <small style="color: #666;">
                                    {{ strtoupper($attachment['type'] ?? 'AUDIO') }} 
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </small>
                            </div>
                            <a href="{{ $attachment['url'] ?? '#' }}" class="btn btn-sm btn-primary" download style="padding: 0.5rem 1rem;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    @if($imageAttachments->count() > 0)
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #00BCD4; font-size: 1rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-images"></i> Images
                        </h4>
                        <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(120px, 1fr)); gap: 0.75rem;">
                            @foreach($imageAttachments as $attachment)
                            <div style="position: relative; border: 1px solid #e0e0e0; border-radius: 5px; overflow: hidden;">
                                <img src="{{ $attachment['url'] ?? '#' }}" alt="{{ $attachment['name'] ?? 'Image' }}" 
                                     style="width: 100%; height: 120px; object-fit: cover;">
                                <div style="padding: 0.5rem; background: white;">
                                    <small style="display: block; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                                        {{ Str::limit($attachment['name'] ?? 'Image', 15) }}
                                    </small>
                                    <a href="{{ $attachment['url'] ?? '#' }}" class="btn btn-sm btn-primary" download 
                                       style="width: 100%; margin-top: 0.25rem; padding: 0.25rem; font-size: 0.75rem;">
                                        <i class="fas fa-download"></i>
                                    </a>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($documentAttachments->count() > 0)
                    <div style="margin-bottom: 1.5rem;">
                        <h4 style="color: #d32f2f; font-size: 1rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-file-alt"></i> Documents
                        </h4>
                        @foreach($documentAttachments as $attachment)
                        <div class="download-item" style="display: flex; align-items: center; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 5px; margin-bottom: 0.5rem;">
                            <i class="{{ getAttachmentIcon($attachment['type'] ?? 'file') }}" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                            <div style="flex: 1;">
                                <strong>{{ $attachment['name'] ?? 'Document' }}</strong>
                                <br>
                                <small style="color: #666;">
                                    {{ strtoupper($attachment['type'] ?? 'FILE') }} 
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </small>
                            </div>
                            <a href="{{ $attachment['url'] ?? '#' }}" class="btn btn-sm btn-primary" download style="padding: 0.5rem 1rem;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                    
                    @if($otherAttachments->count() > 0)
                    <div>
                        <h4 style="color: #757575; font-size: 1rem; margin-bottom: 0.75rem; display: flex; align-items: center; gap: 0.5rem;">
                            <i class="fas fa-paperclip"></i> Other Files
                        </h4>
                        @foreach($otherAttachments as $attachment)
                        <div class="download-item" style="display: flex; align-items: center; padding: 0.75rem; border: 1px solid #e0e0e0; border-radius: 5px; margin-bottom: 0.5rem;">
                            <i class="{{ getAttachmentIcon($attachment['type'] ?? 'file') }}" style="font-size: 1.5rem; margin-right: 1rem;"></i>
                            <div style="flex: 1;">
                                <strong>{{ $attachment['name'] ?? 'File' }}</strong>
                                <br>
                                <small style="color: #666;">
                                    {{ strtoupper($attachment['type'] ?? 'FILE') }} 
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </small>
                            </div>
                            <a href="{{ $attachment['url'] ?? '#' }}" class="btn btn-sm btn-primary" download style="padding: 0.5rem 1rem;">
                                <i class="fas fa-download"></i> Download
                            </a>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>
                @endif
            </aside>
        </div>

        @if(count($relatedLessons) > 0)
        <div style="margin-top: 4rem;">
            <h2 style="color: var(--primary-color); margin-bottom: 2rem; text-align: center;">Related Lessons</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 2rem;">
                @foreach($relatedLessons as $related)
                <div class="lesson-card">
                    <div class="lesson-thumbnail" style="position: relative; overflow: hidden;">
                        @if(isset($related['thumbnail']) && !in_array($related['thumbnail'], ['default.jpg', 'video-placeholder.jpg', 'audio-placeholder.jpg']))
                            <img src="{{ $related['thumbnail'] }}" alt="{{ $related['title'] }}" 
                                 style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;">
                        @else
                            <div style="width: 100%; height: 100%; background: linear-gradient(135deg, var(--primary-color) 0%, var(--secondary-color) 100%); display: flex; align-items: center; justify-content: center;">
                                <i class="fas fa-book" style="font-size: 3rem; color: white;"></i>
                            </div>
                        @endif
                    </div>
                    <div class="lesson-info">
                        <h3>{{ $related['title'] }}</h3>
                        <p class="scripture">{{ $related['scripture'] }}</p>
                        <p class="theme">{{ $related['theme'] }}</p>
                        <div class="lesson-meta">
                            <span><i class="fas fa-users"></i> {{ $related['age_group'] }}</span>
                            <span><i class="fas fa-clock"></i> {{ $related['duration'] }}</span>
                        </div>
                        <a href="{{ route('lessons.show', $related['id']) }}" class="btn btn-primary" style="width: 100%; margin-top: 1rem;">View Lesson</a>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>

@push('scripts')
<script>
function shareLesson() {
    if (navigator.share) {
        navigator.share({
            title: '{{ $lesson["title"] }} - SundayLearn',
            text: '{{ $lesson["overview"] }}',
            url: window.location.href
        }).catch(err => console.log('Error sharing:', err));
    } else {
        // Fallback: copy to clipboard
        navigator.clipboard.writeText(window.location.href).then(() => {
            alert('Link copied to clipboard!');
        });
    }
}
</script>
@endpush
@endsection
