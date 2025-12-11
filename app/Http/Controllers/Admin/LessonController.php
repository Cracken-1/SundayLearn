<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lesson;
use Illuminate\Http\Request;

class LessonController extends Controller
{
    public function index()
    {
        $lessons = Lesson::orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Lesson::count(),
            'published' => Lesson::where('status', 'published')->count(),
            'drafts' => Lesson::where('status', 'draft')->count(),
            'this_month' => Lesson::whereMonth('created_at', now()->month)->count()
        ];

        return view('admin.lessons.index', compact('lessons', 'stats'));
    }

    public function create()
    {
        return view('admin.lessons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'age_group' => 'required|string',
            'category' => 'nullable|string',
            'difficulty' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'content' => 'required|string',
            'scripture' => 'nullable|string|max:255',
            'theme' => 'nullable|string|max:255',
            'duration' => 'nullable|integer|min:1',
            'excerpt' => 'nullable|string',
            'overview' => 'nullable|string',
            'objectives' => 'nullable|string',
            'discussion_questions' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'audio_url' => 'nullable|url|max:500',
            'thumbnail' => 'nullable|string|max:500',
            'image_url' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,zip',
        ]);

        // Generate slug from title
        $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
        
        // Ensure unique slug
        $originalSlug = $validated['slug'];
        $counter = 1;
        while (Lesson::where('slug', $validated['slug'])->exists()) {
            $validated['slug'] = $originalSlug . '-' . $counter;
            $counter++;
        }

        // Convert string fields to arrays for JSON columns
        if (isset($validated['objectives'])) {
            $validated['objectives'] = array_filter(array_map('trim', explode("\n", $validated['objectives'])));
        }
        if (isset($validated['discussion_questions'])) {
            $validated['discussion_questions'] = array_filter(array_map('trim', explode("\n", $validated['discussion_questions'])));
        }
        if (isset($validated['tags'])) {
            $validated['tags'] = array_filter(array_map('trim', explode(',', $validated['tags'])));
        }

        // Handle file attachments
        $attachments = [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $mimeType = $file->getMimeType();
                $size = $file->getSize();
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '_' . $originalName;
                
                // Store file in public/storage/lessons/attachments
                $path = $file->storeAs('lessons/attachments', $filename, 'public');
                
                $attachments[] = [
                    'name' => $originalName,
                    'filename' => $filename,
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'type' => $extension,
                    'mime_type' => $mimeType,
                    'size' => $size,
                    'uploaded_at' => now()->toDateTimeString()
                ];
            }
        }
        
        $validated['attachments'] = $attachments;

        // Set published_at if status is published
        if ($validated['status'] === 'published' && !isset($validated['published_at'])) {
            $validated['published_at'] = now();
        }

        $lesson = Lesson::create($validated);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson created successfully!');
    }

    public function show($id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('admin.lessons.show', compact('lesson'));
    }

    public function edit($id)
    {
        $lesson = Lesson::findOrFail($id);
        return view('admin.lessons.edit', compact('lesson'));
    }

    public function update(Request $request, $id)
    {
        $lesson = Lesson::findOrFail($id);
        
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'age_group' => 'required|string',
            'category' => 'nullable|string',
            'difficulty' => 'nullable|string',
            'status' => 'required|in:draft,published',
            'content' => 'required|string',
            'scripture' => 'nullable|string|max:255',
            'theme' => 'nullable|string|max:255',
            'duration' => 'nullable|integer|min:1',
            'excerpt' => 'nullable|string',
            'overview' => 'nullable|string',
            'objectives' => 'nullable|string',
            'discussion_questions' => 'nullable|string',
            'video_url' => 'nullable|url|max:500',
            'audio_url' => 'nullable|url|max:500',
            'thumbnail' => 'nullable|string|max:500',
            'image_url' => 'nullable|string|max:500',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'is_featured' => 'nullable|boolean',
            'tags' => 'nullable|string',
            'attachments.*' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,zip',
        ]);

        // Update slug if title changed
        if ($validated['title'] !== $lesson->title) {
            $validated['slug'] = \Illuminate\Support\Str::slug($validated['title']);
            
            // Ensure unique slug (excluding current lesson)
            $originalSlug = $validated['slug'];
            $counter = 1;
            while (Lesson::where('slug', $validated['slug'])->where('id', '!=', $lesson->id)->exists()) {
                $validated['slug'] = $originalSlug . '-' . $counter;
                $counter++;
            }
        }

        // Convert string fields to arrays for JSON columns
        if (isset($validated['objectives'])) {
            $validated['objectives'] = array_filter(array_map('trim', explode("\n", $validated['objectives'])));
        }
        if (isset($validated['discussion_questions'])) {
            $validated['discussion_questions'] = array_filter(array_map('trim', explode("\n", $validated['discussion_questions'])));
        }
        if (isset($validated['tags'])) {
            $validated['tags'] = array_filter(array_map('trim', explode(',', $validated['tags'])));
        }

        // Handle new file attachments
        $existingAttachments = $lesson->attachments ?? [];
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $originalName = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $mimeType = $file->getMimeType();
                $size = $file->getSize();
                
                // Generate unique filename
                $filename = time() . '_' . uniqid() . '_' . $originalName;
                
                // Store file in public/storage/lessons/attachments
                $path = $file->storeAs('lessons/attachments', $filename, 'public');
                
                $existingAttachments[] = [
                    'name' => $originalName,
                    'filename' => $filename,
                    'path' => $path,
                    'url' => asset('storage/' . $path),
                    'type' => $extension,
                    'mime_type' => $mimeType,
                    'size' => $size,
                    'uploaded_at' => now()->toDateTimeString()
                ];
            }
        }
        
        $validated['attachments'] = $existingAttachments;

        // Set published_at if status changed to published
        if ($validated['status'] === 'published' && $lesson->status !== 'published') {
            $validated['published_at'] = now();
        }

        $lesson->update($validated);

        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson updated successfully!');
    }

    public function destroy($id)
    {
        $lesson = Lesson::findOrFail($id);
        
        // Delete all attachment files
        if (!empty($lesson->attachments)) {
            foreach ($lesson->attachments as $attachment) {
                if (isset($attachment['path'])) {
                    \Storage::disk('public')->delete($attachment['path']);
                }
            }
        }
        
        $lesson->delete();
        
        return redirect()->route('admin.lessons.index')
            ->with('success', 'Lesson deleted successfully!');
    }
    
    public function removeAttachment($id, $index)
    {
        $lesson = Lesson::findOrFail($id);
        $attachments = $lesson->attachments ?? [];
        
        if (isset($attachments[$index])) {
            // Delete the file from storage
            if (isset($attachments[$index]['path'])) {
                \Storage::disk('public')->delete($attachments[$index]['path']);
            }
            
            // Remove from array
            array_splice($attachments, $index, 1);
            
            // Update lesson
            $lesson->update(['attachments' => $attachments]);
            
            return response()->json(['success' => true, 'message' => 'Attachment removed successfully']);
        }
        
        return response()->json(['success' => false, 'message' => 'Attachment not found'], 404);
    }
}