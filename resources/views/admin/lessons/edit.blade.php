@extends('admin.layout')

@section('title', 'Edit Lesson - Admin')

@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Edit Lesson</h1>
            <p class="text-muted">Update the details for "{{ $lesson->title }}".</p>
        </div>
        <div>
            <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Lessons
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.lessons.update', $lesson->id) }}" id="edit-lesson-form">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-8">
                        <div class="mb-3">
                            <label for="title" class="form-label">Lesson Title *</label>
                            <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title', $lesson->title) }}" required>
                            @error('title')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Main Content *</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="15">{{ old('content', $lesson->content) }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card bg-light">
                            <div class="card-body">
                                <h5 class="card-title mb-3">Lesson Properties</h5>
                                <div class="mb-3">
                                    <label for="status" class="form-label">Status</label>
                                    <select class="form-select" id="status" name="status">
                                        <option value="draft" {{ old('status', $lesson->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                        <option value="published" {{ old('status', $lesson->status) == 'published' ? 'selected' : '' }}>Published</option>
                                    </select>
                                </div>
                                <div class="mb-3">
                                    <label for="age_group" class="form-label">Age Group</label>
                                    <input type="text" class="form-control" id="age_group" name="age_group" value="{{ old('age_group', $lesson->age_group) }}">
                                </div>
                                <div class="mb-3">
                                    <label for="category" class="form-label">Category</label>
                                    <input type="text" class="form-control" id="category" name="category" value="{{ old('category', $lesson->category) }}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <h5 class="mb-3">Manage Attachments</h5>
                <div id="existing-attachments">
                    <h6>Existing Files:</h6>
                    @if($lesson->attachments && count($lesson->attachments) > 0)
                        <ul class="list-group mb-3">
                            @foreach($lesson->attachments as $index => $attachment)
                                <li class="list-group-item d-flex justify-content-between align-items-center" id="attachment-item-{{ $index }}">
                                    <a href="{{ $attachment['url'] }}" target="_blank">{{ $attachment['filename'] }}</a>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeAttachment({{ $lesson->id }}, {{ $index }})">Remove</button>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <p>No attachments yet.</p>
                    @endif
                </div>

                <h5 class="mb-3">Add New Attachments</h5>
                <div class="row">
                    <div class="col-md-4">
                        <label class="form-label">Video Attachments</label>
                        <input type="file" class="filepond" name="video_attachments" id="video-uploader" multiple data-file-type="video">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Audio Attachments</label>
                        <input type="file" class="filepond" name="audio_attachments" id="audio-uploader" multiple data-file-type="audio">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">Documents & Other</label>
                        <input type="file" class="filepond" name="document_attachments" id="document-uploader" multiple data-file-type="document">
                    </div>
                </div>

                <div id="attachments-hidden-inputs"></div>

                <div class="mt-4 text-end">
                    <button type="submit" class="btn btn-primary">Update Lesson</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        FilePond.registerPlugin();

        const setupFilePond = (selector, fileType) => {
            const inputElement = document.querySelector(selector);
            FilePond.create(inputElement, {
                allowMultiple: true,
                server: {
                    url: '{{ route("admin.lessons.upload") }}',
                    process: {
                        url: '',
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        ondata: (formData) => {
                            formData.append('fileType', fileType);
                            return formData;
                        },
                        onload: (response) => {
                            const res = JSON.parse(response);
                            if (res.success) {
                                addHiddenInput(fileType, res);
                                return res.path;
                            }
                            return null;
                        }
                    }
                }
            });
        };

        const addHiddenInput = (type, fileData) => {
            const container = document.getElementById('attachments-hidden-inputs');
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = `${type}_attachments[]`;
            input.value = JSON.stringify(fileData);
            container.appendChild(input);
        };

        window.removeAttachment = (lessonId, index) => {
            if (!confirm('Are you sure you want to remove this attachment?')) return;

            fetch(`/admin/lessons/${lessonId}/attachments/${index}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    document.getElementById(`attachment-item-${index}`).remove();
                    alert(data.message);
                } else {
                    alert('Failed to remove attachment.');
                }
            });
        };

        setupFilePond('#video-uploader', 'video');
        setupFilePond('#audio-uploader', 'audio');
        setupFilePond('#document-uploader', 'document');
    });
    </script>
@endpush
