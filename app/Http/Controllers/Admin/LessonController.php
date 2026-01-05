<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Services\SupabaseStorageService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class LessonController extends Controller
{
    protected $storageService;

    public function __construct(SupabaseStorageService $storageService)
    {
        $this->storageService = $storageService;
    }

    public function index()
    {
        $lessons = Lesson::orderBy('created_at', 'desc')->paginate(20);
        $stats = [
            'total' => Lesson::count(),
            'published' => Lesson::where('status', 'published')->count(),
            'drafts' => Lesson::where('status', 'draft')->count(),
        ];
        return view('admin.lessons.index', compact('lessons', 'stats'));
    }

    public function create()
    {
        return view('admin.lessons.create');
    }

    public function store(Request $request)
    {
        $validated = $this->validateLesson($request);

        $validated['slug'] = $this->generateUniqueSlug($validated['title']);

        // Process textual data that needs to be stored as JSON
        $this->processJsonFields($validated);

        if ($validated['status'] === 'published') {
            $validated['published_at'] = now();
        }

        // Handle attachments from Supabase
        $validated['attachments'] = $this->processAttachments($request);

        $lesson = Lesson::create($validated);

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson created successfully.');
    }

    public function edit(Lesson $lesson)
    {
        return view('admin.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, Lesson $lesson)
    {
        $validated = $this->validateLesson($request, $lesson->id);

        if ($validated['title'] !== $lesson->title) {
            $validated['slug'] = $this->generateUniqueSlug($validated['title'], $lesson->id);
        }

        $this->processJsonFields($validated);

        if ($validated['status'] === 'published' && $lesson->status !== 'published') {
            $validated['published_at'] = now();
        }

        // Merge new attachments with existing ones
        $newAttachments = $this->processAttachments($request);
        $existingAttachments = $lesson->attachments ?? [];
        $validated['attachments'] = array_merge($existingAttachments, $newAttachments);

        $lesson->update($validated);

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson updated successfully.');
    }

    public function destroy(Lesson $lesson)
    {
        // Delete all attachments from Supabase
        if (!empty($lesson->attachments)) {
            foreach ($lesson->attachments as $attachment) {
                if (isset($attachment['path'])) {
                    $this->storageService->delete($attachment['path'], 'lessons-attachments');
                }
            }
        }

        $lesson->delete();

        return redirect()->route('admin.lessons.index')->with('success', 'Lesson deleted successfully.');
    }

    public function removeAttachment($id, $index)
    {
        $lesson = Lesson::findOrFail($id);
        $attachments = $lesson->attachments ?? [];

        if (isset($attachments[$index])) {
            // Delete from Supabase
            if (isset($attachments[$index]['path'])) {
                $this->storageService->delete($attachments[$index]['path'], 'lessons-attachments');
            }

            array_splice($attachments, $index, 1);
            $lesson->update(['attachments' => $attachments]);

            return response()->json(['success' => true, 'message' => 'Attachment removed.']);
        }

        return response()->json(['success' => false, 'message' => 'Attachment not found.'], 404);
    }

    private function validateLesson(Request $request, $lessonId = null)
    {
        $rules = [
            'title' => 'required|string|max:255',
            'status' => 'required|in:draft,published',
            'content' => 'required|string',
            'age_group' => 'nullable|string',
            'category' => 'nullable|string',
            'excerpt' => 'nullable|string',
            'video_attachments' => 'nullable|array',
            'audio_attachments' => 'nullable|array',
            'document_attachments' => 'nullable|array',
        ];

        return $request->validate($rules);
    }

    private function generateUniqueSlug($title, $excludeId = null)
    {
        $slug = Str::slug($title);
        $originalSlug = $slug;
        $counter = 1;

        $query = Lesson::where('slug', $slug);
        if ($excludeId) {
            $query->where('id', '!=', $excludeId);
        }

        while ($query->exists()) {
            $slug = $originalSlug . '-' . $counter++;
            $query = Lesson::where('slug', $slug);
            if ($excludeId) {
                $query->where('id', '!=', $excludeId);
            }
        }

        return $slug;
    }

    private function processJsonFields(&$validated)
    {
        // Example for a 'tags' field if you add it back
        if (isset($validated['tags'])) {
            $validated['tags'] = array_filter(array_map('trim', explode(',', $validated['tags'])));
        }
    }

    private function processAttachments(Request $request)
    {
        $attachments = [];
        $types = ['video_attachments', 'audio_attachments', 'document_attachments'];

        foreach ($types as $type) {
            if ($request->has($type)) {
                foreach ($request->input($type) as $fileData) {
                    // The input is expected to be a JSON string of file details
                    $data = json_decode($fileData, true);
                    if ($data && isset($data['path'])) {
                        $attachments[] = [
                            'type' => str_replace('_attachments', '', $type),
                            'path' => $data['path'],
                            'url' => $data['url'],
                            'filename' => $data['filename'],
                            'size' => $data['file_size'],
                        ];
                    }
                }
            }
        }

        return $attachments;
    }
}
