<?php

namespace App\Http\Controllers;

use App\Mail\LostAndFoundResolvedMail;
use App\Jobs\SendAnnouncementNotifications;
use App\Models\Announcement;
use App\Models\LostAndFound;
use App\Helpers\Messenger;
use App\Helpers\ImageStorage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LostAndFoundController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $type = $request->get('type', 'all');

        if ($type === 'returned') {
            $query = LostAndFound::where('status', 'resolved')->latest('resolved_at');
        } else {
            $query = LostAndFound::where('status', 'active')->latest();
            if ($type !== 'all') {
                $query->where('type', $type);
            }
        }

        $items = $query->with('user')->get();

        $recentlyResolved = LostAndFound::where('status', 'resolved')
            ->latest('resolved_at')
            ->take(4)
            ->get();

        return view('lost-and-found.index', compact('items', 'type', 'recentlyResolved'));
    }

    public function show(LostAndFound $lostAndFound)
    {
        $isAdmin = Auth::check();
        $isOwnerStudent = Auth::guard('student')->check() && Auth::guard('student')->id() === $lostAndFound->user_id;
        if (!in_array($lostAndFound->status, ['active', 'resolved']) && !$isAdmin && !$isOwnerStudent) {
            return redirect()->route('lost-and-found.index')->with('error', 'This report is not available.');
        }
        return view('lost-and-found.show', compact('lostAndFound'));
    }

    public function adminShow(LostAndFound $lost_and_found)
    {
        $lost_and_found->load(['user', 'resolver', 'matchedItem']);

        return view('admin.lost-and-found.show', compact('lost_and_found'));
    }

    /**
     * Admin view to manage reports
     */
    public function adminIndex()
    {
        $items = LostAndFound::with('user')->latest()->get();

        return view('admin.lost-and-found.index', compact('items'));
    }

    /**
     * Show admin create form
     */
    public function adminCreate(Request $request)
    {
        $type = $request->get('type', 'lost');

        return view('admin.lost-and-found.create', compact('type'));
    }

    /**
     * Store report from student or public
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_info' => 'required|email|max:255',
            'is_anonymous' => 'boolean',
            'reporter_name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = ImageStorage::upload($request->file('image'), 'lost-and-found');
        }

        unset($validated['image']);
        $validated['is_anonymous'] = false; // Always false now

        // Associate with logged in user (student or employee/admin)
        if (Auth::guard('student')->check()) {
            $validated['user_id'] = Auth::guard('student')->id();
            $validated['type'] = 'lost'; // Students can only report lost items
        } elseif (Auth::check()) {
            $validated['user_id'] = Auth::id();
        }

        $validated['date_reported'] = now();
        $validated['status'] = 'pending';

        LostAndFound::create($validated);

        if (Auth::guard('student')->check()) {
            // Student reports start as pending in DB, but we still allow showing a generic success message.
            return redirect()->route('student.lost-and-found.my-reports')
                ->with('success', 'Your report has been submitted successfully.');
        }

        if (Auth::check()) {
            return redirect()->route('admin.lost-and-found.index')
                ->with('success', 'Report created successfully.');
        }

        return redirect()->route('lost-and-found.index')
            ->with('success', 'Report submitted successfully. Thank you for your help!');
    }

    /**
     * Admin store report
     */
    public function adminStore(Request $request)
    {
        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'type' => 'required|in:lost,found',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_info' => 'required|email|max:255',
            'is_anonymous' => 'boolean',
            'reporter_name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            $validated['image_path'] = ImageStorage::upload($request->file('image'), 'lost-and-found');
        }

        unset($validated['image']);
        // Automatically tag as anonymous if requested
        $validated['is_anonymous'] = $request->has('is_anonymous');

        $validated['user_id'] = Auth::id(); // Admin/Staff user
        $validated['status'] = 'active';
        $validated['date_reported'] = now();

        LostAndFound::create($validated);

        return redirect()->route('admin.lost-and-found.index')
            ->with('success', 'Report created successfully.');
    }

    /**
     * Show resolve form
     */
    public function resolve(LostAndFound $lost_and_found)
    {
        // Find potential matches from the opposite type that are still active
        $oppositeType = $lost_and_found->type == 'lost' ? 'found' : 'lost';
        $potentialMatches = LostAndFound::where('type', $oppositeType)
            ->where('status', 'active')
            ->latest()
            ->get();

        return view('admin.lost-and-found.resolve', compact('lost_and_found', 'potentialMatches'));
    }

    /**
     * Store resolution with proof
     */
    public function storeResolution(Request $request, LostAndFound $lost_and_found)
    {
        $validated = $request->validate([
            'handover_image' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
            'matched_item_id' => 'nullable|exists:lost_and_founds,id',
            'returned_by_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('handover_image')) {
            $lost_and_found->handover_image_path = ImageStorage::upload($request->file('handover_image'), 'handovers');
        }

        $lost_and_found->matched_item_id = $validated['matched_item_id'] ?? null;
        $lost_and_found->returned_by_name = $validated['returned_by_name'] ?? null;
        $lost_and_found->status = 'resolved';
        $lost_and_found->resolved_at = now();
        $lost_and_found->resolved_by = Auth::id();
        $lost_and_found->save();

        // Notify reporter if email is provided
        $recipient = trim((string) $lost_and_found->contact_info);
        if ($recipient && filter_var($recipient, FILTER_VALIDATE_EMAIL)) {
            Messenger::send($recipient, new LostAndFoundResolvedMail($lost_and_found));
        }

        // If a matching report was selected, resolve it too
        if (!empty($validated['matched_item_id'])) {
            $matchedItem = LostAndFound::find($validated['matched_item_id']);
            if ($matchedItem && $matchedItem->status == 'active') {
                $matchedItem->status = 'resolved';
                $matchedItem->resolved_at = now();
                $matchedItem->resolved_by = Auth::id();
                $matchedItem->matched_item_id = $lost_and_found->id; // Mutual link
                $matchedItem->identity_proof_ref = 'Resolved via matched report #'.$lost_and_found->id;
                $matchedItem->save();

                // Notify reporter of the matched item too if email is provided
                $matchedRecipient = trim((string) $matchedItem->contact_info);
                if ($matchedRecipient && filter_var($matchedRecipient, FILTER_VALIDATE_EMAIL)) {
                    Messenger::send($matchedRecipient, new LostAndFoundResolvedMail($matchedItem));
                }
            }
        }

        if ($lost_and_found->type === 'lost') {
            $title = 'Lost Item Resolved: ' . $lost_and_found->item_name;
            $body = '<p>A previously reported lost item has been marked as resolved.</p>'
                . '<ul>'
                . '<li><strong>Item:</strong> ' . e($lost_and_found->item_name) . '</li>'
                . '<li><strong>Location:</strong> ' . e($lost_and_found->location) . '</li>'
                . '<li><strong>Resolved Date:</strong> ' . e(optional($lost_and_found->resolved_at)->format('M d, Y')) . '</li>'
                . ($lost_and_found->returned_by_name ? '<li><strong>Returned By:</strong> ' . e($lost_and_found->returned_by_name) . '</li>' : '')
                . '</ul>'
                . '<p>Thank you to everyone who helped. Please check with the office for any follow-up.</p>';

            $announcement = Announcement::create([
                'title' => $title,
                'body' => $body,
                'image' => $lost_and_found->image_path,
                'is_active' => true,
                'is_draft' => false,
                'is_archived' => false,
                'start_date' => now(),
                'end_date' => now()->addDays(14),
                'target_audience' => 'all',
                'target_year_levels' => null,
            ]);

            try {
                dispatch(new SendAnnouncementNotifications($announcement));
            } catch (\Throwable $e) {
            }
        }

        return redirect()->route('admin.lost-and-found.index')
            ->with('success', 'Item and its matching report (if any) have been marked as resolved.');
    }

    public function destroy(LostAndFound $lost_and_found)
    {
        ImageStorage::delete($lost_and_found->image_path);
        ImageStorage::delete($lost_and_found->handover_image_path);

        $lost_and_found->delete();

        return redirect()->route('admin.lost-and-found.index')
            ->with('success', 'Report deleted successfully.');
    }

    public function adminApprove(LostAndFound $lost_and_found)
    {
        $shouldAnnounce = ($lost_and_found->status !== 'active') && ($lost_and_found->type === 'lost');
        if ($lost_and_found->status !== 'active') {
            $lost_and_found->status = 'active';
            if (!$lost_and_found->date_reported) {
                $lost_and_found->date_reported = now();
            }
            $lost_and_found->save();
        }
        if ($shouldAnnounce) {
            $title = 'Lost Item Reported: ' . $lost_and_found->item_name;
            $reporter = $lost_and_found->is_anonymous ? 'Anonymous' : ($lost_and_found->reporter_name ?: 'Reporter');
            $body = '<p>A lost item has been reported and approved for posting.</p>'
                . '<ul>'
                . '<li><strong>Item:</strong> ' . e($lost_and_found->item_name) . '</li>'
                . '<li><strong>Location:</strong> ' . e($lost_and_found->location) . '</li>'
                . '<li><strong>Reported by:</strong> ' . e($reporter) . '</li>'
                . '<li><strong>Date Reported:</strong> ' . e(optional($lost_and_found->date_reported)->format('M d, Y')) . '</li>'
                . '</ul>'
                . '<p>If you have any information or found this item, please reach out to the office.</p>';

            $announcement = Announcement::create([
                'title' => $title,
                'body' => $body,
                'image' => $lost_and_found->image_path,
                'is_active' => true,
                'is_draft' => false,
                'is_archived' => false,
                'start_date' => now(),
                'end_date' => now()->addDays(14),
                'target_audience' => 'all',
                'target_year_levels' => null,
            ]);

            try {
                dispatch(new SendAnnouncementNotifications($announcement));
            } catch (\Throwable $e) {
                // ignore dispatch failure; announcement remains posted
            }
        }
        return redirect()->route('admin.lost-and-found.index')->with('success', 'Report approved and published.');
    }

    public function studentEdit(LostAndFound $lost_and_found)
    {
        $studentId = Auth::guard('student')->id();
        if ($lost_and_found->user_id !== $studentId || $lost_and_found->status !== 'active') {
            return redirect()->route('student.lost-and-found.my-reports')->with('error', 'You can only edit your active reports.');
        }
        $mode = 'edit';
        return view('lost-and-found.create', compact('lost_and_found', 'mode'));
    }

    public function studentUpdate(Request $request, LostAndFound $lost_and_found)
    {
        $studentId = Auth::guard('student')->id();
        if ($lost_and_found->user_id !== $studentId || $lost_and_found->status !== 'active') {
            return redirect()->route('student.lost-and-found.my-reports')->with('error', 'You can only edit your active reports.');
        }

        $validated = $request->validate([
            'item_name' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'contact_info' => 'required|email|max:255',
            'reporter_name' => 'required|string|max:255',
            'owner_name' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('image')) {
            ImageStorage::delete($lost_and_found->image_path);
            $validated['image_path'] = ImageStorage::upload($request->file('image'), 'lost-and-found');
        }

        unset($validated['image']);

        $lost_and_found->update($validated);

        return redirect()->route('student.lost-and-found.my-reports')->with('success', 'Report updated successfully.');
    }
}
