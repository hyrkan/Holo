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
            'tags' => $tags,
            'capacity' => $request->capacity,
            'departments' => $request->departments,
        ]);

        foreach ($request->dates as $date) {
            $event->eventDates()->create(['date' => $date]);
        }

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
            'tags' => $tags,
            'capacity' => $request->capacity,
            'departments' => $request->departments,
        ];

        if ($request->hasFile('image')) {
            if ($event->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('events', 'public');
        }

        $event->update($data);

        // Update dates: for simplicity, delete all and recreate or sync
        $event->eventDates()->delete();
        foreach ($request->dates as $date) {
            $event->eventDates()->create(['date' => $date]);
        }

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

    /**
     * Student joins an event.
     */
    public function join(Event $event)
    {
        $student = auth()->guard('student')->user()->student;

        // Check if already registered
        if ($event->students()->where('student_id', $student->id)->exists()) {
            return back()->with('error', 'You are already registered for this event.');
        }

        // Check capacity
        if ($event->capacity && $event->students()->count() >= $event->capacity) {
            return back()->with('error', 'Event is full.');
        }

        $event->students()->attach($student->id, ['status' => 'registered']);

        return back()->with('success', 'You have successfully joined the event.');
    }

    /**
     * Display the participants of the specified event.
     */
    public function participants(Event $event)
    {
        $participants = $event->students()->with('user')->get();
        return view('admin.events.participants', compact('event', 'participants'));
    }
}
