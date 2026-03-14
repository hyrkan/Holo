<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\AnnouncementAttachment;
use App\Jobs\SendAnnouncementNotifications;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $announcements = Announcement::where('is_archived', false)->latest()->paginate(10);
        return view('admin.announcements.index', compact('announcements'));
    }

    public function archived()
    {
        $announcements = Announcement::where('is_archived', true)->latest()->paginate(10);
        return view('admin.announcements.archived', compact('announcements'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.announcements.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpeg,png,jpg,gif|max:10240',
            'target_audience' => 'required|string|in:all,students,guests',
            'target_year_levels' => 'nullable|array',
            'target_year_levels.*' => 'string',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('announcements', 'public');
        }

        $validated['start_date'] = now();
        $validated['end_date'] = now();
        $validated['is_active'] = true;
        $validated['is_draft'] = false;

        $announcementData = collect($validated)->except(['attachments'])->toArray();
        $announcement = Announcement::create($announcementData);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('announcement_attachments', 'public');
                $announcement->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        // Dispatch queued email notifications to targeted students
        SendAnnouncementNotifications::dispatch($announcement);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement created and notifications queued successfully.');
    }

    public function show(Announcement $announcement)
    {
        return view('admin.announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        return view('admin.announcements.edit', compact('announcement'));
    }

    public function update(Request $request, Announcement $announcement)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'attachments.*' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpeg,png,jpg,gif|max:10240',
            'target_audience' => 'required|string|in:all,students,guests',
            'target_year_levels' => 'nullable|array',
            'target_year_levels.*' => 'string',
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($announcement->image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($announcement->image);
            }
            $validated['image'] = $request->file('image')->store('announcements', 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_draft'] = $request->has('is_draft');

        $announcementData = collect($validated)->except(['attachments'])->toArray();
        $announcement->update($announcementData);

        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('announcement_attachments', 'public');
                $announcement->attachments()->create([
                    'file_path' => $path,
                    'file_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientOriginalExtension(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement updated successfully.');
    }

    public function destroy(Announcement $announcement)
    {
        $announcement->update(['is_archived' => true]);

        return redirect()->route('admin.announcements.index')
            ->with('success', 'Announcement archived successfully.');
    }

    public function restore(Announcement $announcement)
    {
        $announcement->update(['is_archived' => false]);

        return redirect()->route('admin.announcements.archived')
            ->with('success', 'Announcement restored successfully.');
    }

    public function deleteAttachment(AnnouncementAttachment $attachment)
    {
        \Illuminate\Support\Facades\Storage::disk('public')->delete($attachment->file_path);
        $attachment->delete();

        return response()->json(['success' => true]);
    }
}
