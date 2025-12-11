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
            'type' => 'required|in:worksheet,coloring_page,activity_guide,craft,game,other',
            'file' => 'required|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip',
            'thumbnail' => 'nullable|image|max:2048',
            'category' => 'nullable|string|max:100',
            'age_group' => 'nullable|string|max:50',
            'is_featured' => 'nullable|boolean',
        ]);
        
        // Handle file upload
        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $filename = time() . '_' . uniqid() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('resources/files', $filename, 'public');
            
            $validated['file_url'] = asset('storage/' . $path);
            $validated['file_size'] = $file->getSize();
            $validated['file_type'] = $file->getClientOriginalExtension();
        }
        
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
            'type' => 'required|in:worksheet,coloring_page,activity_guide,craft,game,other',
            'file' => 'nullable|file|max:10240|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,jpg,jpeg,png,zip',
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
