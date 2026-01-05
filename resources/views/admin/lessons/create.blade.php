@extends('admin.layout')

@section('title', 'Create Lesson - Admin')

@push('styles')
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">
    <link href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">Create New Lesson</h1>
            <p class="text-muted">Add a new Sunday school lesson to the curriculum.</p>
        </div>
        <div>
            <a href="{{ route('admin.lessons.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left me-1"></i> Back to Lessons
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs" id="lesson-creation-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="lesson-details-tab" data-bs-toggle="tab" href="#lesson-details" role="tab" aria-controls="lesson-details" aria-selected="true">
                        <i class="fas fa-book-open me-2"></i>Lesson Details
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="lesson-scanner-tab" data-bs-toggle="tab" href="#lesson-scanner" role="tab" aria-controls="lesson-scanner" aria-selected="false">
                        <i class="fas fa-search me-2"></i>Content Scanner
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="lesson-creation-tabs-content">
                <!-- Lesson Details Tab -->
                <div class="tab-pane fade show active" id="lesson-details" role="tabpanel" aria-labelledby="lesson-details-tab">
                    <form method="POST" action="{{ route('admin.lessons.store') }}" id="create-lesson-form">
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Lesson Title *</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror" id="title" name="title" value="{{ old('title') }}" required placeholder="e.g., The Story of David and Goliath">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="content" class="form-label">Main Content *</label>
                                    <textarea class="form-control @error('content') is-invalid @enderror" id="content" name="content" rows="15" placeholder="Enter the main lesson text here...">{{ old('content') }}</textarea>
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
                                                <option value="draft" {{ old('status') == 'draft' ? 'selected' : '' }}>Draft</option>
                                                <option value="published" {{ old('status') == 'published' ? 'selected' : '' }}>Published</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="age_group" class="form-label">Age Group</label>
                                            <input type="text" class="form-control" id="age_group" name="age_group" value="{{ old('age_group') }}" placeholder="e.g., 5-7 years">
                                        </div>
                                        <div class="mb-3">
                                            <label for="category" class="form-label">Category</label>
                                            <input type="text" class="form-control" id="category" name="category" value="{{ old('category') }}" placeholder="e.g., Old Testament">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <hr class="my-4">

                        <h5 class="mb-3">Attachments</h5>
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Video Attachments</label>
                                <input type="file" class="filepond" name="video_attachments" id="video-uploader" multiple data-max-file-size="100MB" data-file-type="video">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Audio Attachments</label>
                                <input type="file" class="filepond" name="audio_attachments" id="audio-uploader" multiple data-max-file-size="100MB" data-file-type="audio">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Documents & Other</label>
                                <input type="file" class="filepond" name="document_attachments" id="document-uploader" multiple data-max-file-size="50MB" data-file-type="document">
                            </div>
                        </div>

                        <div id="attachments-hidden-inputs"></div>

                        <div class="mt-4 text-end">
                            <button type="submit" class="btn btn-primary">Create Lesson</button>
                        </div>
                    </form>
                </div>

                <!-- Lesson Scanner Tab -->
                <div class="tab-pane fade" id="lesson-scanner" role="tabpanel" aria-labelledby="lesson-scanner-tab">
                    <div class="ratio ratio-16x9" style="height: 70vh;">
                        <iframe src="{{ route('admin.content.index') }}" title="Content Scanner" allowfullscreen></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script src="https://unpkg.com/filepond/dist/filepond.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.js"></script>
    <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        FilePond.registerPlugin(
            FilePondPluginFileValidateSize,
            FilePondPluginFileValidateType
        );

        const setupFilePond = (selector, fileType) => {
            const inputElement = document.querySelector(selector);
            const pond = FilePond.create(inputElement, {
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
                                return res.path; // Used by FilePond to reference the file
                            }
                            return null;
                        },
                        onerror: (response) => {
                            const res = JSON.parse(response);
                            return res.message || 'Upload failed';
                        }
                    },
                    revert: (uniqueFileId, load, error) => {
                        // Revert logic can be added here if needed
                        // e.g., send a request to delete the file from Supabase
                        load();
                    }
                },
                labelIdle: `Drag & Drop your ${fileType} files or <span class="filepond--label-action">Browse</span>`,
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

        setupFilePond('#video-uploader', 'video');
        setupFilePond('#audio-uploader', 'audio');
        setupFilePond('#document-uploader', 'document');
    });
    </script>
@endpush
