<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::orderBy('event_date', 'asc')->paginate(20);
        
        $stats = [
            'total' => Event::count(),
            'upcoming' => Event::upcoming()->count(),
            'featured' => Event::featured()->count(),
        ];
        
        return view('admin.events.index', compact('events', 'stats'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_type' => 'required|in:holiday,special,seasonal,other',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);
        
        $validated['is_featured'] = $request->has('is_featured');
        $validated['color'] = $validated['color'] ?? '#dc3545';
        $validated['icon'] = $validated['icon'] ?? 'calendar';
        
        Event::create($validated);
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'event_type' => 'required|in:holiday,special,seasonal,other',
            'color' => 'nullable|string|max:7',
            'icon' => 'nullable|string|max:50',
            'is_featured' => 'nullable|boolean',
            'display_order' => 'nullable|integer',
        ]);
        
        $validated['is_featured'] = $request->has('is_featured');
        
        $event->update($validated);
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event)
    {
        $event->delete();
        
        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
