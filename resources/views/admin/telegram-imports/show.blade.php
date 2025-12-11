@extends('layouts.app')

@section('title', 'Telegram Import #' . $import->id . ' - Admin')

@section('content')
<div style="padding: 2rem 0; background: var(--background-light);">
    <div class="container">
        <a href="{{ route('admin.telegram-imports.index') }}" style="color: var(--primary-color); text-decoration: none; margin-bottom: 2rem; display: inline-block;">
            <i class="fas fa-arrow-left"></i> Back to Imports
        </a>

        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div>
                <div style="background: var(--background-white); padding: 2rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 2rem;">
                    <h1 style="color: var(--primary-color); margin-bottom: 1rem;">Import #{{ $import->id }}</h1>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem; margin-bottom: 2rem;">
                        <div>
                            <strong>Telegram Message ID:</strong><br>
                            {{ $import->telegram_message_id }}
                        </div>
                        <div>
                            <strong>Status:</strong><br>
                            @php
                                $statusColors = [
                                    'pending' => '#ff9800',
                                    'processing' => '#2196f3',
                                    'completed' => '#4caf50',
                                    'failed' => '#f44336',
                                ];
                            @endphp
                            <span style="background: {{ $statusColors[$import->processing_status] ?? '#666' }}; color: white; padding: 0.25rem 0.5rem; border-radius: 12px; font-size: 0.9rem;">
                                {{ ucfirst($import->processing_status) }}
                            </span>
                        </div>
                        <div>
                            <strong>Media Type:</strong><br>
                            {{ $import->media_type ? ucfirst($import->media_type) : 'Text Only' }}
                        </div>
                        <div>
                            <strong>Created:</strong><br>
                            {{ $import->created_at->format('M j, Y H:i:s') }}
                        </div>
                    </div>

                    @if($import->caption)
                    <div style="margin-bottom: 2rem;">
                        <strong>Caption/Text:</strong>
                        <div style="background: var(--background-light); padding: 1rem; border-radius: 5px; margin-top: 0.5rem; white-space: pre-wrap;">{{ $import->caption }}</div>
                    </div>
                    @endif

                    @if($import->file_url)
                    <div style="margin-bottom: 2rem;">
                        <strong>Media File:</strong><br>
                        <a href="{{ $import->file_url }}" target="_blank" class="btn btn-primary" style="margin-top: 0.5rem;">
                            <i class="fas fa-external-link-alt"></i> View File
                        </a>
                    </div>
                    @endif

                    @if($import->processing_notes)
                    <div style="margin-bottom: 2rem;">
                        <strong>Processing Notes:</strong>
                        <div style="background: #fff3cd; border: 1px solid #ffeaa7; padding: 1rem; border-radius: 5px; margin-top: 0.5rem;">{{ $import->processing_notes }}</div>
                    </div>
                    @endif

                    @if($import->lesson)
                    <div style="margin-bottom: 2rem;">
                        <strong>Associated Lesson:</strong><br>
                        <a href="{{ route('lessons.show', $import->lesson) }}" class="btn btn-secondary" style="margin-top: 0.5rem;">
                            <i class="fas fa-book"></i> {{ $import->lesson->title }}
                        </a>
                    </div>
                    @endif
                </div>

                <!-- Raw Telegram Data -->
                <div style="background: var(--background-white); padding: 2rem; border-radius: 10px; box-shadow: var(--shadow-light);">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Raw Telegram Data</h3>
                    <pre style="background: #f8f9fa; padding: 1rem; border-radius: 5px; overflow-x: auto; font-size: 0.8rem;">{{ json_encode($import->telegram_data, JSON_PRETTY_PRINT) }}</pre>
                </div>
            </div>

            <div>
                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 10px; box-shadow: var(--shadow-light); margin-bottom: 1.5rem;">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Quick Actions</h3>
                    
                    @if($import->processing_status === 'pending')
                    <button class="btn btn-primary" style="width: 100%; margin-bottom: 0.5rem;">
                        <i class="fas fa-play"></i> Process Now
                    </button>
                    @endif
                    
                    @if($import->processing_status === 'failed')
                    <button class="btn btn-secondary" style="width: 100%; margin-bottom: 0.5rem;">
                        <i class="fas fa-redo"></i> Retry Processing
                    </button>
                    @endif
                    
                    <button class="btn btn-outline" style="width: 100%;">
                        <i class="fas fa-trash"></i> Delete Import
                    </button>
                </div>

                <div style="background: var(--background-white); padding: 1.5rem; border-radius: 10px; box-shadow: var(--shadow-light);">
                    <h3 style="color: var(--primary-color); margin-bottom: 1rem;">Import Details</h3>
                    
                    <div style="margin-bottom: 1rem;">
                        <strong>File ID:</strong><br>
                        <code style="background: var(--background-light); padding: 0.25rem; border-radius: 3px; font-size: 0.8rem;">
                            {{ $import->telegram_file_id ?: 'N/A' }}
                        </code>
                    </div>
                    
                    <div style="margin-bottom: 1rem;">
                        <strong>File Path:</strong><br>
                        <code style="background: var(--background-light); padding: 0.25rem; border-radius: 3px; font-size: 0.8rem;">
                            {{ $import->file_path ?: 'N/A' }}
                        </code>
                    </div>
                    
                    <div>
                        <strong>Updated:</strong><br>
                        {{ $import->updated_at->format('M j, Y H:i:s') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection