<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $events = Event::when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%")
                         ->orWhere('location', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $speakers = \App\Models\Speaker::all();
        return view('admin.events.create', compact('speakers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dates' => 'required|array',
            'dates.*' => 'date',
            'speakers' => 'nullable|array',
            'speakers.*' => 'exists:speakers,id',
            'tags' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
            'departments' => 'nullable|array',
            'departments.*' => 'string',
        ]);

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

        $imagePath = $request->file('image')->store('events', 'public');

        $event = Event::create([
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'image' => $imagePath,
            'image' => $imagePath,
            'dates' => $request->dates,
            'tags' => $tags,
            'capacity' => $request->capacity,
            'departments' => $request->departments,
        ]);

        if ($request->has('speakers')) {
            $event->speakers()->attach($request->speakers);
        }

        return redirect()->route('admin.events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Event $event)
    {
        return view('admin.events.show', compact('event'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Event $event)
    {
        $speakers = \App\Models\Speaker::all();
        return view('admin.events.edit', compact('event', 'speakers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Event $event)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'dates' => 'required|array',
            'dates.*' => 'date',
            'speakers' => 'nullable|array',
            'speakers.*' => 'exists:speakers,id',
            'tags' => 'nullable|string',
            'capacity' => 'nullable|integer|min:0',
            'departments' => 'nullable|array',
            'departments.*' => 'string',
        ]);

        $tags = $request->tags ? array_map('trim', explode(',', $request->tags)) : [];

        $data = [
            'name' => $request->name,
            'description' => $request->description,
            'location' => $request->location,
            'location' => $request->location,
            'dates' => $request->dates,
            'tags' => $tags,
            'capacity' => $request->capacity,
            'departments' => $request->departments,
        ];

        if ($request->hasFile('image')) {
            // Delete old image if needed, for potentially better cleanup
            if ($event->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        if ($request->has('speakers')) {
            $event->speakers()->sync($request->speakers);
        } else {
            $event->speakers()->detach();
        }

        return redirect()->route('admin.events.index')->with('success', 'Event updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Event $event)
    {
        //
    }
}
