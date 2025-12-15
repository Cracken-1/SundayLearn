<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Resource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::orderBy('created_at', 'desc')->paginate(20);
        
        $stats = [
            'total' => Resource::count(),
            'featured' => Resource::featured()->count(),
            'total_downloads' => Resource::sum('downloads_count'),
        ];
        
        return view('admin.resources.index', compact('resources', 'stats'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Relaxed type validation to allow dynamic types
            'type' => 'required|string|max:50', 
            'video_file' => 'nullable|file|max:102400|mimes:mp4,avi,mov,wmv,webm,mkv,flv',
            'audio_file' => 'nullable|file|max:102400|mimes:mp3,wav,ogg,m4a,aac,flac,wma',
            'document_file' => 'nullable|file|max:102400|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,txt,jpg,jpeg,png,gif,zip,rar',
            'thumbnail' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'age_group' => 'nullable|string|max:50',
            'is_featured' => 'nullable|boolean',
        ]);
        
        // Determine which file was uploaded and handle it
        $file = null;
        $fileCategory = null;
        
        if ($request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $fileCategory = 'video';
            $validated['type'] = 'video';
        } elseif ($request->hasFile('audio_file')) {
            $file = $request->file('audio_file');
            $fileCategory = 'audio';
            $validated['type'] = 'audio';
        } elseif ($request->hasFile('document_file')) {
            $file = $request->file('document_file');
            $fileCategory = 'document';
            // Keep the selected type for documents
        }
        
        // Validate that at least one file was uploaded
        if (!$file) {
            return back()->withErrors(['file' => 'Please select at least one file to upload.'])->withInput();
        }
        
        // Handle file upload
        $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs("resources/{$fileCategory}", $filename, 'public');
        
        $validated['file_url'] = asset('storage/' . $path);
        $validated['file_size'] = $file->getSize();
        $validated['file_type'] = $file->getClientOriginalExtension();
        $validated['file_category'] = $fileCategory;
        
        // Handle thumbnail upload
        if ($request->hasFile('thumbnail')) {
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '_thumb_' . $thumbnail->getClientOriginalName();
            $thumbnailPath = $thumbnail->storeAs('resources/thumbnails', $thumbnailName, 'public');
            $validated['thumbnail'] = asset('storage/' . $thumbnailPath);
        }
        
        $validated['is_featured'] = $request->has('is_featured');
        
        Resource::create($validated);
        
        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource created successfully!');
    }

    public function show(Resource $resource)
    {
        return view('admin.resources.show', compact('resource'));
    }

    public function edit(Resource $resource)
    {
        return view('admin.resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Relaxed type validation
            'type' => 'required|string|max:50', 
            'file' => 'nullable|file|max:51200|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip,mp4,avi,mov,wmv,webm,mp3,wav,ogg,m4a',
            'thumbnail' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'age_group' => 'nullable|string|max:50',
            'is_featured' => 'nullable|boolean',
        ]);
        
        // Handle new file upload
        if ($request->hasFile('file')) {
            // Delete old file
            if ($resource->file_url) {
                $oldPath = str_replace(asset('storage/'), '', $resource->file_url);
                Storage::disk('public')->delete($oldPath);
            }
            
            $file = $request->file('file');
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('resources/files', $filename, 'public');
            
            $validated['file_url'] = asset('storage/' . $path);
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getClientOriginalExtension();
        }
        
        // Handle new thumbnail upload
        if ($request->hasFile('thumbnail')) {
            // Delete old thumbnail
            if ($resource->thumbnail) {
                $oldThumbPath = str_replace(asset('storage/'), '', $resource->thumbnail);
                Storage::disk('public')->delete($oldThumbPath);
            }
            
            $thumbnail = $request->file('thumbnail');
            $thumbnailName = time() . '_thumb_' . $thumbnail->getClientOriginalName();
            $thumbnailPath = $thumbnail->storeAs('resources/thumbnails', $thumbnailName, 'public');
            $validated['thumbnail'] = asset('storage/' . $thumbnailPath);
        }
        
        $validated['is_featured'] = $request->has('is_featured');
        
        $resource->update($validated);
        
        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource updated successfully!');
    }

    public function destroy(Resource $resource)
    {
        // Delete files
        if ($resource->file_url) {
            $filePath = str_replace(asset('storage/'), '', $resource->file_url);
            Storage::disk('public')->delete($filePath);
        }
        
        if ($resource->thumbnail) {
            $thumbPath = str_replace(asset('storage/'), '', $resource->thumbnail);
            Storage::disk('public')->delete($thumbPath);
        }
        
        $resource->delete();
        
        return redirect()->route('admin.resources.index')
            ->with('success', 'Resource deleted successfully!');
    }
}
