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

        <div style="display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap;">
            <a href="{{ route('lessons.index') }}" style="color: var(--primary-color); text-decoration: none;">
                <i class="fas fa-arrow-left"></i> Back to Lessons
            </a>
            <div style="margin-left: auto;">
                <button onclick="shareLesson()" class="btn btn-outline" style="padding: 0.5rem 1rem;">
                    <i class="fas fa-share-alt"></i> Share
                </button>
            </div>
        </div>

        <div class="lesson-layout">
            <div class="lesson-main">
                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 10px; box-shadow: var(--shadow-light);">
                    <h1 style="color: var(--primary-color); margin-bottom: 1rem; font-size: 1.8rem; line-height: 1.3;">{{ $lesson['title'] }}</h1>
                    
                    <div class="lesson-meta">
                        <div class="meta-item">
                            <strong>Scripture:</strong> {{ $lesson['scripture'] }}
                        </div>
                        <div class="meta-item">
                            <strong>Theme:</strong> {{ $lesson['theme'] }}
                        </div>
                        <div class="meta-item">
                            <strong>Age:</strong> {{ $lesson['age_group'] }}
                        </div>
                        <div class="meta-item">
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

            <aside class="lesson-sidebar">
                @php
                    $videoAttachments = collect($lesson['attachments'] ?? [])->map(function($a, $index) {
                        $a['original_index'] = $index;
                        return $a;
                    })->filter(function($a) {
                        return in_array(strtolower($a['type'] ?? ''), ['mp4', 'avi', 'mov', 'wmv', 'webm']);
                    });
                    $audioAttachments = collect($lesson['attachments'] ?? [])->map(function($a, $index) {
                        $a['original_index'] = $index;
                        return $a;
                    })->filter(function($a) {
                        return in_array(strtolower($a['type'] ?? ''), ['mp3', 'wav', 'ogg', 'm4a']);
                    });
                    
                    $hasVideo = !empty($lesson['video_url']) || $videoAttachments->count() > 0;
                    $hasAudio = !empty($lesson['audio_url']) || $audioAttachments->count() > 0;
                @endphp
                
                @if($hasVideo || $hasAudio)
                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 1.5rem;">
                    <h3 style="margin-bottom: 1rem;">Multimedia Resources</h3>
                    
                    @if($hasVideo)
                    <div class="media-item" style="margin-bottom: 1.5rem;">
                        <h4><i class="fas fa-video"></i> Video Lesson</h4>
                        <div style="margin-top: 0.5rem;">
                            @if(!empty($lesson['video_url']))
                                @php
                                    $videoUrl = $lesson['video_url'];
                                    $isYouTube = preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([^&\n?#]+)/', $videoUrl, $matches);
                                    $isVimeo = preg_match('/vimeo\.com\/(\d+)/', $videoUrl, $vimeoMatches);
                                @endphp
                                
                                @if($isYouTube)
                                    {{-- YouTube Embed --}}
                                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px;">
                                        <iframe 
                                            src="https://www.youtube.com/embed/{{ $matches[1] }}?rel=0&modestbranding=1" 
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @elseif($isVimeo)
                                    {{-- Vimeo Embed --}}
                                    <div style="position: relative; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px;">
                                        <iframe 
                                            src="https://player.vimeo.com/video/{{ $vimeoMatches[1] }}" 
                                            style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; border: 0;"
                                            allowfullscreen>
                                        </iframe>
                                    </div>
                                @else
                                    {{-- Direct Video URL --}}
                                    <video controls style="width: 100%; border-radius: 8px; background: #000;">
                                        <source src="{{ $videoUrl }}" type="video/mp4">
                                        <p style="color: #666; text-align: center; padding: 2rem;">
                                            Your browser doesn't support video playback. 
                                            <a href="{{ $videoUrl }}" target="_blank">Download the video</a>
                                        </p>
                                    </video>
                                @endif
                            @elseif($videoAttachments->count() > 0)
                                {{-- Video Attachments - Direct Player --}}
                                @foreach($videoAttachments->take(1) as $video)
                                    @php
                                        $videoUrl = $video['url'] ?? '#';
                                        $videoType = strtolower($video['type'] ?? 'mp4');
                                        
                                        // Ensure URL is properly formatted
                                        if ($videoUrl !== '#' && !str_starts_with($videoUrl, 'http')) {
                                            // If it's a relative path, make it absolute
                                            if (str_starts_with($videoUrl, '/')) {
                                                $videoUrl = url($videoUrl);
                                            } else {
                                                $videoUrl = asset('storage/' . $videoUrl);
                                            }
                                        }
                                        
                                        $mimeType = match($videoType) {
                                            'mp4' => 'video/mp4',
                                            'webm' => 'video/webm',
                                            'avi' => 'video/x-msvideo',
                                            'mov' => 'video/quicktime',
                                            'wmv' => 'video/x-ms-wmv',
                                            'mkv' => 'video/x-matroska',
                                            'flv' => 'video/x-flv',
                                            default => 'video/mp4'
                                        };
                                    @endphp
                                    
                                    <div class="video-player-container" style="background: #000; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 12px rgba(0,0,0,0.15); margin-bottom: 1rem;">
                                        {{-- Clean Video Player --}}
                                        <video 
                                            controls 
                                            preload="metadata"
                                            controls 
                                            preload="auto"
                                            poster=""
                                            style="width: 100%; max-height: 400px; display: block; background: #000;"
                                            onloadstart="console.log('Video loading:', this.src)"
                                            oncanplay="console.log('Video ready to play')"
                                            onerror="handleVideoError(this)"
                                            data-original-url="{{ $video['url'] ?? '#' }}"
                                            data-processed-url="{{ $videoUrl }}">
                                            
                                            <source src="{{ $videoUrl }}" type="{{ $mimeType }}">
                                            @if($videoType !== 'mp4')
                                                <source src="{{ $videoUrl }}" type="video/mp4">
                                            @endif
                                            
                                            {{-- Test with a known working video for debugging --}}
                                            @if(config('app.debug'))
                                                <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4" type="video/mp4">
                                            @endif
                                            
                                            {{-- Simple fallback --}}
                                            <p style="color: #fff; text-align: center; padding: 2rem;">
                                                Your browser doesn't support video playback. 
                                                <a href="{{ $videoUrl }}" style="color: #4CAF50;" download>Download the video</a>
                                            </p>
                                        </video>
                                    </div>
                                    
                                    {{-- Video Info Below --}}
                                    <div style="background: #f8f9fa; padding: 1rem; border-radius: 8px; margin-bottom: 1rem;">
                                        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.9rem;">
                                            <div>
                                                <strong style="color: #333;">{{ $video['name'] ?? 'Video Lesson' }}</strong>
                                                <div style="color: #666; font-size: 0.8rem; margin-top: 0.25rem;">
                                                    <i class="fas fa-file-video"></i> {{ strtoupper($videoType) }} Format
                                                </div>
                                            </div>
                                            @if(isset($video['size']))
                                                <div style="text-align: right; color: #666;">
                                                    <i class="fas fa-hdd"></i> {{ number_format($video['size'] / 1024 / 1024, 1) }} MB
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    @if($videoAttachments->count() > 1)
                                        <p style="margin-top: 0.75rem; font-size: 0.9rem; color: #666; text-align: center;">
                                            <i class="fas fa-info-circle"></i> 
                                            {{ $videoAttachments->count() - 1 }} more video(s) available in downloads below
                                        </p>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($hasAudio)
                    <div class="media-item">
                        <h4><i class="fas fa-volume-up"></i> Audio Story</h4>
                        <div style="margin-top: 0.5rem;">
                            @if(!empty($lesson['audio_url']))
                                {{-- Audio URL --}}
                                <audio controls style="width: 100%; border-radius: 8px;">
                                    <source src="{{ $lesson['audio_url'] }}" type="audio/mpeg">
                                    <p style="color: #666; text-align: center; padding: 1rem;">
                                        Your browser doesn't support audio playback. 
                                        <a href="{{ $lesson['audio_url'] }}" target="_blank">Download the audio</a>
                                    </p>
                                </audio>
                            @elseif($audioAttachments->count() > 0)
                                {{-- Audio Attachments --}}
                                @foreach($audioAttachments->take(1) as $audio)
                                    <audio controls style="width: 100%; border-radius: 8px;">
                                        <source src="{{ $audio['url'] ?? '#' }}" type="audio/{{ strtolower($audio['type'] ?? 'mp3') }}">
                                        <p style="color: #666; text-align: center; padding: 1rem;">
                                            Your browser doesn't support audio playback. 
                                            <a href="{{ $audio['url'] ?? '#' }}" download>Download the audio</a>
                                        </p>
                                    </audio>
                                    @if($audioAttachments->count() > 1)
                                        <p style="margin-top: 0.5rem; font-size: 0.9rem; color: #666;">
                                            <i class="fas fa-info-circle"></i> 
                                            {{ $audioAttachments->count() - 1 }} more audio file(s) available in downloads below
                                        </p>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>
                    @endif
                </div>
                @endif

                @php
                    $imageAttachments = collect($lesson['attachments'] ?? [])->map(function($a, $index) {
                        $a['original_index'] = $index;
                        return $a;
                    })->filter(function($a) {
                        return in_array(strtolower($a['type'] ?? ''), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
                    });
                    $documentAttachments = collect($lesson['attachments'] ?? [])->map(function($a, $index) {
                        $a['original_index'] = $index;
                        return $a;
                    })->filter(function($a) {
                        return in_array(strtolower($a['type'] ?? ''), ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt']);
                    });
                    $otherAttachments = collect($lesson['attachments'] ?? [])->map(function($a, $index) {
                        $a['original_index'] = $index;
                        return $a;
                    })->filter(function($a) {
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
                        <div class="download-item">
                            <i class="{{ getAttachmentIcon($attachment['type'] ?? 'file') }}"></i>
                            <div class="download-info">
                                <strong>{{ $attachment['name'] ?? 'Video File' }}</strong>
                                <br>
                                <small>
                                    {{ strtoupper($attachment['type'] ?? 'VIDEO') }} 
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </small>
                            </div>
                            <a href="{{ route('lessons.download-attachment', ['lesson' => $lesson['id'], 'attachment' => $attachment['original_index']]) }}" 
                               class="btn btn-sm btn-primary download-btn">
                                <i class="fas fa-download"></i> <span class="btn-text">Download</span>
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
                        <div class="download-item">
                            <i class="{{ getAttachmentIcon($attachment['type'] ?? 'file') }}"></i>
                            <div class="download-info">
                                <strong>{{ $attachment['name'] ?? 'Audio File' }}</strong>
                                <br>
                                <small>
                                    {{ strtoupper($attachment['type'] ?? 'AUDIO') }} 
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </small>
                            </div>
                            <a href="{{ route('lessons.download-attachment', ['lesson' => $lesson['id'], 'attachment' => $attachment['original_index']]) }}" 
                               class="btn btn-sm btn-primary download-btn">
                                <i class="fas fa-download"></i> <span class="btn-text">Download</span>
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
                        <div class="image-grid">
                            @foreach($imageAttachments as $attachment)
                            <div class="image-item">
                                <img src="{{ $attachment['url'] ?? '#' }}" alt="{{ $attachment['name'] ?? 'Image' }}">
                                <div class="image-info">
                                    <small>{{ Str::limit($attachment['name'] ?? 'Image', 15) }}</small>
                                    <a href="{{ route('lessons.download-attachment', ['lesson' => $lesson['id'], 'attachment' => $attachment['original_index']]) }}" 
                                       class="btn btn-sm btn-primary">
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
                        <div class="download-item">
                            <i class="{{ getAttachmentIcon($attachment['type'] ?? 'file') }}"></i>
                            <div class="download-info">
                                <strong>{{ $attachment['name'] ?? 'Document' }}</strong>
                                <br>
                                <small>
                                    {{ strtoupper($attachment['type'] ?? 'FILE') }} 
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </small>
                            </div>
                            <a href="{{ route('lessons.download-attachment', ['lesson' => $lesson['id'], 'attachment' => $attachment['original_index']]) }}" 
                               class="btn btn-sm btn-primary download-btn">
                                <i class="fas fa-download"></i> <span class="btn-text">Download</span>
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
                        <div class="download-item">
                            <i class="{{ getAttachmentIcon($attachment['type'] ?? 'file') }}"></i>
                            <div class="download-info">
                                <strong>{{ $attachment['name'] ?? 'File' }}</strong>
                                <br>
                                <small>
                                    {{ strtoupper($attachment['type'] ?? 'FILE') }} 
                                    @if(isset($attachment['size']))
                                        - {{ number_format($attachment['size'] / 1024 / 1024, 2) }} MB
                                    @endif
                                </small>
                            </div>
                            <a href="{{ route('lessons.download-attachment', ['lesson' => $lesson['id'], 'attachment' => $attachment['original_index']]) }}" 
                               class="btn btn-sm btn-primary download-btn">
                                <i class="fas fa-download"></i> <span class="btn-text">Download</span>
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

@push('styles')
<style>
/* Mobile-First Responsive Design for Lesson View */
.lesson-layout {
    display: flex;
    flex-direction: column;
    gap: 1.5rem;
}

.lesson-main {
    order: 1;
}

.lesson-sidebar {
    order: 2;
}

.lesson-meta {
    display: grid;
    grid-template-columns: 1fr;
    gap: 0.75rem;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 2px solid var(--border-color);
}

.meta-item {
    padding: 0.5rem;
    background: #f8f9fa;
    border-radius: 5px;
    font-size: 0.9rem;
}

/* Download Items - Mobile First */
.download-item {
    display: flex;
    align-items: center;
    padding: 0.75rem;
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    margin-bottom: 0.5rem;
    transition: all 0.2s;
    gap: 0.75rem;
}

.download-item i {
    font-size: 1.2rem;
    flex-shrink: 0;
    color: #666;
}

.download-info {
    flex: 1;
    min-width: 0; /* Allows text to truncate */
}

.download-info strong {
    display: block;
    font-size: 0.9rem;
    line-height: 1.3;
    margin-bottom: 0.25rem;
    word-break: break-word;
}

.download-info small {
    color: #666;
    font-size: 0.75rem;
}

.download-btn {
    padding: 0.5rem 0.75rem !important;
    font-size: 0.8rem !important;
    flex-shrink: 0;
    white-space: nowrap;
}

.download-btn .btn-text {
    display: none;
}

/* Image Grid - Mobile First */
.image-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
    gap: 0.5rem;
}

.image-item {
    border: 1px solid #e0e0e0;
    border-radius: 5px;
    overflow: hidden;
}

.image-item img {
    width: 100%;
    height: 80px;
    object-fit: cover;
}

.image-info {
    padding: 0.5rem;
    background: white;
}

.image-info small {
    display: block;
    font-weight: 600;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    margin-bottom: 0.25rem;
    font-size: 0.7rem;
}

.image-info .btn {
    width: 100%;
    padding: 0.25rem;
    font-size: 0.7rem;
}

/* Tablet Styles */
@media (min-width: 768px) {
    .lesson-layout {
        display: grid;
        grid-template-columns: 1fr 300px;
        gap: 2rem;
    }
    
    .lesson-main {
        order: 0;
    }
    
    .lesson-sidebar {
        order: 0;
    }
    
    .lesson-meta {
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }
    
    .meta-item {
        font-size: 1rem;
    }
    
    .download-item i {
        font-size: 1.5rem;
        margin-right: 0.5rem;
    }
    
    .download-info strong {
        font-size: 1rem;
    }
    
    .download-info small {
        font-size: 0.85rem;
    }
    
    .download-btn {
        padding: 0.5rem 1rem !important;
        font-size: 0.9rem !important;
    }
    
    .download-btn .btn-text {
        display: inline;
    }
    
    .image-grid {
        grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
        gap: 0.75rem;
    }
    
    .image-item img {
        height: 100px;
    }
    
    .image-info small {
        font-size: 0.8rem;
    }
    
    .image-info .btn {
        font-size: 0.75rem;
    }
}

/* Desktop Styles */
@media (min-width: 1024px) {
    .lesson-layout {
        grid-template-columns: 2fr 1fr;
    }
    
    .lesson-meta {
        grid-template-columns: repeat(4, 1fr);
    }
    
    .download-item {
        padding: 1rem;
    }
    
    .download-item i {
        font-size: 1.5rem;
        margin-right: 1rem;
    }
    
    .image-item img {
        height: 120px;
    }
}

/* Media Player Styles */
.media-item video,
.media-item audio {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    transition: box-shadow 0.3s ease;
}

.media-item video:hover {
    box-shadow: 0 6px 12px rgba(0,0,0,0.15);
}

.media-item iframe {
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

/* Responsive video containers */
.video-container {
    position: relative;
    padding-bottom: 56.25%;
    height: 0;
    overflow: hidden;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.video-container iframe,
.video-container video {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    border: 0;
}

/* Audio player styling */
audio {
    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
    border: 1px solid #dee2e6;
    padding: 0.5rem;
}

audio::-webkit-media-controls-panel {
    background-color: #f8f9fa;
}

/* Video Player Container */
.video-player-container {
    transition: all 0.3s ease;
    position: relative;
}

.video-player-container:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.2) !important;
}

/* Ensure video is clickable and responsive */
.video-player-container video {
    cursor: pointer;
    outline: none;
    border: none;
}

.video-player-container video:focus {
    outline: 2px solid #007bff;
    outline-offset: 2px;
}

/* CSS Removed to restore native controls */

/* Mobile responsive media */
@media (max-width: 768px) {
    .media-item video,
    .media-item audio {
        border-radius: 5px;
    }
    
    .media-item h4 {
        font-size: 0.95rem;
    }
    
    .video-player-container {
        border-radius: 8px;
    }
    
    .video-player-container video {
        min-height: 200px !important;
    }
}

/* Print Styles */
@media print {
    .lesson-sidebar {
        display: none;
    }
    
    .lesson-layout {
        display: block;
    }
    
    .media-item {
        display: none;
    }
}
</style>
@endpush

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

function printCleanLesson() {
    // Create a clean print version
    const printWindow = window.open('', '_blank');
    const lessonContent = document.querySelector('.lesson-main').innerHTML;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>{{ $lesson["title"] }} - SundayLearn</title>
            <style>
                body { 
                    font-family: 'Times New Roman', serif; 
                    margin: 1.5rem; 
                    line-height: 1.8; 
                    color: #333;
                    font-size: 12pt;
                }
                h1 { 
                    color: #2c3e50; 
                    border-bottom: 3px solid #3498db; 
                    padding-bottom: 0.5rem; 
                    font-size: 18pt;
                    margin-bottom: 1rem;
                }
                h2 { 
                    color: #34495e; 
                    margin-top: 1.5rem; 
                    font-size: 14pt;
                    border-left: 4px solid #3498db;
                    padding-left: 0.5rem;
                }
                .lesson-meta { 
                    background: #f8f9fa; 
                    padding: 1rem; 
                    border-radius: 5px; 
                    margin: 1rem 0;
                    border: 1px solid #dee2e6;
                }
                .meta-item { 
                    margin: 0.5rem 0; 
                    font-weight: bold;
                }
                .objectives-list { 
                    margin: 1rem 0; 
                    padding-left: 1.5rem;
                }
                .objectives-list li {
                    margin: 0.5rem 0;
                }
                .question-item { 
                    margin: 0.75rem 0; 
                    padding: 0.75rem; 
                    background: #f8f9fa; 
                    border-radius: 5px;
                    border-left: 4px solid #28a745;
                }
                .lesson-section { 
                    margin: 1.5rem 0; 
                    page-break-inside: avoid;
                }
                .lesson-section p {
                    text-align: justify;
                    margin: 0.75rem 0;
                }
                @media print {
                    body { margin: 1rem; font-size: 11pt; }
                    h1 { font-size: 16pt; }
                    h2 { font-size: 13pt; }
                    .lesson-section { page-break-inside: avoid; }
                }
                @page {
                    margin: 1in;
                    @bottom-center {
                        content: "Page " counter(page) " of " counter(pages);
                    }
                }
            </style>
        </head>
        <body>
            ${lessonContent}
            <div style="margin-top: 2rem; padding-top: 1rem; border-top: 2px solid #ddd; font-size: 10pt; color: #666; text-align: center;">
                <p><strong>SundayLearn.com</strong> - Free Sunday School Resources</p>
                <p>Downloaded from: {{ url()->current() }}</p>
                <p>© {{ date('Y') }} SundayLearn. All rights reserved. Free for educational use.</p>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 750);
}

function exportToPDF() {
    // Show loading message
    const originalBtn = event.target;
    const originalText = originalBtn.innerHTML;
    originalBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Generating PDF...';
    originalBtn.disabled = true;
    
    // Use browser's print to PDF functionality
    const printWindow = window.open('', '_blank');
    const lessonContent = document.querySelector('.lesson-main').innerHTML;
    
    printWindow.document.write(`
        <!DOCTYPE html>
        <html>
        <head>
            <title>{{ $lesson["title"] }} - SundayLearn</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    margin: 0; 
                    padding: 2rem; 
                    line-height: 1.6; 
                    color: #333;
                }
                h1 { 
                    color: #2c3e50; 
                    border-bottom: 3px solid #3498db; 
                    padding-bottom: 0.5rem; 
                    margin-bottom: 1.5rem;
                }
                h2 { 
                    color: #34495e; 
                    margin-top: 2rem; 
                    border-left: 4px solid #3498db;
                    padding-left: 0.75rem;
                }
                .lesson-meta { 
                    background: #f8f9fa; 
                    padding: 1.5rem; 
                    border-radius: 8px; 
                    margin: 1.5rem 0;
                    border: 1px solid #dee2e6;
                    display: grid;
                    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
                    gap: 1rem;
                }
                .meta-item { 
                    font-weight: bold;
                    padding: 0.5rem;
                    background: white;
                    border-radius: 4px;
                }
                .objectives-list { 
                    margin: 1rem 0; 
                    padding-left: 2rem;
                }
                .objectives-list li {
                    margin: 0.75rem 0;
                }
                .question-item { 
                    margin: 1rem 0; 
                    padding: 1rem; 
                    background: #e8f5e8; 
                    border-radius: 8px;
                    border-left: 5px solid #28a745;
                }
                .lesson-section { 
                    margin: 2rem 0; 
                    page-break-inside: avoid;
                }
                .lesson-section p {
                    text-align: justify;
                    margin: 1rem 0;
                }
            </style>
        </head>
        <body>
            ${lessonContent}
            <div style="margin-top: 3rem; padding-top: 1.5rem; border-top: 2px solid #ddd; text-align: center; color: #666;">
                <h3 style="color: #3498db; margin-bottom: 1rem;">SundayLearn.com</h3>
                <p>Free Sunday School Resources for Teachers</p>
                <p>Downloaded from: {{ url()->current() }}</p>
                <p>© {{ date('Y') }} SundayLearn. All rights reserved. Free for educational use.</p>
            </div>
        </body>
        </html>
    `);
    
    printWindow.document.close();
    printWindow.focus();
    
    setTimeout(() => {
        // Restore button
        originalBtn.innerHTML = originalText;
        originalBtn.disabled = false;
        
        // Show instructions
        alert('To save as PDF:\n1. Press Ctrl+P (or Cmd+P on Mac)\n2. Select "Save as PDF" as destination\n3. Click Save');
        
        printWindow.close();
    }, 1000);
}

function handleVideoError(videoElement) {
    console.error('Video failed to load:', videoElement.src);
    console.error('Video error details:', videoElement.error);
    
    // Simple error handling - just show a message below the video
    const container = videoElement.parentNode;
    const errorDiv = document.createElement('div');
    errorDiv.style.cssText = `
        background: #f8d7da; 
        color: #721c24; 
        padding: 1rem; 
        border-radius: 4px; 
        margin-top: 0.5rem;
        border: 1px solid #f5c6cb;
    `;
    
    errorDiv.innerHTML = `
        <strong><i class="fas fa-exclamation-triangle"></i> Video Error:</strong> 
        Unable to load video. <a href="${videoElement.src}" target="_blank" style="color: #721c24;">Try opening directly</a> or 
        <a href="${videoElement.src}" download style="color: #721c24;">download the file</a>.
    `;
    
    container.appendChild(errorDiv);
}

function testVideoPlayer(button) {
    const container = button.closest('.video-player-container').parentNode;
    const video = container.querySelector('video');
    
    // Test with a known working video
    const testUrl = 'https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4';
    
    console.log('Testing video player with:', testUrl);
    video.src = testUrl;
    video.load();
    
    button.textContent = 'Testing...';
    button.disabled = true;
    
    video.addEventListener('canplay', function() {
        console.log('Test video loaded successfully');
        button.textContent = 'Test Passed!';
        button.style.background = '#28a745';
    }, { once: true });
    
    video.addEventListener('error', function() {
        console.log('Test video failed');
        button.textContent = 'Test Failed';
        button.style.background = '#dc3545';
    }, { once: true });
}

function reloadVideo(button) {
    const container = button.closest('.video-player-container').parentNode;
    const video = container.querySelector('video');
    
    const originalUrl = video.dataset.processedUrl;
    console.log('Reloading video with:', originalUrl);
    
    video.src = originalUrl;
    video.load();
    
    button.textContent = 'Reloading...';
    button.disabled = true;
    
    setTimeout(() => {
        button.textContent = 'Reload Video';
        button.disabled = false;
    }, 2000);
}

// Initialize video players on page load
document.addEventListener('DOMContentLoaded', function() {
    const videos = document.querySelectorAll('video');
    console.log('Found', videos.length, 'video elements on page');
    
    videos.forEach((video, index) => {
        console.log(`Video ${index + 1}:`, {
            src: video.src,
            sources: Array.from(video.querySelectorAll('source')).map(s => ({src: s.src, type: s.type})),
            readyState: video.readyState,
            networkState: video.networkState
        });
        
        // Ensure video is interactive
        video.style.pointerEvents = 'auto';
        video.setAttribute('playsinline', '');
        
        // Click handler removed to prevent conflict with native controls
        
        // Add loading state feedback
        video.addEventListener('loadstart', function() {
            console.log('Video loading started');
            video.style.opacity = '0.7';
        });
        
        video.addEventListener('canplay', function() {
            console.log('Video can play');
            video.style.opacity = '1';
        });
        
        video.addEventListener('loadeddata', function() {
            console.log('Video data loaded');
            video.style.opacity = '1';
        });
        
        // Test if video URL is accessible
        const videoSrc = video.src || (video.querySelector('source') ? video.querySelector('source').src : '');
        if (videoSrc) {
            fetch(videoSrc, {method: 'HEAD'})
                .then(response => {
                    console.log(`Video ${index + 1} URL test:`, response.status, response.statusText);
                    if (!response.ok) {
                        console.warn(`Video ${index + 1} may not be accessible:`, response.status);
                    }
                })
                .catch(error => {
                    console.error(`Video ${index + 1} URL test failed:`, error);
                });
        }
    });
});
</script>
@endpush
@endsection
