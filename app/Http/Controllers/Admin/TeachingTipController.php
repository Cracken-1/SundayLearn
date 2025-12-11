<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TeachingTip;
use Illuminate\Http\Request;

class TeachingTipController extends Controller
{
    public function index()
    {
        $teachingTips = TeachingTip::orderBy('display_order', 'asc')->paginate(20);
        
        $stats = [
            'total' => TeachingTip::count(),
            'active' => TeachingTip::active()->count(),
            'inactive' => TeachingTip::where('is_active', false)->count(),
        ];
        
        return view('admin.teaching-tips.index', compact('teachingTips', 'stats'));
    }

    public function create()
    {
        return view('admin.teaching-tips.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        $validated['icon'] = $validated['icon'] ?? 'lightbulb';
        
        TeachingTip::create($validated);
        
        return redirect()->route('admin.teaching-tips.index')
            ->with('success', 'Teaching tip created successfully!');
    }

    public function show(TeachingTip $teachingTip)
    {
        return view('admin.teaching-tips.show', compact('teachingTip'));
    }

    public function edit(TeachingTip $teachingTip)
    {
        return view('admin.teaching-tips.edit', compact('teachingTip'));
    }

    public function update(Request $request, TeachingTip $teachingTip)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'icon' => 'nullable|string|max:50',
            'category' => 'nullable|string|max:100',
            'is_active' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);
        
        $validated['is_active'] = $request->has('is_active');
        
        $teachingTip->update($validated);
        
        return redirect()->route('admin.teaching-tips.index')
            ->with('success', 'Teaching tip updated successfully!');
    }

    public function destroy(TeachingTip $teachingTip)
    {
        $teachingTip->delete();
        
        return redirect()->route('admin.teaching-tips.index')
            ->with('success', 'Teaching tip deleted successfully!');
    }
}
