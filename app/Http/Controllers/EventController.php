<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Helpers\ImageStorage;
use App\Jobs\SendEventNotifications;
use Illuminate\Http\Request;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');

        $events = Event::with('eventDates')->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%")
                         ->orWhere('location', 'like', "%{$search}%");
        })
        ->latest()
        ->paginate(10);

        return view('admin.events.index', compact('events'));
    }

    public function exportCsv(Request $request)
    {
        $search = $request->input('search');
        $events = Event::with('eventDates')->when($search, function ($query, $search) {
            return $query->where('name', 'like', "%{$search}%")
                         ->orWhere('description', 'like', "%{$search}%")
                         ->orWhere('location', 'like', "%{$search}%");
        })
        ->latest()
        ->get();
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="events.csv"',
        ];
        $callback = function () use ($events) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Name', 'Location', 'Departments', 'Capacity', 'Dates', 'Created At']);
            foreach ($events as $e) {
                $dates = $e->eventDates->sortBy('date')->pluck('date')->map(function($d){
                    return \Carbon\Carbon::parse($d)->format('Y-m-d');
                })->implode(', ');
                $departments = (!$e->departments || in_array('All', (array)$e->departments)) ? 'All' : implode(', ', (array)$e->departments);
                fputcsv($handle, [
                    $e->name,
                    $e->location,
                    $departments,
                    $e->capacity ?: 'Unlimited',
                    $dates ?: 'No dates',
                    optional($e->created_at)->toDateTimeString(),
                ]);
            }
            fclose($handle);
        };
        return response()->streamDownload($callback, 'events.csv', $headers);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $speakers = \App\Models\Speaker::active()->get();
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

        $imagePath = ImageStorage::upload($request->file('image'), 'events');

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

        SendEventNotifications::dispatchSync($event);

        return redirect()->route('admin.events.index')->with('success', 'Event created and email notifications sent successfully.');
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
        // Get all active speakers
        $activeSpeakers = \App\Models\Speaker::active()->get();
        
        // Get speakers currently attached to the event (even if inactive)
        $eventSpeakers = $event->speakers;
        
        // Merge and remove duplicates
        $speakers = $activeSpeakers->merge($eventSpeakers)->unique('id');
        
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
            ImageStorage::delete($event->image);
            $data['image'] = ImageStorage::upload($request->file('image'), 'events');
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
        if ($event->image) {
            ImageStorage::delete($event->image);
        }

        $event->speakers()->detach();
        $event->eventDates()->delete();
        // Students/Registrations and Certificates should ideally cascade, but we'll try to detach/delete them to be safe
        $event->students()->detach();
        $event->certificates()->delete();
        
        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Event deleted successfully.');
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

        // Check if event is in the past
        $lastDate = $event->eventDates->max('date');
        if ($lastDate && \Carbon\Carbon::parse($lastDate)->endOfDay()->isPast()) {
            return back()->with('error', 'This event has already ended.');
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

    /**
     * Display the attendance sheet of the specified event.
     */
    public function attendance(Event $event)
    {
        $event->load(['eventDates', 'students.user', 'students.attendances']);
        $dates = $event->eventDates->sortBy('date');
        $participants = $event->students;
        
        return view('admin.events.attendance', compact('event', 'dates', 'participants'));
    }
}
